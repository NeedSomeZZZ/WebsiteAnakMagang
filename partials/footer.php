<?php
/**
 * partials/footer.php
 * Footer reusable partial untuk portal dashboard dan halaman internal Kedayweb.
 */
?>
<footer class="w-full border-t border-outline-variant bg-surface-container-lowest py-4 px-6 mt-auto">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-on-surface-variant">
        <div class="flex items-center gap-2 font-medium">
            <span class="font-bold text-primary">Kedayweb InternSpace</span>
            <span>&copy; <?php echo date('Y'); ?> All rights reserved.</span>
        </div>
        <div class="flex items-center gap-4 text-xs">
            <span class="hover:text-primary transition-colors cursor-pointer font-semibold">Dokumentasi PKL</span>
            <span>&bull;</span>
            <span class="hover:text-primary transition-colors cursor-pointer font-semibold">Material Design 3</span>
            <span>&bull;</span>
            <span class="hover:text-primary transition-colors cursor-pointer font-semibold">Tim Dev Magang</span>
        </div>
    </div>
</footer>
