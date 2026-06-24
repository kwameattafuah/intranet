<?php

class Departments {

    public function all() {
        $r = myFunc::myQuery("SELECT * FROM gacl_departments WHERE active=1 ORDER BY name", '', [], 'result');
        if (!$r || $r->num_rows === 0) return [];
        $rows = [];
        while ($row = $r->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    public function get($slug) {
        $r = myFunc::myQuery("SELECT * FROM gacl_departments WHERE slug=? AND active=1", 's', [$slug], 'fetch');
        return $r ?: false;
    }

    public function sections($dept_id) {
        $r = myFunc::myQuery("SELECT * FROM gacl_dept_sections WHERE dept_id=? ORDER BY sort_order", 'i', [$dept_id], 'result');
        if (!$r || $r->num_rows === 0) return [];
        $rows = [];
        while ($row = $r->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    public function files($dept_id, $category = null) {
        if ($category) {
            $r = myFunc::myQuery("SELECT * FROM dept_files WHERE dept_id=? AND category=? AND active=1 ORDER BY created_at DESC", 'is', [$dept_id, $category], 'result');
        } else {
            $r = myFunc::myQuery("SELECT * FROM dept_files WHERE dept_id=? AND active=1 ORDER BY category, title", 'i', [$dept_id], 'result');
        }
        if (!$r || $r->num_rows === 0) return [];
        $rows = [];
        while ($row = $r->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    public function forms($dept_id = null) {
        if ($dept_id) {
            $r = myFunc::myQuery("SELECT * FROM dept_forms WHERE (dept_id=? OR dept_id IS NULL) AND active=1 ORDER BY category, title", 'i', [$dept_id], 'result');
        } else {
            $r = myFunc::myQuery("SELECT * FROM dept_forms WHERE active=1 ORDER BY category, title", '', [], 'result');
        }
        if (!$r || $r->num_rows === 0) return [];
        $rows = [];
        while ($row = $r->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    public function contacts($dept_id) {
        $r = myFunc::myQuery("SELECT * FROM dept_contacts WHERE dept_id=? ORDER BY is_head DESC, sort_order, name", 'i', [$dept_id], 'result');
        if (!$r || $r->num_rows === 0) return [];
        $rows = [];
        while ($row = $r->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    public function announcements($dept_id = null) {
        if ($dept_id) {
            $r = myFunc::myQuery(
                "SELECT * FROM dept_announcements WHERE (dept_id=? OR dept_id IS NULL) AND active=1 AND (expires_at IS NULL OR expires_at >= CURDATE()) ORDER BY priority DESC, created_at DESC LIMIT 5",
                'i', [$dept_id], 'result'
            );
        } else {
            $r = myFunc::myQuery(
                "SELECT da.*, gd.name dept_name FROM dept_announcements da LEFT JOIN gacl_departments gd ON da.dept_id=gd.id WHERE da.active=1 AND (da.expires_at IS NULL OR da.expires_at >= CURDATE()) ORDER BY da.priority DESC, da.created_at DESC",
                '', [], 'result'
            );
        }
        if (!$r || $r->num_rows === 0) return [];
        $rows = [];
        while ($row = $r->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    public function policies($dept_id = null, $category = null) {
        if ($dept_id && $category) {
            $r = myFunc::myQuery("SELECT * FROM gacl_policies WHERE dept_id=? AND category=? ORDER BY title", 'is', [$dept_id, $category], 'result');
        } elseif ($dept_id) {
            $r = myFunc::myQuery("SELECT * FROM gacl_policies WHERE dept_id=? ORDER BY category, title", 'i', [$dept_id], 'result');
        } elseif ($category) {
            $r = myFunc::myQuery("SELECT * FROM gacl_policies WHERE category=? ORDER BY title", 's', [$category], 'result');
        } else {
            $r = myFunc::myQuery("SELECT * FROM gacl_policies ORDER BY category, title", '', [], 'result');
        }
        if (!$r || $r->num_rows === 0) return [];
        $rows = [];
        while ($row = $r->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    public function airports() {
        $r = myFunc::myQuery("SELECT * FROM gacl_airports ORDER BY name", '', [], 'result');
        if (!$r || $r->num_rows === 0) return [];
        $rows = [];
        while ($row = $r->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    public function airport($code) {
        $r = myFunc::myQuery("SELECT * FROM gacl_airports WHERE code=?", 's', [$code], 'fetch');
        return $r ?: false;
    }

    public function logDownload($table, $id) {
        myFunc::myQuery("UPDATE $table SET downloads=downloads+1 WHERE id=?", 'i', [$id], 'action');
    }
}
