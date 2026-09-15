<?php
require_once __DIR__ . '/../session.php';
require_login();

// Database connection attempt
$conn = null;
if (file_exists(__DIR__ . '/../Login/koneksi.php')) {
    include_once __DIR__ . '/../Login/koneksi.php';
}

$positions_file = __DIR__ . '/../uploads/positions.json';
if (!file_exists(dirname($positions_file))) {
    @mkdir(dirname($positions_file), 0777, true);
}

// Function to fetch positions for index.php
function get_index_positions($positions_file) {
    if (!file_exists($positions_file)) {
        $default_positions = [
            [
                'id' => 1,
                'title' => 'Magang Web Developer',
                'category' => 'Teknik',
                'icon' => 'code',
                'description' => 'Bergabunglah dengan tim frontend kami untuk membangun antarmuka web modern, cepat, dan interaktif. Bekerja sama langsung dengan engineer senior.',
                'location' => 'Remote / Hybrid'
            ],
            [
                'id' => 2,
                'title' => 'Magang UI/UX Designer',
                'category' => 'Desain',
                'icon' => 'design_services',
                'description' => 'Bantu rancang pengalaman produk terbaik. Buat riset pengguna, wireframe, dan prototipe desain aplikasi berstandar industri.',
                'location' => 'Banyuwangi / Remote'
            ]
        ];
        file_put_contents($positions_file, json_encode($default_positions, JSON_PRETTY_PRINT));
        return $default_positions;
    }
    $data = json_decode(file_get_contents($positions_file), true);
    return is_array($data) ? $data : [];
}

// Save positions
function save_index_positions($positions_file, $positions) {
    file_put_contents($positions_file, json_encode($positions, JSON_PRETTY_PRINT));
}

// Handle POST actions
$msg = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'cook_role') {
        $target_role = $_POST['role'] ?? 'admin';
        cook_role($target_role);
        $msg = "Cookie Role berhasil dimasak menjadi: " . strtoupper($target_role);
        $msg_type = "success";
    } elseif ($action === 'clear_role') {
        clear_cooked_role();
        $msg = "Cookie Role berhasil dihapus. Peran dikembalikan ke role akun asli.";
        $msg_type = "info";
    } elseif ($action === 'update_user_role') {
        $user_id = intval($_POST['user_id'] ?? 0);
        $new_role = trim($_POST['role'] ?? 'intern');
        if ($conn && $user_id > 0) {
            $stmt = mysqli_prepare($conn, "UPDATE users SET role=? WHERE id=?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "si", $new_role, $user_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $msg = "Role user ID #{$user_id} berhasil diubah menjadi {$new_role}.";
                $msg_type = "success";
            }
        }
    } elseif ($action === 'add_position') {
        $positions = get_index_positions($positions_file);
        $max_id = 0;
        foreach ($positions as $p) {
            if (($p['id'] ?? 0) > $max_id) $max_id = $p['id'];
        }
        $new_pos = [
            'id' => $max_id + 1,
            'title' => trim($_POST['title'] ?? ''),
            'category' => trim($_POST['category'] ?? 'Teknik'),
            'icon' => trim($_POST['icon'] ?? 'work'),
            'description' => trim($_POST['description'] ?? ''),
            'location' => trim($_POST['location'] ?? 'Remote / Hybrid')
        ];
        $positions[] = $new_pos;
        save_index_positions($positions_file, $positions);
        $msg = "Posisi baru berhasil ditambahkan ke index.php!";
        $msg_type = "success";
    } elseif ($action === 'delete_position') {
        $pos_id = intval($_POST['pos_id'] ?? 0);
        $positions = get_index_positions($positions_file);
        $positions = array_values(array_filter($positions, function($p) use ($pos_id) {
            return ($p['id'] ?? 0) != $pos_id;
        }));
        save_index_positions($positions_file, $positions);
        $msg = "Posisi berhasil dihapus dari index.php.";
        $msg_type = "danger";
    }
}

// Fetch Database Users
$users = [];
if ($conn) {
    $res = @mysqli_query($conn, "SELECT id, username, role FROM users ORDER BY id ASC");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $users[] = $row;
        }
    }
}

$positions = get_index_positions($positions_file);
$current_active_role = current_user_role();
$cooked_cookie = $_COOKIE['cooked_role'] ?? $_SESSION['cooked_role'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <title>Kedayweb Superadmin - Role & Position Control</title>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="../shared-config.js"></script>
    <link rel="stylesheet" href="../style.css"/>
    <style>
        .glass-card { background: rgba(255,255,255,0.85); backdrop-filter: blur(12px); }
    </style>
</head>
<body class="bg-background text-on-surface font-body-md flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <?php $active = 'superadmin'; include '../partials/sidebar-admin.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col md:ml-[16.5rem] h-screen overflow-y-auto">
        <!-- Top Header -->
        <header class="w-full h-16 bg-surface-container-lowest border-b border-outline-variant sticky top-0 flex justify-between items-center px-6 z-10">
            <h2 class="font-headline-lg flex items-center gap-2 text-primary font-bold">
                <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                <span>Superadmin Control Center</span>
            </h2>
            <div class="flex items-center gap-3">
                <!-- Cooked Role Indicator Badge -->
                <?php if (!empty($cooked_cookie)): ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-900 font-label-sm font-bold border border-amber-300 shadow-sm animate-pulse">
                        <span class="material-symbols-outlined text-sm">cookie</span>
                        <span>Cooked Role: <?php echo htmlspecialchars(strtoupper($cooked_cookie), ENT_QUOTES, 'UTF-8'); ?></span>
                    </span>
                <?php endif; ?>

                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">account_circle</span>
                    </div>
                    <span class="hidden sm:inline-block font-label-md"><?php echo htmlspecialchars(current_user_name(), ENT_QUOTES, 'UTF-8'); ?></span>
                    <a href="../logout.php" class="text-error hover:text-red-700 ml-1" title="Keluar"><span class="material-symbols-outlined">logout</span></a>
                </div>
            </div>
        </header>

        <div class="p-6 flex flex-col gap-6">

            <!-- Alert Notification -->
            <?php if (!empty($msg)): ?>
                <div class="flex items-center justify-between p-4 rounded-xl <?php echo $msg_type === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : ($msg_type === 'danger' ? 'bg-red-50 text-red-800 border border-red-200' : 'bg-blue-50 text-blue-800 border border-blue-200'); ?> shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined"><?php echo $msg_type === 'success' ? 'check_circle' : 'info'; ?></span>
                        <span class="font-label-md font-semibold"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-on-surface-variant hover:text-on-surface">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Section 1: Cookie & Role Switcher ("Cook Role Admin") -->
            <div class="glass-card rounded-2xl border border-outline-variant p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center">
                        <span class="material-symbols-outlined">cookie</span>
                    </div>
                    <div>
                        <h3 class="font-headline-md font-bold text-on-surface">Kontrol & Switcher Role Cookie ("Cook Admin")</h3>
                        <p class="text-sm text-on-surface-variant">Ubah role sesi/cookie secara instan untuk menguji tampilan aplikasi (index.php, dashboard, admin) tanpa perlu relogin.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-5">
                    <!-- Cook Admin -->
                    <form action="superadmin.php" method="POST">
                        <input type="hidden" name="action" value="cook_role"/>
                        <input type="hidden" name="role" value="admin"/>
                        <button type="submit" class="w-full p-4 rounded-xl bg-primary text-on-primary font-label-md hover:bg-primary-container hover:text-on-primary-container transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95">
                            <span class="material-symbols-outlined">shield</span>
                            <span>🍳 Cook Role Admin</span>
                        </button>
                    </form>

                    <!-- Cook Superadmin -->
                    <form action="superadmin.php" method="POST">
                        <input type="hidden" name="action" value="cook_role"/>
                        <input type="hidden" name="role" value="superadmin"/>
                        <button type="submit" class="w-full p-4 rounded-xl bg-indigo-600 text-white font-label-md hover:bg-indigo-700 transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95">
                            <span class="material-symbols-outlined">verified_user</span>
                            <span>👑 Cook Role Superadmin</span>
                        </button>
                    </form>

                    <!-- Cook Intern -->
                    <form action="superadmin.php" method="POST">
                        <input type="hidden" name="action" value="cook_role"/>
                        <input type="hidden" name="role" value="intern"/>
                        <button type="submit" class="w-full p-4 rounded-xl bg-emerald-600 text-white font-label-md hover:bg-emerald-700 transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95">
                            <span class="material-symbols-outlined">school</span>
                            <span>🎓 Cook Role Intern</span>
                        </button>
                    </form>

                    <!-- Reset Cookie -->
                    <form action="superadmin.php" method="POST">
                        <input type="hidden" name="action" value="clear_role"/>
                        <button type="submit" class="w-full p-4 rounded-xl bg-slate-200 text-slate-800 font-label-md hover:bg-slate-300 transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95">
                            <span class="material-symbols-outlined">restart_alt</span>
                            <span>🔄 Reset / Clear Cookie</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Section 2: Manage Positions on index.php -->
            <div class="glass-card rounded-2xl border border-outline-variant p-6 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="font-headline-md font-bold text-on-surface">Kontrol Posisi Magang di index.php</h3>
                        <p class="text-sm text-on-surface-variant">Tambah atau hapus posisi magang yang tampil pada landing page pendaftaran (`index.php`).</p>
                    </div>
                    <button onclick="document.getElementById('add-pos-modal').classList.remove('hidden')" class="px-4 py-2 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary-container transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined">add_circle</span>
                        <span>Tambah Posisi</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <?php foreach ($positions as $pos): ?>
                        <div class="p-4 rounded-xl border border-outline-variant bg-surface-container-lowest flex justify-between items-start">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary"><?php echo htmlspecialchars($pos['icon'] ?? 'work', ENT_QUOTES, 'UTF-8'); ?></span>
                                    <h4 class="font-headline-sm font-bold text-on-surface"><?php echo htmlspecialchars($pos['title'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-semibold"><?php echo htmlspecialchars($pos['category'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                <p class="text-xs text-on-surface-variant"><?php echo htmlspecialchars($pos['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <p class="text-xs text-primary font-semibold flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-xs">location_on</span>
                                    <span><?php echo htmlspecialchars($pos['location'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </p>
                            </div>

                            <form action="superadmin.php" method="POST" onsubmit="return confirm('Hapus posisi ini dari index.php?')">
                                <input type="hidden" name="action" value="delete_position"/>
                                <input type="hidden" name="pos_id" value="<?php echo (int) $pos['id']; ?>"/>
                                <button type="submit" class="text-error hover:bg-red-50 p-2 rounded-lg transition-colors" title="Hapus Posisi">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Section 3: Manage Database User Roles -->
            <div class="glass-card rounded-2xl border border-outline-variant p-6 shadow-sm">
                <h3 class="font-headline-md font-bold text-on-surface mb-1">Manajemen Role User Database</h3>
                <p class="text-sm text-on-surface-variant mb-4">Ubah role akun pengguna di database secara permanen.</p>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-outline-variant bg-surface-container-low text-on-surface-variant">
                                <th class="py-3 px-4 font-semibold">ID User</th>
                                <th class="py-3 px-4 font-semibold">Username / Email</th>
                                <th class="py-3 px-4 font-semibold">Role Sekarang</th>
                                <th class="py-3 px-4 font-semibold text-right">Ubah Role</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-6 text-on-surface-variant">Tidak ada data user di database.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $u): ?>
                                    <tr class="hover:bg-surface-container-low">
                                        <td class="py-3 px-4 font-bold">#<?php echo (int) $u['id']; ?></td>
                                        <td class="py-3 px-4 font-medium text-on-surface"><?php echo htmlspecialchars($u['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="py-3 px-4">
                                            <span class="px-2.5 py-1 text-xs rounded-full font-bold <?php echo $u['role'] === 'admin' || $u['role'] === 'superadmin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'; ?>">
                                                <?php echo strtoupper(htmlspecialchars($u['role'], ENT_QUOTES, 'UTF-8')); ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <form action="superadmin.php" method="POST" class="inline-flex items-center gap-2">
                                                <input type="hidden" name="action" value="update_user_role"/>
                                                <input type="hidden" name="user_id" value="<?php echo (int) $u['id']; ?>"/>
                                                <select name="role" class="px-3 py-1 text-xs border border-outline-variant rounded-lg bg-surface-container-lowest">
                                                    <option value="intern" <?php echo $u['role'] === 'intern' ? 'selected' : ''; ?>>INTERN</option>
                                                    <option value="admin" <?php echo $u['role'] === 'admin' ? 'selected' : ''; ?>>ADMIN</option>
                                                    <option value="superadmin" <?php echo $u['role'] === 'superadmin' ? 'selected' : ''; ?>>SUPERADMIN</option>
                                                </select>
                                                <button type="submit" class="px-3 py-1 text-xs bg-primary text-on-primary rounded-lg font-bold hover:bg-primary-container">Simpan</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- Modal Tambah Posisi -->
    <div id="add-pos-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 hidden backdrop-blur-sm">
        <div class="bg-surface-container-lowest rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-outline-variant">
            <div class="flex justify-between items-center pb-3 border-b border-outline-variant mb-4">
                <h3 class="font-headline-md font-bold text-primary">Tambah Posisi Magang (index.php)</h3>
                <button onclick="document.getElementById('add-pos-modal').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="superadmin.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="add_position"/>
                <div>
                    <label class="block text-xs font-semibold mb-1">Nama Posisi Magang</label>
                    <input type="text" name="title" required placeholder="Contoh: Magang Data Analyst" class="w-full px-3 py-2 border border-outline-variant rounded-xl text-sm"/>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold mb-1">Kategori</label>
                        <select name="category" class="w-full px-3 py-2 border border-outline-variant rounded-xl text-sm">
                            <option value="Teknik">Teknik</option>
                            <option value="Desain">Desain</option>
                            <option value="Pemasaran">Pemasaran</option>
                            <option value="Data">Data</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1">Icon Material Symbol</label>
                        <input type="text" name="icon" value="work" placeholder="code, design_services, analytics" class="w-full px-3 py-2 border border-outline-variant rounded-xl text-sm"/>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1">Lokasi</label>
                    <input type="text" name="location" value="Remote / Hybrid" class="w-full px-3 py-2 border border-outline-variant rounded-xl text-sm"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="3" required placeholder="Deskripsi tugas dan tanggung jawab..." class="w-full px-3 py-2 border border-outline-variant rounded-xl text-sm"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('add-pos-modal').classList.add('hidden')" class="px-4 py-2 border rounded-xl text-sm">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-primary text-on-primary rounded-xl text-sm font-bold">Simpan Posisi</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
