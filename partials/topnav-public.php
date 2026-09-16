<?php
/**
 * partials/topnav-public.php
 * TopNavBar untuk halaman publik (tanpa sidebar): index.php, verification.php, article.php
 */
$script_path = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? $_SERVER['SCRIPT_NAME'] ?? '');
$is_in_admin_dir = (basename(dirname($script_path)) === 'admin');
$root_prefix = $is_in_admin_dir ? '../' : '';

if (!isset($nav_icon)) {
    $nav_icon = 'work';
}
if (!isset($nav_home_href)) {
    $nav_home_href = $root_prefix . 'index.php';
}
if (!isset($nav_cta_label)) {
    $nav_cta_label = 'Masuk Portal';
}
if (!isset($nav_cta_href)) {
    $nav_cta_href = $root_prefix . 'Login/login.php';
}
?>
<header class="bg-surface-container-lowest border-b border-outline-variant shadow-sm sticky top-0 z-50">
    <div class="flex justify-between items-center px-gutter w-full max-w-container-max mx-auto h-16">
        <div class="flex items-center gap-sm">
            <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-on-primary">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;"><?php echo htmlspecialchars($nav_icon); ?></span>
            </div>
            <a href="<?php echo htmlspecialchars($nav_home_href); ?>" class="font-headline-md text-headline-md font-bold text-primary" data-i18n="brand_name">Kedayweb</a>
        </div>
        <nav class="flex flex-wrap justify-center gap-md">
            <a href="<?php echo $root_prefix; ?>about.php" class="px-md py-sm rounded-lg font-label-md text-label-md hover:bg-primary hover:text-white transition-colors flex items-center gap-1"><span class="material-symbols-outlined text-sm">info</span>Tentang</a>
            <a href="<?php echo $root_prefix; ?>event_history.php" class="px-md py-sm rounded-lg font-label-md text-label-md hover:bg-primary hover:text-white transition-colors flex items-center gap-1"><span class="material-symbols-outlined text-sm">stars</span>Histori Event</a>
            <a href="<?php echo $root_prefix; ?>galeryanakmagang.php" class="px-md py-sm rounded-lg font-label-md text-label-md hover:bg-primary hover:text-white transition-colors">Galeri Magang</a>
            <a href="<?php echo $root_prefix; ?>article.php" class="px-md py-sm rounded-lg font-label-md text-label-md hover:bg-primary hover:text-white transition-colors">Artikel</a>
            <a href="<?php echo $root_prefix; ?>index.php#features" class="px-md py-sm rounded-lg font-label-md text-label-md hover:bg-primary hover:text-white transition-colors" data-i18n="nav_features">Fitur</a>
            <a href="<?php echo $root_prefix; ?>index.php#faq" class="px-md py-sm rounded-lg font-label-md text-label-md hover:bg-primary hover:text-white transition-colors" data-i18n="nav_faq">FAQ</a>
            <a href="<?php echo $root_prefix; ?>index.php#contact" class="px-md py-sm rounded-lg font-label-md text-label-md hover:bg-primary hover:text-white transition-colors" data-i18n="nav_contact">Kontak</a>
        </nav>
        <div class="flex items-center gap-2">
            <a href="<?php echo $root_prefix; ?>verification.php" class="border border-outline-variant text-primary hover:bg-primary hover:text-white px-3 py-2 rounded-lg font-label-md text-xs sm:text-sm transition-all flex items-center gap-1.5 font-bold shadow-xs hover:shadow-sm group" title="Verifikasi Keaslian Sertifikat">
                <span class="material-symbols-outlined text-[18px] text-primary group-hover:text-white" style="font-variation-settings: 'FILL' 1;">verified</span>
                <span>Verifikasi Sertifikat</span>
            </a>
            <a href="<?php echo htmlspecialchars($nav_cta_href); ?>" class="bg-primary-container text-on-primary px-3.5 py-2 rounded-lg font-label-md text-xs sm:text-sm hover:bg-primary transition-colors font-bold shadow-xs" data-i18n="btn_portal_login"><?php echo htmlspecialchars($nav_cta_label); ?></a>
        </div>
    </div>
</header>
