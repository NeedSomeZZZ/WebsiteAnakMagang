<?php
// ============================================================
// InternSpace - Attendance API (Clock In saja + Foto Geotag)
// Pola sama seperti projects.php: ?action=... , JSON in/out, mysqli
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// --------------------------------------------------------------
// 1. AUTH CHECK (tanpa redirect, karena ini endpoint JSON)
// --------------------------------------------------------------
if (empty($_SESSION['user_logged_in'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Belum login']);
    exit;
}
$username = $_SESSION['username'] ?? $_SESSION['user_id'] ?? null;
if (!$username) {
    http_response_code(401);
    echo json_encode(['error' => 'Sesi tidak valid']);
    exit;
}

// --------------------------------------------------------------
// 2. KONEKSI DATABASE (samain kayak Login/koneksi.php)
// --------------------------------------------------------------
$host     = "localhost";
$user     = "root";
$password = "";
$database = "db_internspace";

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    http_response_code(500);
    echo json_encode(['error' => 'Koneksi database gagal: ' . mysqli_connect_error()]);
    exit;
}

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$today  = date('Y-m-d');

// --------------------------------------------------------------
// 3. GET STATUS ABSEN HARI INI (?action=today)
// --------------------------------------------------------------
if ($action === 'today' && $method === 'GET') {
    $sql = "SELECT * FROM attendance WHERE username = ? AND date = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $today);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        echo json_encode(['exists' => false]);
        exit;
    }

    echo json_encode([
        'exists'      => true,
        'status'      => $row['status'],
        'clock_in'    => $row['clock_in'],
        'photo_in'    => $row['photo_in'] ? $row['photo_in'] : null,
        'location_in' => $row['location_in'],
        'lat_in'      => isset($row['lat_in']) ? (float) $row['lat_in'] : null,
        'lng_in'      => isset($row['lng_in']) ? (float) $row['lng_in'] : null,
    ]);
    exit;
}

// --------------------------------------------------------------
// 4. SIMPAN CLOCK IN (?action=save, POST)
// --------------------------------------------------------------
if ($action === 'save' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $time     = trim($input['time'] ?? '');     // 'HH:MM'
    $status   = $input['status'] ?? 'present';
    $location = trim($input['location'] ?? '');
    $lat      = isset($input['lat']) ? (float) $input['lat'] : null;
    $lng      = isset($input['lng']) ? (float) $input['lng'] : null;
    $photoData= $input['photo'] ?? '';          // data:image/jpeg;base64,....

    if (empty($time)) {
        http_response_code(400);
        echo json_encode(['error' => 'Data tidak lengkap (time)']);
        exit;
    }
    if (empty($photoData)) {
        http_response_code(400);
        echo json_encode(['error' => 'Foto wajib disertakan']);
        exit;
    }

    // -------- Simpan foto base64 ke file --------
    if (!preg_match('/^data:image\/(\w+);base64,/', $photoData, $m)) {
        http_response_code(400);
        echo json_encode(['error' => 'Format foto tidak valid']);
        exit;
    }
    $ext       = $m[1] === 'jpeg' ? 'jpg' : $m[1];
    $base64Raw = substr($photoData, strpos($photoData, ',') + 1);
    $binary    = base64_decode($base64Raw);
    if ($binary === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Gagal decode foto']);
        exit;
    }

    $uploadDir = __DIR__ . '/uploads/attendance/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $safeUsername = preg_replace('/[^A-Za-z0-9_\-]/', '_', $username);
    $fileName     = $safeUsername . '_' . $today . '_in_' . time() . '.' . $ext;
    $filePath     = $uploadDir . $fileName;
    $relativePath = 'uploads/attendance/' . $fileName;

    if (file_put_contents($filePath, $binary) === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Gagal menyimpan file foto']);
        exit;
    }

    // -------- Cek record hari ini sudah ada atau belum --------
    $sqlCheck = "SELECT id FROM attendance WHERE username = ? AND date = ? LIMIT 1";
    $stmtCheck = mysqli_prepare($conn, $sqlCheck);
    mysqli_stmt_bind_param($stmtCheck, "ss", $username, $today);
    mysqli_stmt_execute($stmtCheck);
    $existing = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtCheck));

    if ($existing) {
        $sql = "UPDATE attendance SET status=?, clock_in=?, photo_in=?, location_in=?, lat_in=?, lng_in=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssddi", $status, $time, $relativePath, $location, $lat, $lng, $existing['id']);
    } else {
        $sql = "INSERT INTO attendance (username, date, status, clock_in, photo_in, location_in, lat_in, lng_in) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssdd", $username, $today, $status, $time, $relativePath, $location, $lat, $lng);
    }

    if (!mysqli_stmt_execute($stmt)) {
        http_response_code(500);
        echo json_encode(['error' => 'Gagal menyimpan ke database: ' . mysqli_error($conn)]);
        exit;
    }

    echo json_encode([
        'message'  => 'Clock In berhasil disimpan',
        'photo'    => $relativePath,
        'time'     => $time,
        'location' => $location,
        'lat'      => $lat,
        'lng'      => $lng,
    ]);
    exit;
}

// --------------------------------------------------------------
// 5. RIWAYAT KEHADIRAN UNTUK KALENDER (?action=history, GET)
//    Dipakai oleh attendance.php (kalender + tabel riwayat milik intern sendiri)
// --------------------------------------------------------------
if ($action === 'history' && $method === 'GET') {
    $sql = "SELECT date, status, clock_in, clock_out, reason, location_in, lat_in, lng_in
            FROM attendance WHERE username = ? ORDER BY date DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rows = [];
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = [
            'date'        => $r['date'],
            'status'      => $r['status'],
            'clock_in'    => $r['clock_in'],
            'clock_out'   => $r['clock_out'],
            'reason'      => $r['reason'],
            'location_in' => $r['location_in'],
            'lat_in'      => $r['lat_in'] !== null ? (float) $r['lat_in'] : null,
            'lng_in'      => $r['lng_in'] !== null ? (float) $r['lng_in'] : null,
        ];
    }
    echo json_encode($rows);
    exit;
}

// --------------------------------------------------------------
// 6. CATAT / EDIT KEHADIRAN MANUAL DARI KALENDER (?action=upsert, POST)
// --------------------------------------------------------------
if ($action === 'upsert' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $date     = trim($input['date'] ?? '');
    $status   = $input['status'] ?? '';
    $clockIn  = trim($input['clockIn'] ?? '');
    $clockOut = trim($input['clockOut'] ?? '');
    $reason   = trim($input['reason'] ?? '');

    if (!$date || !in_array($status, ['present', 'late', 'absent'], true)) {
        http_response_code(400);
        echo json_encode(['error' => 'Data tidak lengkap (date/status)']);
        exit;
    }
    if ($status === 'absent') { $clockIn = ''; $clockOut = ''; }

    $sqlCheck = "SELECT id FROM attendance WHERE username = ? AND date = ? LIMIT 1";
    $stmtCheck = mysqli_prepare($conn, $sqlCheck);
    mysqli_stmt_bind_param($stmtCheck, "ss", $username, $date);
    mysqli_stmt_execute($stmtCheck);
    $existing = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtCheck));

    if ($existing) {
        $sql = "UPDATE attendance SET status=?, clock_in=?, clock_out=?, reason=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $status, $clockIn, $clockOut, $reason, $existing['id']);
    } else {
        $sql = "INSERT INTO attendance (username, date, status, clock_in, clock_out, reason) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $username, $date, $status, $clockIn, $clockOut, $reason);
    }

    if (!mysqli_stmt_execute($stmt)) {
        http_response_code(500);
        echo json_encode(['error' => 'Gagal menyimpan ke database: ' . mysqli_error($conn)]);
        exit;
    }

    echo json_encode(['message' => 'Kehadiran berhasil disimpan']);
    exit;
}

// --------------------------------------------------------------
// 7. HAPUS CATATAN KEHADIRAN (?action=delete, POST)
// --------------------------------------------------------------
if ($action === 'delete' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $date  = trim($input['date'] ?? '');

    if (!$date) {
        http_response_code(400);
        echo json_encode(['error' => 'Tanggal wajib diisi']);
        exit;
    }

    $sql = "DELETE FROM attendance WHERE username = ? AND date = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $date);

    if (!mysqli_stmt_execute($stmt)) {
        http_response_code(500);
        echo json_encode(['error' => 'Gagal menghapus data: ' . mysqli_error($conn)]);
        exit;
    }

    echo json_encode(['message' => 'Catatan berhasil dihapus']);
    exit;
}

// --------------------------------------------------------------
// Fallback
// --------------------------------------------------------------
http_response_code(404);
echo json_encode(['error' => 'Action tidak ditemukan']);