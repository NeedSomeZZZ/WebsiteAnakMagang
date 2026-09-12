<?php
/**
 * partials/sidebar-admin.php
 * Left sidebar (Sidebar) yang dipakai bersama oleh semua halaman admin:
 * admin-dashboard.php, admin-users.php, admin-attendance.php, admin-projects.php, admin-stats.php
 *
 * Variabel yang bisa di-set SEBELUM include ini:
 *   $active            (string) -> 'dashboard' | 'users' | 'attendance' | 'projects' | 'stats'
 *   $sidebar_title      (string) -> judul teks biasa (default: 'Kedayweb')
 *   $sidebar_title_html (string) -> judul dalam bentuk HTML mentah (override $sidebar_title, dipakai admin-dashboard.php)
 *   $sidebar_subtitle   (string) -> sub-judul (default: 'Admin Panel')
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

$admin_nav_items = [
    'dashboard'  => ['label' => 'Dashboard',        'icon' => 'dashboard',        'href' => 'admin-dashboard.php'],
    'users'      => ['label' => 'Users',            'icon' => 'people',           'href' => 'admin-users.php'],
    'attendance' => ['label' => 'Kehadiran Intern', 'icon' => 'event_available',  'href' => 'admin-attendance.php'],
    'projects'   => ['label' => 'Projects',         'icon' => 'folder_open',      'href' => 'admin-projects.php'],
    'stats'      => ['label' => 'Statistics',       'icon' => 'insights',         'href' => 'admin-stats.php'],
];
?>
<aside class="hidden md:flex flex-col h-full w-[16.5rem] bg-surface-container-lowest border-r border-outline-variant p-md fixed left-0 top-0 z-20">
    <div class="flex items-center gap-sm mb-xl px-sm">
        <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-on-primary">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">admin_panel_settings</span>
        </div>
        <div>
            <?php if (isset($sidebar_title_html)): ?>
                <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface font-bold"><?php echo $sidebar_title_html; ?></h2>
            <?php else: ?>
                <h2 class="font-headline-lg text-on-surface font-bold"><?php echo htmlspecialchars($sidebar_title); ?></h2>
            <?php endif; ?>
            <p class="font-label-sm text-label-sm text-on-surface-variant"><?php echo htmlspecialchars($sidebar_subtitle); ?></p>
        </div>
    </div>
    <nav class="flex-1 space-y-sm">
        <?php foreach ($admin_nav_items as $key => $item): ?>
            <?php $is_active = ($active === $key); ?>
            <a class="flex items-center gap-md px-md py-sm rounded-lg font-label-md <?php echo $is_active ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high'; ?>" href="<?php echo $item['href']; ?>">
                <span class="material-symbols-outlined"><?php echo $item['icon']; ?></span>
                <span><?php echo $item['label']; ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>
