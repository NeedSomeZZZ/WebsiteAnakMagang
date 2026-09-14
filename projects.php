<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tambahkan fungsi ini agar partials/sidebar-intern.php tidak Fatal Error
if (!function_exists('current_user_name')) {
    function current_user_name() {
        return $_SESSION['user_name'] ?? $_SESSION['username'] ?? 'INT-2024-001';
    }
}

$userName = current_user_name();

// --------------------------------------------------------------------------
// 1. KONEKSI DATABASE (mysqli)
// --------------------------------------------------------------------------
$host     = "localhost";
$user     = "root";     
$password = "";         
$database = "db_internspace";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// --------------------------------------------------------------------------
// 2. BACKEND API HANDLER (?action=api)
// --------------------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'api') {
    header('Content-Type: application/json; charset=utf-8');
    
    $method = $_SERVER['REQUEST_METHOD'];
    $userId = $_SESSION['user_id'] ?? 1;

    // READ (GET)
    if ($method === 'GET') {
        $sql = "SELECT * FROM projects ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);
        
        $projects = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $projects[] = $row;
            }
        }
        echo json_encode($projects);
        exit;
    }

    // CREATE (POST)
    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $title = trim($input['title'] ?? '');
        $description = trim($input['description'] ?? '');
        $status = $input['status'] ?? 'pending';

        if (empty($title)) {
            http_response_code(400);
            echo json_encode(['error' => 'Judul project wajib diisi']);
            exit;
        }

        $sql = "INSERT INTO projects (user_id, title, description, status) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isss", $userId, $title, $description, $status);
        mysqli_stmt_execute($stmt);

        echo json_encode([
            'id' => mysqli_insert_id($conn), 
            'message' => 'Project berhasil ditambahkan'
        ]);
        exit;
    }

    // UPDATE (PUT)
    if ($method === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
        $title = trim($input['title'] ?? '');
        $description = trim($input['description'] ?? '');
        $status = $input['status'] ?? 'pending';

        if (!$id || empty($title)) {
            http_response_code(400);
            echo json_encode(['error' => 'Data tidak lengkap']);
            exit;
        }

        $sql = "UPDATE projects SET title = ?, description = ?, status = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $status, $id);
        mysqli_stmt_execute($stmt);

        echo json_encode(['message' => 'Project berhasil diperbarui']);
        exit;
    }

    // DELETE (DELETE)
    if ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID project tidak ditemukan']);
            exit;
        }

        $sql = "DELETE FROM projects WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        echo json_encode(['message' => 'Project berhasil dihapus']);
        exit;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Kedayweb - Projects</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=Material+Symbols+Outlined:FILL@0..1" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="shared-config.js"></script>
  <style>
    body { font-family: Inter, sans-serif; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 500; }
    .modal { background: rgba(11, 28, 48, 0.45); }
    .card { transition: all 0.2s ease; }
    .card:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(30, 58, 138, 0.08); }
  </style>
</head>
<body class="min-h-screen bg-canvas text-slate-900">
<?php $active = 'projects'; include 'partials/sidebar-intern.php'; ?>
  <main class="md:ml-[16.5rem]">
    <header class="flex h-16 items-center justify-between border-b border-line bg-white px-5 md:px-8">
      <h1 class="font-geist text-lg font-bold text-primary" data-i18n="nav_projects">Projects</h1>
      <span class="rounded-full border border-line px-3 py-1 text-sm font-semibold text-slate-700"><?php echo htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?></span>
      <a href="logout.php" class="text-red-600 hover:text-red-700" title="Keluar" aria-label="Keluar"><span class="material-symbols-outlined">logout</span></a>
    </header>
    <div id="app" class="mx-auto max-w-7xl p-5 md:p-8"></div>
  </main>
  <div id="modal" class="modal fixed inset-0 z-50 hidden items-center justify-center p-4"></div>
  <div id="toast" class="fixed bottom-5 right-5 z-[60] hidden rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white"></div>
  <script src="project-store.js"></script>
  <script src="projects-ui.js"></script>
  <script src="lang.js"></script>
  <script src="language-ui.js"></script>
  <script src="performance.js"></script>
</body>
</html>