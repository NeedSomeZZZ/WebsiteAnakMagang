<?php require_once __DIR__ . '/session.php'; require_login(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Kedayweb Dashboard</title>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800;900&amp;family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
   <script src="shared-config.js"></script>
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-background text-on-surface font-body-md flex h-screen overflow-hidden">
<!-- SideNavBar (Desktop) -->
 <?php $active = 'dashboard'; include 'partials/sidebar-intern.php'; ?>

<!-- Main Content Canvas -->
<main class="flex-1 flex flex-col md:ml-[16.5rem] h-screen overflow-y-auto relative">
    <!-- TopNavBar -->
    <header class="w-full h-16 bg-surface-container-lowest border-b border-outline-variant sticky top-0 z-10">
        <div class="flex justify-between items-center px-gutter w-full max-w-container-max mx-auto h-full">
            <div class="flex-1 flex items-center">
                <div class="relative w-full max-w-md hidden sm:block">
                    <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline">search</span>
                    <input class="w-full pl-xl pr-md py-sm rounded-lg bg-surface-bright border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary-fixed focus:outline-none font-body-sm text-body-sm transition-all" placeholder="Search tasks, projects..." type="text"/>
                </div>
            </div>
            <!-- Trailing Actions -->
            <div class="flex items-center gap-sm">
                <button class="p-sm text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors relative cursor-pointer active:scale-95">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full"></span>
                </button>
                <div class="h-8 w-px bg-outline-variant mx-xs"></div>
                <div class="flex items-center gap-sm p-xs pr-md rounded-full border border-outline-variant">
                    <div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container overflow-hidden">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">account_circle</span>
                    </div>
                    <span class="font-label-md text-label-md hidden sm:inline-block"><?php echo htmlspecialchars(current_user_name(), ENT_QUOTES, 'UTF-8'); ?></span>
                    <a href="logout.php" class="text-error hover:text-red-700" title="Keluar" aria-label="Keluar">
                        <span class="material-symbols-outlined">logout</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Dashboard Content -->
    <div class="w-full max-w-container-max mx-auto p-md md:p-gutter flex flex-col gap-xl">
        <!-- Page Header -->
        <div>
            <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface font-bold">Welcome back, <span id="intern-name"><?php echo htmlspecialchars(current_user_name(), ENT_QUOTES, 'UTF-8'); ?></span></h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Here's what's happening with your internship today.</p>
        </div>
        <!-- Top Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md">
            <!-- Stat: Attendance -->
            <a href="attendance.php" class="glass-card bg-surface-container-lowest border border-outline-variant rounded-2xl p-md flex flex-col justify-between h-32 relative overflow-hidden group block">
                <div class="flex justify-between items-start">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Attendance</span>
                    <div class="p-xs rounded-full bg-surface-container-high text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                        <span class="material-symbols-outlined text-[20px]">event_available</span>
                    </div>
                </div>
                <div>
                    <div class="font-headline-xl text-headline-xl text-on-surface">95%</div>
                    <div class="flex items-center gap-xs mt-1">
                        <span class="material-symbols-outlined text-[14px] text-green-600">trending_up</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">+2% from last month</span>
                    </div>
                </div>
            </a>
            <!-- Stat: Tasks Done -->
            <a href="tasks.php" class="glass-card bg-surface-container-lowest border border-outline-variant rounded-2xl p-md flex flex-col justify-between h-32 block">
                <div class="flex justify-between items-start">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider" data-i18n="stat_tasks_done">Tasks Done</span>
                    <div class="p-xs rounded-full bg-surface-container-high text-primary">
                        <span class="material-symbols-outlined text-[20px]">task_alt</span>
                    </div>
                </div>
                <div>
                    <div class="font-headline-xl text-headline-xl text-on-surface" id="stat-tasks-done-val">24</div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-1" data-i18n="stat_this_sprint">This sprint</p>
                </div>
            </a>
            <!-- Stat: Active Projects -->
            <a href="projects.php" class="glass-card bg-surface-container-lowest border border-outline-variant rounded-2xl p-md flex flex-col justify-between h-32 block">
                <div class="flex justify-between items-start">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider" data-i18n="stat_active_proj">Active Projects</span>
                    <div class="p-xs rounded-full bg-surface-container-high text-primary">
                        <span class="material-symbols-outlined text-[20px]">rocket_launch</span>
                    </div>
                </div>
                <div>
                    <div class="font-headline-xl text-headline-xl text-on-surface" id="stat-active-proj-val">3</div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-1" data-i18n="stat_cross_func">Cross-functional</p>
                </div>
            </a>
            <!-- Stat: Pending Tasks -->
            <a href="tasks.php" class="glass-card bg-surface-container-lowest border border-outline-variant rounded-2xl p-md flex flex-col justify-between h-32 block" id="stat-tasks-pending-card">
                <div class="flex justify-between items-start">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Pending Tasks</span>
                    <div class="p-xs rounded-full bg-surface-container-high text-primary">
                        <span class="material-symbols-outlined text-[20px]">hourglass_bottom</span>
                    </div>
                </div>
                <div>
                    <div class="font-headline-xl text-headline-xl text-on-surface" id="stat-tasks-pending-val">0</div>
                </div>
            </a>
            <!-- Stat: Total Projects -->
            <a href="projects.php" class="glass-card bg-surface-container-lowest border border-outline-variant rounded-2xl p-md flex flex-col justify-between h-32 block">
                <div class="flex justify-between items-start">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Total Proyek</span>
                    <div class="p-xs rounded-full bg-surface-container-high text-primary">
                        <span class="material-symbols-outlined text-[20px]">folder_open</span>
                    </div>
                </div>
                <div>
                    <div class="font-headline-xl text-headline-xl text-on-surface" id="stat-total-proj-val">0</div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Proyek yang diikuti</p>
                </div>
            </a>
            <!-- Stat: Completed Projects -->
            <a href="projects.php" class="glass-card bg-surface-container-lowest border border-outline-variant rounded-2xl p-md flex flex-col justify-between h-32 block">
                <div class="flex justify-between items-start">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Proyek Selesai</span>
                    <div class="p-xs rounded-full bg-surface-container-high text-primary">
                        <span class="material-symbols-outlined text-[20px]">task_alt</span>
                    </div>
                </div>
                <div>
                    <div class="font-headline-xl text-headline-xl text-on-surface" id="stat-completed-proj-val">0</div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Semua task rampung</p>
                </div>
            </a>
        </div>
        <!-- Attendance (Timemark style) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-md items-start">
            <div class="lg:col-span-5">
                <div class="glass-card bg-surface-container-lowest border border-outline-variant rounded-2xl p-lg flex flex-col items-center text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary-fixed opacity-30 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                    <h3 class="font-headline-md text-headline-md text-on-surface mb-xs">Absensi Hari Ini</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mb-sm" id="tm-date">-</p>
                    <div class="my-md">
                        <span class="font-headline-xl text-[40px] leading-tight font-black text-primary tabular-nums tracking-tight" id="tm-clock">00:00:00</span>
                    </div>
                    <span class="inline-flex items-center gap-xs px-3 py-1 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm mb-md" id="tm-status-badge">
                        Belum Absen
                    </span>
                    <div class="w-full mb-md text-left">
                        <div class="p-sm rounded-lg border border-outline-variant bg-surface-bright overflow-hidden">
                            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Jam Masuk</p>
                            <p class="font-headline-sm text-headline-sm text-on-surface font-bold" id="tm-in-time">--:--</p>
                            <img id="tm-in-photo" class="hidden w-full h-32 object-cover rounded-md mt-xs" alt="Foto clock in">
                        </div>
                    </div>
                    <div class="w-full">
                        <button id="tm-clockin-btn" onclick="timemarkStartCapture()" class="w-full bg-primary text-on-primary hover:shadow-md transition-all rounded-lg py-sm px-md font-label-md text-label-md flex items-center justify-center gap-sm active:scale-[0.98] disabled:opacity-40 disabled:cursor-not-allowed">
                            <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                            Clock In
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden fallback camera input -->
        <input type="file" accept="image/*" capture="user" id="tm-camera-fallback" class="hidden">

        <!-- Live Camera Stream Modal -->
        <div id="tm-modal" class="hidden fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-md">
            <div class="bg-surface-container-lowest rounded-2xl p-md max-w-sm w-full shadow-2xl">
                <h4 class="font-headline-sm text-headline-sm text-on-surface mb-sm text-center" id="tm-modal-title">Clock In - Ambil Foto</h4>
                
                <!-- Live Video Feed -->
                <div id="tm-video-wrap" class="relative rounded-xl overflow-hidden border border-outline-variant mb-sm bg-black aspect-[3/4] flex items-center justify-center">
                    <video id="tm-video" autoplay playsinline class="w-full h-full object-cover"></video>
                    <canvas id="tm-canvas" class="hidden w-full h-full object-cover"></canvas>
                </div>

                <p class="font-body-sm text-body-sm text-on-surface-variant text-center mb-md" id="tm-modal-status">Membuka kamera...</p>

                <!-- Action Controls -->
                <div id="tm-cam-actions" class="grid grid-cols-2 gap-sm">
                    <button onclick="timemarkCloseModal()" class="w-full bg-surface-container-high text-on-surface rounded-lg py-sm font-label-md text-label-md hover:bg-surface-container-highest">Batal</button>
                    <button id="tm-capture-btn" onclick="timemarkCapture()" class="w-full bg-primary text-on-primary rounded-lg py-sm font-label-md text-label-md flex items-center justify-center gap-xs hover:opacity-90">
                        <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                        Ambil Foto
                    </button>
                </div>
                <div id="tm-confirm-actions" class="grid grid-cols-2 gap-sm hidden">
                    <button onclick="timemarkRetake()" class="w-full bg-surface-container-high text-on-surface rounded-lg py-sm font-label-md text-label-md hover:bg-surface-container-highest">Ulangi</button>
                    <button id="tm-confirm-btn" onclick="timemarkConfirm()" class="w-full bg-primary text-on-primary rounded-lg py-sm font-label-md text-label-md disabled:opacity-40 disabled:cursor-not-allowed hover:opacity-90" disabled>Simpan</button>
                </div>
            </div>
        </div>
        <div class="h-md"></div>
    </div>
</main>

<script>
// Username dari sesi PHP yang sedang login (dipakai untuk simpan absensi ke database)
const CURRENT_USERNAME = <?php echo json_encode(current_user_name()); ?>;

// Determine intern name from URL parameter
const params = new URLSearchParams(window.location.search);
const internParam = params.get('intern');
if (internParam) {
  const nameElem = document.getElementById('intern-name');
  if (nameElem) nameElem.textContent = decodeURIComponent(internParam);
}

    let clockInterval = null;
    let isClockedIn = localStorage.getItem('internspace-clock-status') !== 'inactive';
    let clockStartTime = parseInt(localStorage.getItem('internspace-clock-start') || (Date.now() - 10035000), 10);

    function getElapsedSeconds() {
        if (!isClockedIn) {
            return parseInt(localStorage.getItem('internspace-clock-accumulated') || '10035', 10);
        }
        return Math.floor((Date.now() - clockStartTime) / 1000);
    }

    function updateTimerDisplay() {
        const elapsed = getElapsedSeconds();
        const hours = Math.floor(elapsed / 3600);
        const minutes = Math.floor((elapsed % 3600) / 60);
        const seconds = elapsed % 60;
        const timerElem = document.getElementById('live-timer');
        if (timerElem) {
            timerElem.textContent = 
                String(hours).padStart(2, '0') + ':' + 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
        }
    }
function toggleClock() {
    isClockedIn = !isClockedIn;
    const btnText = document.getElementById('clock-btn-text');
    const btnIcon = document.getElementById('clock-btn-icon');
    const statusText = document.getElementById('clock-status-text');
    const statusDot = document.getElementById('clock-status-dot');
    const btn = document.getElementById('clock-btn');

    if (isClockedIn) {
        const acc = parseInt(localStorage.getItem('internspace-clock-accumulated') || '10035', 10);
        clockStartTime = Date.now() - (acc * 1000);
        localStorage.setItem('internspace-clock-start', clockStartTime);
        localStorage.setItem('internspace-clock-status', 'active');
        clockInterval = setInterval(updateTimerDisplay, 1000);
        if (btnText) { btnText.dataset.i18n = 'btn_clock_out'; btnText.textContent = window.I18n ? window.I18n.t('btn_clock_out') : "Clock Out"; }
        if (btnIcon) btnIcon.textContent = "logout";
        if (statusText) { statusText.dataset.i18n = 'shift_active'; statusText.textContent = window.I18n ? window.I18n.t('shift_active') : "Currently Active"; }
        if (statusDot) statusDot.className = "w-2 h-2 rounded-full bg-primary animate-pulse";
        if (btn) btn.className = "w-full bg-primary text-on-primary hover:bg-on-primary-fixed hover:shadow-md transition-all rounded-lg py-sm px-md font-label-md text-label-md flex items-center justify-center gap-sm active:scale-[0.98]";
    } else {
        const accumulated = getElapsedSeconds();
        localStorage.setItem('internspace-clock-accumulated', accumulated);
        localStorage.setItem('internspace-clock-status', 'inactive');
        clearInterval(clockInterval);
        if (btnText) { btnText.dataset.i18n = 'btn_clock_in'; btnText.textContent = window.I18n ? window.I18n.t('btn_clock_in') : "Clock In"; }
        if (btnIcon) btnIcon.textContent = "login";
        if (statusText) { statusText.dataset.i18n = 'shift_inactive'; statusText.textContent = window.I18n ? window.I18n.t('shift_inactive') : "Inactive"; }
        if (statusDot) statusDot.className = "w-2 h-2 rounded-full bg-outline";
        if (btn) btn.className = "w-full bg-green-600 text-white hover:bg-green-700 hover:shadow-md transition-all rounded-lg py-sm px-md font-label-md text-label-md flex items-center justify-center gap-sm active:scale-[0.98]";
    }
    updateTimerDisplay();
}

    document.addEventListener('DOMContentLoaded', () => {
        updateTimerDisplay();
        if (isClockedIn) {
            clockInterval = setInterval(updateTimerDisplay, 1000);
        } else {
            toggleClock(); // apply inactive UI states
            isClockedIn = false;
        }

        if (window.ProjectStore) {
            const projects = ProjectStore.projects();
            let completedCount = 0;
            projects.forEach(p => {
                if (p.tasks) completedCount += p.tasks.filter(t => t.status === 'done').length;
            });
            const pElem = document.getElementById('stat-active-proj-val');
            const tElem = document.getElementById('stat-tasks-done-val');
            if (pElem) pElem.textContent = projects.length;
            if (tElem) tElem.textContent = completedCount;

            // Total proyek yang diikuti & jumlah proyek yang sudah selesai
            const totalProjElem = document.getElementById('stat-total-proj-val');
            const completedProjElem = document.getElementById('stat-completed-proj-val');
            const finishedProjects = projects.filter(p =>
                p.tasks && p.tasks.length > 0 &&
                p.tasks.every(t => ProjectStore.normalizeStatus(t.status) === 'done')
            ).length;
            if (totalProjElem) totalProjElem.textContent = projects.length;
            if (completedProjElem) completedProjElem.textContent = finishedProjects;
        }

        // Timemark-style attendance
        timemarkUpdateClock();
        setInterval(timemarkUpdateClock, 1000);
        timemarkRefreshUI();
    });

    // ============================================================
    // TIMEMARK ATTENDANCE (Clock In)
    // ============================================================
    function timemarkPad(n) { return String(n).padStart(2, '0'); }

    function timemarkToday() {
        const d = new Date();
        return `${d.getFullYear()}-${timemarkPad(d.getMonth() + 1)}-${timemarkPad(d.getDate())}`;
    }

    function timemarkUpdateClock() {
        const now = new Date();
        const clockEl = document.getElementById('tm-clock');
        if (clockEl) {
            clockEl.textContent = `${timemarkPad(now.getHours())}:${timemarkPad(now.getMinutes())}:${timemarkPad(now.getSeconds())}`;
        }
        const dateEl = document.getElementById('tm-date');
        if (dateEl) {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            dateEl.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
        }
    }

    function timemarkSetBadge(text, variant) {
        const badge = document.getElementById('tm-status-badge');
        if (!badge) return;
        badge.textContent = text;
        const base = 'inline-flex items-center gap-xs px-3 py-1 rounded-full font-label-sm text-label-sm mb-md ';
        badge.className = base + (variant === 'active'
            ? 'bg-secondary-container text-on-secondary-container'
            : variant === 'done'
                ? 'bg-primary-container text-on-primary-container'
                : 'bg-surface-container-high text-on-surface-variant');
    }

    function timemarkRefreshUI() {
        const inTimeEl = document.getElementById('tm-in-time');
        const inPhotoEl = document.getElementById('tm-in-photo');
        const btnIn = document.getElementById('tm-clockin-btn');

        const showPhoto = (el, url) => {
            if (!el) return;
            if (url) { el.src = url; el.classList.remove('hidden'); }
            else { el.classList.add('hidden'); el.removeAttribute('src'); }
        };

        fetch('attendance-api.php?action=today')
            .then(r => r.json())
            .then(rec => {
                if (rec && rec.exists && rec.clock_in) {
                    if (inTimeEl) inTimeEl.textContent = rec.clock_in;
                    showPhoto(inPhotoEl, rec.photo_in);
                    if (btnIn) btnIn.disabled = true;
                    timemarkSetBadge('Sudah Absen', 'done');
                } else {
                    if (inTimeEl) inTimeEl.textContent = '--:--';
                    showPhoto(inPhotoEl, null);
                    if (btnIn) btnIn.disabled = false;
                    timemarkSetBadge('Belum Absen', 'idle');
                }
            })
            .catch(() => {
                timemarkSetBadge('Gagal memuat status absen', 'idle');
            });
    }

    // ---------------- Kamera + Geotag (Live Camera Feed) ----------------
    let tmStream = null;
    let tmPendingDataUrl = null;
    let tmPendingAddress = '';
    let tmPendingLat = null;
    let tmPendingLng = null;

    async function timemarkStartCapture() {
        const modal = document.getElementById('tm-modal');
        const video = document.getElementById('tm-video');
        const canvas = document.getElementById('tm-canvas');
        const statusEl = document.getElementById('tm-modal-status');
        const titleEl = document.getElementById('tm-modal-title');
        const camActions = document.getElementById('tm-cam-actions');
        const confirmActions = document.getElementById('tm-confirm-actions');

        // Check navigator.mediaDevices support
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            timemarkTriggerFallback();
            return;
        }

        if (!modal || !video) return;

        titleEl.textContent = 'Clock In - Ambil Foto';
        statusEl.textContent = 'Membuka kamera...';
        video.classList.remove('hidden');
        canvas.classList.add('hidden');
        camActions.classList.remove('hidden');
        confirmActions.classList.add('hidden');
        modal.classList.remove('hidden');

        try {
            tmStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } },
                audio: false
            });
            video.srcObject = tmStream;
            statusEl.textContent = 'Posisikan wajah Anda lalu tekan Ambil Foto.';
        } catch (err) {
            console.warn('Camera live feed failed, falling back to camera input:', err);
            timemarkCloseModal();
            timemarkTriggerFallback();
        }
    }

    function timemarkTriggerFallback() {
        const fallbackInput = document.getElementById('tm-camera-fallback');
        if (fallbackInput) fallbackInput.click();
    }

    function timemarkHandleFallbackFile(e) {
        const file = e.target.files && e.target.files[0];
        e.target.value = '';
        if (!file) return;
        const reader = new FileReader();
        reader.onload = evt => {
            const img = new Image();
            img.onload = () => {
                const modal = document.getElementById('tm-modal');
                const video = document.getElementById('tm-video');
                const canvas = document.getElementById('tm-canvas');
                const camActions = document.getElementById('tm-cam-actions');
                const confirmActions = document.getElementById('tm-confirm-actions');

                if (video) video.classList.add('hidden');
                if (canvas) canvas.classList.remove('hidden');
                if (camActions) camActions.classList.add('hidden');
                if (confirmActions) confirmActions.classList.remove('hidden');
                if (modal) modal.classList.remove('hidden');

                timemarkComposeAndShow(img);
            };
            img.src = evt.target.result;
        };
        reader.readAsDataURL(file);
    }

    const tmFallbackInput = document.getElementById('tm-camera-fallback');
    if (tmFallbackInput) tmFallbackInput.addEventListener('change', timemarkHandleFallbackFile);

    function timemarkStopCamera() {
        if (tmStream) {
            tmStream.getTracks().forEach(track => track.stop());
            tmStream = null;
        }
    }

    function timemarkCloseModal() {
        timemarkStopCamera();
        document.getElementById('tm-modal')?.classList.add('hidden');
    }

    function timemarkCapture() {
        const video = document.getElementById('tm-video');
        const canvas = document.getElementById('tm-canvas');
        const videoWrap = document.getElementById('tm-video-wrap');
        if (!video || !canvas || !video.videoWidth) return;

        const vW = video.videoWidth;
        const vH = video.videoHeight;
        
        // Draw video frame to temp canvas
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = vW;
        tempCanvas.height = vH;
        const tempCtx = tempCanvas.getContext('2d');
        tempCtx.drawImage(video, 0, 0, vW, vH);

        // Stop stream & switch to canvas view
        timemarkStopCamera();
        video.classList.add('hidden');
        canvas.classList.remove('hidden');

        const camActions = document.getElementById('tm-cam-actions');
        const confirmActions = document.getElementById('tm-confirm-actions');
        camActions.classList.add('hidden');
        confirmActions.classList.remove('hidden');

        // Create HTML Image element from captured video frame
        const img = new Image();
        img.onload = () => timemarkComposeAndShow(img);
        img.src = tempCanvas.toDataURL('image/jpeg');
    }

    function timemarkWrapAddress(addr, maxLen) {
        maxLen = maxLen || 42;
        const words = addr.split(' ');
        const lines = [];
        let line = '';
        words.forEach(w => {
            if ((line + ' ' + w).trim().length > maxLen) {
                lines.push(line.trim());
                line = w;
            } else {
                line += ' ' + w;
            }
        });
        if (line.trim()) lines.push(line.trim());
        return lines.slice(0, 3); // maksimal 3 baris alamat
    }

    function timemarkComposeAndShow(img) {
        const modal = document.getElementById('tm-modal');
        const canvas = document.getElementById('tm-canvas');
        const statusEl = document.getElementById('tm-modal-status');
        const confirmBtn = document.getElementById('tm-confirm-btn');
        const titleEl = document.getElementById('tm-modal-title');
        if (!modal || !canvas) return;

        titleEl.textContent = 'Konfirmasi Clock In';
        if (confirmBtn) confirmBtn.disabled = true;
        if (statusEl) statusEl.textContent = 'Mengambil lokasi...';

        const maxW = 900;
        const scale = Math.min(1, maxW / img.width);
        canvas.width = Math.round(img.width * scale);
        canvas.height = Math.round(img.height * scale);
        const ctx = canvas.getContext('2d');

        const now = new Date();
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const timeStr = `${timemarkPad(now.getHours())}:${timemarkPad(now.getMinutes())}`;
        const dateStr = `${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
        const dayStr = days[now.getDay()];

        function draw(addressLines) {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

            const padX = canvas.width * 0.04;
            const lineH = canvas.height * 0.034;
            const boxH = canvas.height * (0.20 + addressLines.length * 0.034);
            const grad = ctx.createLinearGradient(0, canvas.height - boxH, 0, canvas.height);
            grad.addColorStop(0, 'rgba(0,0,0,0)');
            grad.addColorStop(1, 'rgba(0,0,0,0.68)');
            ctx.fillStyle = grad;
            ctx.fillRect(0, canvas.height - boxH, canvas.width, boxH);

            ctx.fillStyle = '#fff';
            ctx.textBaseline = 'alphabetic';

            const timeFontSize = canvas.width * 0.085;
            ctx.font = `900 ${timeFontSize}px sans-serif`;
            let y = canvas.height - boxH + timeFontSize + (canvas.height * 0.02);
            ctx.fillText(timeStr, padX, y);
            const timeWidth = ctx.measureText(timeStr).width;

            ctx.strokeStyle = 'rgba(255,255,255,0.55)';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(padX + timeWidth + 14, y - timeFontSize * 0.8);
            ctx.lineTo(padX + timeWidth + 14, y + 2);
            ctx.stroke();

            ctx.font = `600 ${timeFontSize * 0.3}px sans-serif`;
            ctx.fillText(dateStr, padX + timeWidth + 26, y - timeFontSize * 0.4);
            ctx.fillText(dayStr, padX + timeWidth + 26, y);

            ctx.font = `500 ${canvas.width * 0.028}px sans-serif`;
            let ay = y + canvas.height * 0.05;
            addressLines.forEach(line => {
                ctx.fillText(line, padX, ay);
                ay += lineH;
            });

            tmPendingDataUrl = canvas.toDataURL('image/jpeg', 0.85);
            if (confirmBtn) confirmBtn.disabled = false;
            if (statusEl) statusEl.textContent = 'Foto siap — cek dulu sebelum disimpan.';
        }

        tmPendingAddress = '';
        tmPendingLat = null;
        tmPendingLng = null;

        if (!navigator.geolocation) {
            if (statusEl) statusEl.textContent = 'Lokasi tidak tersedia di perangkat ini.';
            draw(['Lokasi tidak tersedia']);
            return;
        }

        navigator.geolocation.getCurrentPosition(
            pos => {
                const { latitude, longitude } = pos.coords;
                tmPendingLat = latitude;
                tmPendingLng = longitude;
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
                    .then(r => r.json())
                    .then(data => {
                        const addr = data && data.display_name ? data.display_name : `${latitude.toFixed(5)}, ${longitude.toFixed(5)}`;
                        tmPendingAddress = addr;
                        draw(timemarkWrapAddress(addr));
                    })
                    .catch(() => {
                        tmPendingAddress = `${latitude.toFixed(5)}, ${longitude.toFixed(5)}`;
                        draw([tmPendingAddress]);
                    });
            },
            () => {
                if (statusEl) statusEl.textContent = 'Izin lokasi ditolak — foto disimpan tanpa lokasi.';
                tmPendingAddress = 'Lokasi tidak diizinkan';
                draw(['Lokasi tidak diizinkan']);
            },
            { timeout: 8000 }
        );
    }

    function timemarkRetake() {
        tmPendingDataUrl = null;
        timemarkStartCapture();
    }

    const TM_CUTOFF_HOUR = 9; // 09:00 batas tepat waktu

    function timemarkConfirm() {
        if (!tmPendingDataUrl) return;
        const confirmBtn = document.getElementById('tm-confirm-btn');
        const statusEl = document.getElementById('tm-modal-status');
        const now = new Date();
        const timeStr = `${timemarkPad(now.getHours())}:${timemarkPad(now.getMinutes())}`;
        const status = now.getHours() >= TM_CUTOFF_HOUR ? 'late' : 'present';

        if (confirmBtn) confirmBtn.disabled = true;
        if (statusEl) statusEl.textContent = 'Menyimpan ke database...';

        fetch('attendance-api.php?action=save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                time: timeStr,
                status: status,
                photo: tmPendingDataUrl,
                location: tmPendingAddress,
                lat: tmPendingLat,
                lng: tmPendingLng
            })
        })
            .then(async r => {
                const data = await r.json().catch(() => ({}));
                if (!r.ok) throw new Error(data.error || 'Gagal menyimpan absensi');
                return data;
            })
            .then(() => {
                document.getElementById('tm-modal')?.classList.add('hidden');
                tmPendingDataUrl = null;
                timemarkRefreshUI();
            })
            .catch(err => {
                if (statusEl) statusEl.textContent = err.message || 'Gagal menyimpan, coba lagi.';
                if (confirmBtn) confirmBtn.disabled = false;
            });
    }
</script>
<script src="project-store.js"></script>
<script src="intern-store.js"></script>
</body>
</html>