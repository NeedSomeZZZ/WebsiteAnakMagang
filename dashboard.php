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
<?php $active = 'dashboard'; $show_admin_link = true; include 'partials/sidebar-intern.php'; ?>

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
           
        </div>
        <!-- Main Bento Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-md items-start">
            <!-- Center/Left Column -->
            <div class="lg:col-span-8 flex flex-col gap-md">
                <!-- Latest Badges -->
               
              
            </div>
          
        </div>
        <div class="h-md"></div>
    </div>
</main>

<script>
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
        }
    });
</script>
<script src="project-store.js"></script>
<script src="lang.js"></script>
<script src="language-ui.js"></script>
<script src="performance.js"></script>
</body>
</html>
