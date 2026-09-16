<?php
/**
 * certificate-api.php
 * API endpoint untuk verifikasi keaslian sertifikat magang
 * Dipanggil dari verification.php via fetch()
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

require_once __DIR__ . '/Login/koneksi.php';

$action = $_GET['action'] ?? $_POST['action'] ?? 'verify';

// ── VERIFY sertifikat (publik) ──────────────────────────────────────────────
if ($action === 'verify') {
    $cert_id = trim($_GET['id'] ?? '');
    if (!$cert_id) {
        echo json_encode(['success' => false, 'message' => 'ID sertifikat tidak boleh kosong.']);
        exit;
    }

    $stmt = $conn->prepare(
        "SELECT c.*, 
                DATE_FORMAT(c.start_date, '%d %M %Y') AS start_fmt,
                DATE_FORMAT(c.end_date,   '%d %M %Y') AS end_fmt,
                DATE_FORMAT(c.issue_date, '%d %M %Y') AS issue_fmt
         FROM certificates c
         WHERE c.certificate_id = ? LIMIT 1"
    );
    $stmt->bind_param('s', $cert_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Sertifikat tidak ditemukan. Pastikan ID sudah benar.']);
        exit;
    }

    $cert = $result->fetch_assoc();

    if ($cert['status'] === 'revoked') {
        echo json_encode([
            'success' => false,
            'revoked' => true,
            'message' => 'Sertifikat ini telah dicabut / tidak berlaku.',
            'certificate_id' => $cert['certificate_id'],
        ]);
        exit;
    }

    // Hitung rata-rata skor
    $avg_score = round(($cert['score_technical'] + $cert['score_discipline'] + $cert['score_attitude']) / 3);

    echo json_encode([
        'success'          => true,
        'certificate_id'   => $cert['certificate_id'],
        'intern_name'      => $cert['intern_name'],
        'intern_position'  => $cert['intern_position'],
        'university'       => $cert['university'],
        'major'            => $cert['major'],
        'start_date'       => $cert['start_fmt'],
        'end_date'         => $cert['end_fmt'],
        'issue_date'       => $cert['issue_fmt'],
        'score_technical'  => (int)$cert['score_technical'],
        'score_discipline' => (int)$cert['score_discipline'],
        'score_attitude'   => (int)$cert['score_attitude'],
        'avg_score'        => $avg_score,
        'final_grade'      => $cert['final_grade'],
        'supervisor_name'  => $cert['supervisor_name'],
        'status'           => $cert['status'],
        'notes'            => $cert['notes'],
    ]);
    exit;
}

// ── LIST semua sertifikat (admin only) ─────────────────────────────────────
if ($action === 'list') {
    session_start();
    if (empty($_SESSION['user_logged_in']) || !in_array($_SESSION['role'] ?? '', ['admin', 'superadmin'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
        exit;
    }

    $search = '%' . trim($_GET['search'] ?? '') . '%';
    $stmt = $conn->prepare(
        "SELECT id, certificate_id, intern_name, intern_position, university,
                final_grade, status,
                DATE_FORMAT(issue_date, '%d %b %Y') AS issue_fmt
         FROM certificates
         WHERE certificate_id LIKE ? OR intern_name LIKE ? OR intern_position LIKE ?
         ORDER BY created_at DESC"
    );
    $stmt->bind_param('sss', $search, $search, $search);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['success' => true, 'data' => $rows]);
    exit;
}

// ── CREATE sertifikat (admin only) ─────────────────────────────────────────
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (empty($_SESSION['user_logged_in']) || !in_array($_SESSION['role'] ?? '', ['admin', 'superadmin'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
        exit;
    }

    $fields = ['certificate_id','intern_name','intern_position','university','major',
               'start_date','end_date','issue_date','score_technical','score_discipline',
               'score_attitude','final_grade','supervisor_name','status','user_id','notes'];
    $data = [];
    foreach ($fields as $f) {
        $data[$f] = $_POST[$f] ?? null;
    }

    // Validasi wajib
    if (!$data['certificate_id'] || !$data['intern_name'] || !$data['intern_position'] ||
        !$data['start_date']     || !$data['end_date']     || !$data['issue_date']) {
        echo json_encode(['success' => false, 'message' => 'Field wajib belum diisi.']);
        exit;
    }

    $stmt = $conn->prepare(
        "INSERT INTO certificates
         (certificate_id, user_id, intern_name, intern_position, university, major,
          start_date, end_date, issue_date, score_technical, score_discipline,
          score_attitude, final_grade, supervisor_name, status, notes)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
    );
    $uid = $data['user_id'] ?: null;
    $stmt->bind_param(
        'sissssssssiiisss',
        $data['certificate_id'], $uid, $data['intern_name'], $data['intern_position'],
        $data['university'], $data['major'], $data['start_date'], $data['end_date'],
        $data['issue_date'], $data['score_technical'], $data['score_discipline'],
        $data['score_attitude'], $data['final_grade'], $data['supervisor_name'],
        $data['status'], $data['notes']
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Sertifikat berhasil ditambahkan.', 'insert_id' => $stmt->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan: ' . $conn->error]);
    }
    exit;
}

// ── UPDATE sertifikat (admin only) ─────────────────────────────────────────
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (empty($_SESSION['user_logged_in']) || !in_array($_SESSION['role'] ?? '', ['admin', 'superadmin'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
        exit;
    }

    $id = (int)($_POST['id'] ?? 0);
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID tidak valid.']);
        exit;
    }

    $stmt = $conn->prepare(
        "UPDATE certificates SET
            intern_name=?, intern_position=?, university=?, major=?,
            start_date=?, end_date=?, issue_date=?,
            score_technical=?, score_discipline=?, score_attitude=?,
            final_grade=?, supervisor_name=?, status=?, notes=?
         WHERE id=?"
    );
    $stmt->bind_param(
        'sssssssiiisssi',
        $_POST['intern_name'], $_POST['intern_position'], $_POST['university'], $_POST['major'],
        $_POST['start_date'], $_POST['end_date'], $_POST['issue_date'],
        $_POST['score_technical'], $_POST['score_discipline'], $_POST['score_attitude'],
        $_POST['final_grade'], $_POST['supervisor_name'], $_POST['status'], $_POST['notes'],
        $id
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Sertifikat berhasil diperbarui.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal update: ' . $conn->error]);
    }
    exit;
}

// ── DELETE sertifikat (admin only) ─────────────────────────────────────────
if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (empty($_SESSION['user_logged_in']) || !in_array($_SESSION['role'] ?? '', ['admin', 'superadmin'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
        exit;
    }

    $id = (int)($_POST['id'] ?? 0);
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID tidak valid.']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM certificates WHERE id=?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Sertifikat berhasil dihapus.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal hapus: ' . $conn->error]);
    }
    exit;
}

// ── GET single (admin edit form) ────────────────────────────────────────────
if ($action === 'get') {
    session_start();
    if (empty($_SESSION['user_logged_in']) || !in_array($_SESSION['role'] ?? '', ['admin', 'superadmin'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
        exit;
    }
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $conn->prepare("SELECT * FROM certificates WHERE id=? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        echo json_encode(['success' => true, 'data' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Tidak ditemukan.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Action tidak dikenal.']);
