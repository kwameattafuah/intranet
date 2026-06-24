<?php
require_once('../layout/definition.php');
require_once(__ROOT__ . 'controllers/departments.controller.php');

$action = $_GET['action'] ?? '';
$dept   = new Departments();

if ($action === 'download') {
    $table = $_GET['table'] ?? '';
    $id    = intval($_GET['id'] ?? 0);
    $allowed_tables = ['dept_files','dept_forms','gacl_policies'];
    if (!in_array($table, $allowed_tables) || !$id) {
        http_response_code(400); exit('Invalid request');
    }
    $dept->logDownload($table, $id);
    $r = myFunc::myQuery("SELECT filename FROM $table WHERE id=?", 'i', [$id], 'fetch');
    if (!$r) { http_response_code(404); exit('Not found'); }
    $file = __ROOT__ . 'uploads/dept/' . basename($r['filename']);
    if (!file_exists($file)) { http_response_code(404); exit('File not found'); }
    $mime = mime_content_type($file) ?: 'application/octet-stream';
    header('Content-Type: ' . $mime);
    header('Content-Disposition: attachment; filename="' . basename($r['filename']) . '"');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Unknown action']);
