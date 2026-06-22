<?php

class Services {

    private function code($prefix) {
        return strtoupper($prefix) . '-' . date('Y') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    // Submit any request — returns [success, code/error]
    public function submit($type, $data) {
        $code = $this->code($type);
        $staff = $_SESSION['aj.gaclintra']['user'] ?? 'Unknown';
        $dept  = $_SESSION['aj.gaclintra']['dept']  ?? '';
        $email = $_SESSION['aj.gaclintra']['email'] ?? '';
        $sid   = $_SESSION['aj.gaclintra']['id']    ?? null;

        // Insert master record
        $r = myFunc::myQuery(
            "INSERT INTO hcos_requests (request_code,type,staff_id,staff_name,staff_email,department) VALUES (?,?,?,?,?,?)",
            'sissss', [$code, $type, $sid, $staff, $email, $dept], 'action'
        );
        if (!$r) return [false, 'Could not save request. Please try again.'];

        // Get inserted id
        include(__ROOT__ . 'core/db.php');
        $rid = $conn->insert_id;
        $conn->close();

        // Insert type-specific record
        switch ($type) {
            case 'travel':
                myFunc::myQuery(
                    "INSERT INTO hcos_travel (request_id,travel_type,destination,airport_code,purpose,departure_date,return_date,num_travellers,travellers,accommodation,transport,per_diem,extra_notes)
                     VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)",
                    'issssssisiiis',
                    [$rid, $data['travel_type'], $data['destination'], $data['airport_code'] ?? '',
                     $data['purpose'], $data['departure_date'], $data['return_date'],
                     $data['num_travellers'] ?? 1, $data['travellers'] ?? '',
                     isset($data['accommodation']) ? 1 : 0,
                     isset($data['transport'])     ? 1 : 0,
                     isset($data['per_diem'])      ? 1 : 0,
                     $data['extra_notes'] ?? ''],
                    'action'
                );
                break;
            case 'letter':
                myFunc::myQuery(
                    "INSERT INTO hcos_letters (request_id,letter_type,addressed_to,country,purpose,urgency,extra_notes)
                     VALUES (?,?,?,?,?,?,?)",
                    'issssss',
                    [$rid, $data['letter_type'], $data['addressed_to'] ?? '',
                     $data['country'] ?? '', $data['purpose'],
                     $data['urgency'] ?? 'normal', $data['extra_notes'] ?? ''],
                    'action'
                );
                break;
            case 'loan':
                myFunc::myQuery(
                    "INSERT INTO hcos_loans (request_id,loan_type,amount,reason,repayment,extra_notes)
                     VALUES (?,?,?,?,?,?)",
                    'isdsss',
                    [$rid, $data['loan_type'], $data['amount'] ?? 0,
                     $data['reason'], $data['repayment'] ?? '', $data['extra_notes'] ?? ''],
                    'action'
                );
                break;
            case 'office':
                myFunc::myQuery(
                    "INSERT INTO hcos_office (request_id,service_type,description,location,urgency,extra_notes)
                     VALUES (?,?,?,?,?,?)",
                    'isssss',
                    [$rid, $data['service_type'], $data['description'],
                     $data['location'] ?? '', $data['urgency'] ?? 'normal',
                     $data['extra_notes'] ?? ''],
                    'action'
                );
                break;
        }
        return [true, $code];
    }

    // Track a request by code
    public function track($code) {
        $r = myFunc::myQuery(
            "SELECT * FROM hcos_requests WHERE request_code = ?", 's', [$code], 'fetch'
        );
        return $r ?: false;
    }

    // All requests for the logged-in staff member
    public function myRequests() {
        $staff = $_SESSION['aj.gaclintra']['user'] ?? '';
        $r = myFunc::myQuery(
            "SELECT * FROM hcos_requests WHERE staff_name = ? ORDER BY submitted_at DESC",
            's', [$staff], 'result'
        );
        if (!$r || $r->num_rows === 0) return false;
        $rows = [];
        while ($row = $r->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    // Admin: all requests (optionally filtered by type/status)
    public function allRequests($type = null, $status = null) {
        if ($type && $status) {
            $r = myFunc::myQuery(
                "SELECT * FROM hcos_requests WHERE type=? AND status=? ORDER BY submitted_at DESC",
                'ss', [$type, $status], 'result'
            );
        } elseif ($type) {
            $r = myFunc::myQuery(
                "SELECT * FROM hcos_requests WHERE type=? ORDER BY submitted_at DESC",
                's', [$type], 'result'
            );
        } elseif ($status) {
            $r = myFunc::myQuery(
                "SELECT * FROM hcos_requests WHERE status=? ORDER BY submitted_at DESC",
                's', [$status], 'result'
            );
        } else {
            $r = myFunc::myQuery(
                "SELECT * FROM hcos_requests ORDER BY submitted_at DESC", '', [], 'result'
            );
        }
        if (!$r || $r->num_rows === 0) return false;
        $rows = [];
        while ($row = $r->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    // Admin: update status
    public function updateStatus($code, $status, $notes, $handler) {
        return myFunc::myQuery(
            "UPDATE hcos_requests SET status=?, admin_notes=?, handled_by=? WHERE request_code=?",
            'ssss', [$status, $notes, $handler, $code], 'action'
        );
    }

    // Stats for dashboard
    public function stats() {
        include(__ROOT__ . 'core/db.php');
        $out = [];
        $types = ['travel','letter','loan','room'];
        foreach ($types as $t) {
            $res = $conn->query("SELECT COUNT(*) c FROM hcos_requests WHERE type='$t'");
            $out[$t] = $res ? $res->fetch_assoc()['c'] : 0;
        }
        $res = $conn->query("SELECT COUNT(*) c FROM hcos_requests WHERE status='pending'");
        $out['pending'] = $res ? $res->fetch_assoc()['c'] : 0;
        $conn->close();
        return $out;
    }
}
