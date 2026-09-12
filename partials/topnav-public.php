<?php
/**
 * partials/topnav-public.php
 * TopNavBar untuk halaman publik (tanpa sidebar): index.php, verification.php
 *
 * Variabel yang bisa di-set SEBELUM include ini:
 *   $nav_icon      (string) -> nama material icon (default: 'work')
 *   $nav_home_href (string) -> link brand/logo (default: 'index.php')
 *   $nav_cta_label (string) -> teks tombol kanan (default: 'Masuk Portal')
 *   $nav_cta_href  (string) -> link tombol kanan (default: 'login/login.php')
 */
if (!isset($nav_icon)) {
    $nav_icon = 'work';
}
if (!isset($nav_home_href)) {
    $nav_home_href = 'index.php';
}
if (!isset($nav_cta_label)) {
    $nav_cta_label = 'Masuk Portal';
}
if (!isset($nav_cta_href)) {
    $nav_cta_href = 'login/login.php';
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
        <div class="flex items-center gap-sm">
            <a href="<?php echo htmlspecialchars($nav_cta_href); ?>" class="bg-primary-container text-on-primary px-md py-sm rounded-lg font-label-md text-label-md hover:bg-primary transition-colors" data-i18n="btn_portal_login"><?php echo htmlspecialchars($nav_cta_label); ?></a>
        </div>
    </div>
</header>
