<?php
/**
 * partials/sidebar-admin.php
 * Left sidebar (Sidebar) yang dipakai bersama oleh semua halaman admin:
 * admin-dashboard.php, admin-users.php, admin-attendance.php, admin-projects.php, admin-stats.php, admin-articles.php, superadmin.php
 */
if (!isset($active)) {
    $active = '';
}
if (!isset($sidebar_title)) {
    $sidebar_title = 'Kedayweb';
}
if (!isset($sidebar_subtitle)) {
    $sidebar_subtitle = 'Admin Panel';
}

$script_path = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? $_SERVER['SCRIPT_NAME'] ?? '');
$is_in_admin_dir = (basename(dirname($script_path)) === 'admin');
$admin_prefix = $is_in_admin_dir ? '' : 'admin/';
$root_prefix = $is_in_admin_dir ? '../' : '';

$admin_nav_items = [
    'dashboard'    => ['label' => 'Dashboard',        'icon' => 'dashboard',        'href' => $admin_prefix . 'admin-dashboard.php'],
    'tasks'        => ['label' => 'Kanban Board',     'icon' => 'view_kanban',      'href' => $root_prefix . 'tasks.php'],
    'applications' => ['label' => 'Pendaftaran',      'icon' => 'description',      'href' => $root_prefix . 'applications.php'],
    'users'        => ['label' => 'Users',            'icon' => 'people',           'href' => $admin_prefix . 'admin-users.php'],
    'attendance'   => ['label' => 'Kehadiran Intern', 'icon' => 'event_available',  'href' => $admin_prefix . 'admin-attendance.php'],
    'projects'     => ['label' => 'Projects',         'icon' => 'folder_open',      'href' => $root_prefix . 'projects.php'],
    'article'      => ['label' => 'Kelola Artikel',   'icon' => 'newspaper',        'href' => $admin_prefix . 'admin-articles.php'],
    'stats'        => ['label' => 'Statistics',       'icon' => 'insights',         'href' => $admin_prefix . 'admin-stats.php'],
];

// Superadmin Panel hanya muncul untuk role superadmin
if (is_superadmin()) {
    $admin_nav_items['superadmin'] = ['label' => 'Superadmin Panel', 'icon' => 'verified_user', 'href' => $admin_prefix . 'superadmin.php'];
}
?>
<aside class="hidden md:flex flex-col h-full w-[16.5rem] bg-surface-container-lowest border-r border-outline-variant p-md fixed left-0 top-0 z-20">
    <a href="<?php echo $root_prefix; ?>index.php" class="flex items-center gap-sm mb-xl px-sm rounded-lg transition-all duration-200 hover:bg-surface-container-high group" title="Kembali ke Beranda" aria-label="Kembali ke Beranda">
        <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-on-primary group-hover:opacity-80 transition-opacity">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">admin_panel_settings</span>
        </div>
        <div>
            <?php if (isset($sidebar_title_html)): ?>
                <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface font-bold group-hover:underline"><?php echo $sidebar_title_html; ?></h2>
            <?php else: ?>
                <h2 class="font-headline-lg text-on-surface font-bold group-hover:underline"><?php echo htmlspecialchars($sidebar_title); ?></h2>
            <?php endif; ?>
            <p class="font-label-sm text-label-sm text-on-surface-variant"><?php echo htmlspecialchars($sidebar_subtitle); ?></p>
        </div>
    </a>
    <nav class="flex-1 space-y-sm">
        <?php foreach ($admin_nav_items as $key => $item): ?>
            <?php $is_active = ($active === $key); ?>
            <a class="flex items-center gap-md px-md py-sm rounded-lg font-label-md <?php echo $is_active ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high'; ?>" href="<?php echo $item['href']; ?>">
                <span class="material-symbols-outlined"><?php echo $item['icon']; ?></span>
                <span><?php echo $item['label']; ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="mt-auto border-t border-outline-variant pt-md">
        <div class="flex items-center gap-sm px-sm mb-sm">
            <span class="material-symbols-outlined text-primary">account_circle</span>
            <span class="font-label-sm text-on-surface truncate"><?php echo htmlspecialchars(current_user_name(), ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
        <a class="flex items-center gap-md px-md py-sm rounded-lg font-label-md text-error hover:bg-error-container" href="<?php echo $root_prefix; ?>logout.php">
            <span class="material-symbols-outlined">logout</span>
            <span>Keluar</span>
        </a>
    </div>
</aside>
