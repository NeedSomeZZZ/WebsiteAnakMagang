<?php
/**
 * partials/sidebar-intern.php
 * Left sidebar (SideNavBar) yang dipakai bersama oleh semua halaman intern:
 * dashboard.php, projects.php, attendance.php, tasks.php, applications.php, article.php
 */

require_once __DIR__ . '/../session.php';

if (!isset($active)) {
    $active = '';
}
if (!isset($show_admin_link)) {
    $show_admin_link = false;
}

$script_path = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? $_SERVER['SCRIPT_NAME'] ?? '');
$is_in_admin_dir = (basename(dirname($script_path)) === 'admin');
$root_prefix = $is_in_admin_dir ? '../' : '';
$admin_prefix = $is_in_admin_dir ? '' : 'admin/';

$intern_nav_items = [
    'dashboard'    => ['label' => 'Dashboard',           'icon' => 'dashboard',       'href' => $root_prefix . 'dashboard.php',    'i18n' => 'nav_dashboard'],
    'projects'     => ['label' => 'Projects',            'icon' => 'folder_open',     'href' => $root_prefix . 'projects.php',     'i18n' => 'nav_projects'],
    'attendance'   => ['label' => 'Attendance',          'icon' => 'event_available', 'href' => $root_prefix . 'attendance.php',   'i18n' => 'nav_attendance'],
    'tasks'        => ['label' => 'Tasks',               'icon' => 'view_kanban',     'href' => $root_prefix . 'tasks.php',        'i18n' => 'nav_tasks'],
    'article'        => ['label' => 'Aktivitas & Artikel', 'icon' => 'newspaper',       'href' => $root_prefix . 'article.php',      'i18n' => 'nav_article'],
    'gallery'        => ['label' => 'Galeri Kegiatan',     'icon' => 'collections',     'href' => $root_prefix . 'galeryanakmagang.php', 'i18n' => 'nav_gallery'],
    'events_history' => ['label' => 'Histori Event',       'icon' => 'stars',           'href' => $root_prefix . 'event_history.php', 'i18n' => 'nav_events'],
    'about'          => ['label' => 'Tentang Kedayweb',   'icon' => 'info',            'href' => $root_prefix . 'about.php',         'i18n' => 'nav_about'],
];

if (current_user_role() === 'admin' || current_user_role() === 'superadmin') {
    $intern_nav_items['applications'] = ['label' => 'Applications', 'icon' => 'description', 'href' => $root_prefix . 'applications.php', 'i18n' => 'nav_applications'];
}
?>
<aside class="hidden md:flex flex-col h-full w-[16.5rem] bg-surface-container-lowest border-r border-outline-variant p-md fixed left-0 top-0 z-20">
    <div class="flex items-center gap-sm mb-xl px-sm">
        <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-on-primary">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">work</span>
        </div>
        <div>
            <h1 class="font-headline-md text-headline-md text-primary font-bold" data-i18n="brand_name">Kedayweb</h1>
            <p class="font-label-sm text-label-sm text-on-surface-variant" data-i18n="brand_subtitle">Portal PKL</p>
        </div>
    </div>
    <nav class="flex-1 space-y-sm">
        <?php foreach ($intern_nav_items as $key => $item): ?>
            <?php $is_active = ($active === $key); ?>
            <a class="flex items-center gap-md px-md py-sm rounded-lg font-label-md text-label-md transition-all duration-200 <?php echo $is_active ? 'bg-primary-container text-on-primary-container active:scale-[0.98] transition-transform' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-high'; ?>" href="<?php echo $item['href']; ?>">
                <span class="material-symbols-outlined"<?php echo $is_active ? ' style="font-variation-settings: \'FILL\' 1;"' : ''; ?>><?php echo $item['icon']; ?></span>
                <span data-i18n="<?php echo $item['i18n']; ?>"><?php echo $item['label']; ?></span>
            </a>
        <?php endforeach; ?>
        <?php if ($show_admin_link || current_user_role() === 'admin' || current_user_role() === 'superadmin'): ?>
            <a class="flex items-center gap-md px-md py-sm bg-primary-container text-on-primary-container rounded-lg font-label-md text-label-md active:scale-[0.98] transition-transform" href="<?php echo $admin_prefix; ?>admin-dashboard.php">
                <span class="material-symbols-outlined">admin_panel_settings</span>
                <span data-i18n="nav_admin_dashboard">Admin Dashboard</span>
            </a>
        <?php endif; ?>
    </nav>
    <div class="mt-auto border-t border-outline-variant pt-md">
        <div class="flex items-center gap-sm px-sm mb-sm">
            <span class="material-symbols-outlined text-primary">account_circle</span>
            <span class="font-label-sm text-on-surface truncate"><?php echo htmlspecialchars(current_user_name(), ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
        <a class="flex items-center gap-md px-md py-sm rounded-lg font-label-md text-label-md text-error hover:bg-error-container" href="<?php echo $root_prefix; ?>logout.php">
            <span class="material-symbols-outlined">logout</span>
            <span>Keluar</span>
        </a>
    </div>
</aside>
