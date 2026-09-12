<?php
/**
 * partials/sidebar-intern.php
 * Left sidebar (SideNavBar) yang dipakai bersama oleh semua halaman intern:
 * dashboard.php, projects.php, attendance.php, tasks.php, applications.php
 *
 * Variabel yang bisa di-set SEBELUM include ini:
 *   $active           (string)  -> 'dashboard' | 'projects' | 'attendance' | 'tasks' | 'applications'
 *   $show_admin_link  (bool)    -> tampilkan link "Admin Dashboard" (default: false)
 */
if (!isset($active)) {
    $active = '';
}
if (!isset($show_admin_link)) {
    $show_admin_link = false;
}

$intern_nav_items = [
    'dashboard'    => ['label' => 'Dashboard',    'icon' => 'dashboard',       'href' => 'dashboard.php',    'i18n' => 'nav_dashboard'],
    'projects'     => ['label' => 'Projects',     'icon' => 'folder_open',     'href' => 'projects.php',     'i18n' => 'nav_projects'],
    'attendance'   => ['label' => 'Attendance',   'icon' => 'event_available', 'href' => 'attendance.php',   'i18n' => 'nav_attendance'],
    'tasks'        => ['label' => 'Tasks',        'icon' => 'view_kanban',     'href' => 'tasks.php',        'i18n' => 'nav_tasks'],
    'applications' => ['label' => 'Applications', 'icon' => 'description',     'href' => 'applications.php', 'i18n' => 'nav_applications'],
];
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
        <?php if ($show_admin_link): ?>
            <a class="flex items-center gap-md px-md py-sm bg-primary-container text-on-primary-container rounded-lg font-label-md text-label-md active:scale-[0.98] transition-transform" href="admin-dashboard.php">
                <span class="material-symbols-outlined">admin_panel_settings</span>
                <span data-i18n="nav_admin_dashboard">Admin Dashboard</span>
            </a>
        <?php endif; ?>
    </nav>
</aside>
