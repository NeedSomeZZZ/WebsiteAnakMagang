<?php
/**
 * partials/topnav-mobile.php
 * TopNavBar khusus tampilan mobile (md:hidden) untuk halaman intern.
 *
 * Variabel yang bisa di-set SEBELUM include ini:
 *   $mobile_title (string) -> teks brand yang ditampilkan (default: 'Kedayweb')
 */
if (!isset($mobile_title)) {
    $mobile_title = 'Kedayweb';
}
?>
<header class="md:hidden flex justify-between items-center px-md w-full h-16 bg-surface-container-lowest border-b border-outline-variant shadow-sm fixed top-0 z-50">
    <h1 class="font-headline-md text-headline-md font-bold text-primary font-geist"><?php echo htmlspecialchars($mobile_title); ?></h1>
</header>
