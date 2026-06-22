<?php
require_once('../../layout/definition.php');
require_once(__ROOT__ . 'controllers/services.controller.php');

$svc = new Services();

if (isset($_POST['action'])) {
    switch ($_POST['action']) {

        case 'travel':
            [$ok, $val] = $svc->submit('travel', $_POST);
            echo $ok
                ? '<div class="alert alert-success"><i class="material-icons left tiny">check_circle</i><strong>Request submitted!</strong> Your reference code is <strong>' . $val . '</strong>. Keep this to track your request.</div>'
                : '<div class="alert alert-danger">' . $val . '</div>';
            break;

        case 'letter':
            [$ok, $val] = $svc->submit('letter', $_POST);
            echo $ok
                ? '<div class="alert alert-success"><i class="material-icons left tiny">check_circle</i><strong>Letter request submitted!</strong> Reference: <strong>' . $val . '</strong>. HCOS will contact you when ready.</div>'
                : '<div class="alert alert-danger">' . $val . '</div>';
            break;

        case 'loan':
            [$ok, $val] = $svc->submit('loan', $_POST);
            echo $ok
                ? '<div class="alert alert-success"><i class="material-icons left tiny">check_circle</i><strong>Loan request submitted!</strong> Reference: <strong>' . $val . '</strong>. HCOS will call you if cleared to collect forms.</div>'
                : '<div class="alert alert-danger">' . $val . '</div>';
            break;

        case 'track':
            $req = $svc->track(trim($_POST['code']));
            if (!$req) {
                echo '<div class="alert alert-warning">No request found with that code. Please check and try again.</div>';
            } else {
                $badges = ['pending'=>'badge-warning','approved'=>'badge-success','declined'=>'badge-danger','completed'=>'badge-primary'];
                $icons  = ['travel'=>'flight','letter'=>'description','loan'=>'account_balance','room'=>'meeting_room'];
                $labels = ['travel'=>'Travel Request','letter'=>'Letter Request','loan'=>'Loan Request','room'=>'Room Booking'];
                $b = $badges[$req['status']] ?? 'badge-info';
                $icon = $icons[$req['type']] ?? 'assignment';
                echo '
                <div style="border-left:4px solid var(--primary);padding:14px;background:#f8fafc;border-radius:6px">
                  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                    <span style="font-weight:700;font-size:14px"><i class="material-icons tiny" style="vertical-align:middle">'.$icon.'</i> '.htmlspecialchars($req['request_code']).'</span>
                    <span class="badge-pill '.$b.'">'.ucfirst($req['status']).'</span>
                  </div>
                  <p style="font-size:13px;margin:3px 0"><strong>Type:</strong> '.($labels[$req['type']] ?? $req['type']).'</p>
                  <p style="font-size:13px;margin:3px 0"><strong>Submitted by:</strong> '.htmlspecialchars($req['staff_name']).'</p>
                  <p style="font-size:13px;margin:3px 0"><strong>Date:</strong> '.date('d M Y, H:i', strtotime($req['submitted_at'])).'</p>
                  '.($req['admin_notes'] ? '<p style="font-size:13px;margin:6px 0;padding:8px;background:#fff;border-radius:4px"><strong>Note from HCOS:</strong> '.htmlspecialchars($req['admin_notes']).'</p>' : '').'
                </div>';
            }
            break;

        case 'admin_update':
            $ok = $svc->updateStatus($_POST['code'], $_POST['status'], $_POST['notes'], $_SESSION['aj.gaclintra']['user'] ?? 'Admin');
            echo $ok ? '<div class="alert alert-success">Request updated successfully.</div>'
                     : '<div class="alert alert-danger">Update failed. Please try again.</div>';
            break;

        case 'myrequests':
            $reqs = $svc->myRequests();
            if (!$reqs) {
                echo '<div class="alert alert-info">You have not submitted any requests yet.</div>';
            } else {
                $badges = ['pending'=>'badge-warning','approved'=>'badge-success','declined'=>'badge-danger','completed'=>'badge-primary'];
                $icons  = ['travel'=>'flight','letter'=>'description','loan'=>'account_balance','room'=>'meeting_room'];
                $labels = ['travel'=>'Travel','letter'=>'Letter','loan'=>'Loan','room'=>'Room'];
                echo '<table class="striped responsive-table" style="font-size:13px"><thead><tr><th>Code</th><th>Type</th><th>Date</th><th>Status</th></tr></thead><tbody>';
                foreach ($reqs as $r) {
                    $b = $badges[$r['status']] ?? 'badge-info';
                    echo '<tr>
                        <td><strong>'.htmlspecialchars($r['request_code']).'</strong></td>
                        <td><i class="material-icons tiny" style="vertical-align:middle">'.($icons[$r['type']]??'assignment').'</i> '.($labels[$r['type']]??$r['type']).'</td>
                        <td>'.date('d M Y', strtotime($r['submitted_at'])).'</td>
                        <td><span class="badge-pill '.$b.'">'.ucfirst($r['status']).'</span></td>
                    </tr>';
                }
                echo '</tbody></table>';
            }
            break;
    }
}
