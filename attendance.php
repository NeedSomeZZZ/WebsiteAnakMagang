<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Kedayweb - Kehadiran PKL</title>
    <meta name="description"
        content="Pantau kehadiran PKL Anda — kalender interaktif, status kehadiran, dan riwayat lengkap." />
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="shared-config.js"></script>
    <script src="intern-store.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-geist {
            font-family: 'Geist', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 500;
        }

        /* Calendar */
        .cal-cell {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1.5px solid transparent;
        }

        .cal-cell:hover {
            transform: scale(1.07);
            border-color: var(--color-primary, #2563eb);
        }

        .cal-cell.today {
            box-shadow: 0 0 0 2px #2563eb;
        }

        .cal-cell.status-present {
            background: #dcfce7;
            color: #166534;
        }

        .cal-cell.status-late {
            background: #fef9c3;
            color: #854d0e;
        }

        .cal-cell.status-absent {
            background: #fee2e2;
            color: #991b1b;
        }

        .cal-cell.weekend {
            background: #f8fafc;
            color: #94a3b8;
            opacity: 0.7;
            cursor: default;
        }

        .cal-cell.weekend:hover {
            transform: none;
            border-color: transparent;
        }

        .cal-cell.empty {
            cursor: default;
        }

        .cal-cell.empty:hover {
            transform: none;
            border-color: transparent;
        }

        .cal-cell.future {
            background: #f1f5f9;
            color: #cbd5e1;
            cursor: not-allowed;
        }

        .cal-cell.future:hover {
            transform: none;
            border-color: transparent;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            position: absolute;
            bottom: 5px;
        }

        /* Badge */
        .badge-present {
            background: #dcfce7;
            color: #166534;
        }

        .badge-late {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-absent {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Modal backdrop */
        .modal-backdrop {
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
        }

        /* Stat card gradient */
        .stat-present {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        }

        .stat-late {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        }

        .stat-absent {
            background: linear-gradient(135deg, #fee2e2 0%, #fca5a5 100%);
        }

        .stat-rate {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Table row hover */
        .att-row {
            transition: background 0.12s;
        }

        .att-row:hover {
            background: #f8fafc;
        }

        /* Toast */
        #att-toast {
            transition: opacity 0.3s, transform 0.3s;
        }

        #att-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        #att-toast.hide {
            opacity: 0;
            transform: translateY(12px);
        }

        /* Streak badge */
        .streak-badge {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="bg-surface text-on-surface font-body-md min-h-screen flex">

    <!-- SideNavBar -->
<?php $active = 'attendance'; include 'partials/sidebar-intern.php'; ?>

    <?php include 'partials/topnav-mobile.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 md:ml-[16.5rem] pt-20 md:pt-0 pb-10 px-4 md:px-8 max-w-[1400px] mx-auto w-full">

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 mt-6 md:mt-8">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-primary mb-1" id="page-eyebrow">PKL Tracker
                </p>
                <h2 class="font-geist text-3xl md:text-4xl font-bold text-on-surface" id="page-title">Kehadiran Saya
                </h2>
                <p class="text-sm text-on-surface-variant mt-1" id="page-subtitle">Pantau presensi, status kedatangan,
                    dan riwayat selama masa PKL.</p>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <button onclick="exportAttendanceCSV()"
                    class="flex-1 md:flex-none flex items-center justify-center gap-2 border border-outline-variant text-on-surface bg-surface-container-lowest px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Export CSV
                </button>
            </div>
        </div>

        <!-- Tracker mode info banner -->
        <div
            class="mb-6 flex items-center gap-3 bg-blue-50 border border-blue-200 text-blue-900 px-4 py-3 rounded-xl text-sm shadow-sm">
            <span class="material-symbols-outlined text-blue-600 text-[20px]">info</span>
            <span>Halaman ini berfungsi sebagai <strong>Tracker Kehadiran</strong>. Pengeditan dan pencatatan data
                kehadiran dilakukan oleh <strong>Admin</strong>.</span>
        </div>

        <!-- Admin preview banner (hanya tampil jika dibuka via ?intern=Nama oleh Admin) -->
        <div id="admin-preview-banner"
            class="hidden mb-6 flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-900 px-4 py-3 rounded-xl text-sm">
            <span class="material-symbols-outlined text-amber-600">visibility</span>
            <span>Anda (Admin) sedang melihat kehadiran milik <strong id="admin-preview-name"></strong> dalam mode
                baca-saja.</span>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
            <div class="stat-present rounded-2xl p-4 border border-green-200">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-green-700 text-[20px]"
                        style="font-variation-settings:'FILL' 1;">check_circle</span>
                    <span class="text-xs font-bold uppercase tracking-wider text-green-700">Hadir</span>
                </div>
                <div id="stat-present" class="text-3xl font-geist font-bold text-green-800">0</div>
                <div class="text-xs text-green-700 mt-0.5">hari</div>
            </div>
            <div class="stat-late rounded-2xl p-4 border border-amber-200">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-amber-700 text-[20px]"
                        style="font-variation-settings:'FILL' 1;">schedule</span>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Terlambat</span>
                </div>
                <div id="stat-late" class="text-3xl font-geist font-bold text-amber-800">0</div>
                <div class="text-xs text-amber-700 mt-0.5">hari</div>
            </div>
            <div class="stat-absent rounded-2xl p-4 border border-red-200">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-red-700 text-[20px]"
                        style="font-variation-settings:'FILL' 1;">cancel</span>
                    <span class="text-xs font-bold uppercase tracking-wider text-red-700">Tidak Masuk</span>
                </div>
                <div id="stat-absent" class="text-3xl font-geist font-bold text-red-800">0</div>
                <div class="text-xs text-red-700 mt-0.5">hari</div>
            </div>
            <div class="stat-rate rounded-2xl p-4 border border-blue-200">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-blue-700 text-[20px]"
                        style="font-variation-settings:'FILL' 1;">insights</span>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-700">Kehadiran</span>
                </div>
                <div id="stat-rate" class="text-3xl font-geist font-bold text-blue-800">0%</div>
                <div class="text-xs text-blue-700 mt-0.5">tingkat hadir</div>
            </div>
        </div>

        <!-- Main Bento Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">

            <!-- Left: Streak + Legend -->
            <div class="lg:col-span-4 flex flex-col gap-4">

                <!-- Streak Card -->
                <div
                    class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-5 flex flex-col items-center text-center">
                    <span class="material-symbols-outlined text-[44px] text-amber-400 mb-2"
                        style="font-variation-settings:'FILL' 1;">local_fire_department</span>
                    <div id="streak-count" class="font-geist text-5xl font-black streak-badge mb-1">0</div>
                    <p class="font-semibold text-on-surface mb-0.5">Hari Berturut-turut</p>
                    <p id="streak-desc" class="text-xs text-on-surface-variant">Belum ada data kehadiran</p>
                </div>

                <!-- Legend Card -->
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-5">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3">Keterangan
                        Kalender</h3>
                    <div class="space-y-2.5">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center text-green-700 text-xs font-bold">
                                12</div>
                            <div>
                                <div class="text-sm font-semibold text-on-surface">Hadir Tepat Waktu</div>
                                <div class="text-xs text-on-surface-variant">Masuk sebelum pukul 09:00</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-700 text-xs font-bold">
                                12</div>
                            <div>
                                <div class="text-sm font-semibold text-on-surface">Terlambat</div>
                                <div class="text-xs text-on-surface-variant">Masuk setelah pukul 09:00</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-700 text-xs font-bold">
                                12</div>
                            <div>
                                <div class="text-sm font-semibold text-on-surface">Tidak Masuk</div>
                                <div class="text-xs text-on-surface-variant">Izin / Sakit / Alpha</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 text-xs font-bold">
                                12</div>
                            <div>
                                <div class="text-sm font-semibold text-on-surface">Akhir Pekan / Kosong</div>
                                <div class="text-xs text-on-surface-variant">Weekend atau belum diisi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick summary for current month -->
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-5">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3">Bulan Ini</h3>
                    <div class="space-y-2" id="monthly-summary">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-on-surface-variant">Hadir</span>
                            <span id="month-present" class="font-bold text-green-700">0 hari</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-on-surface-variant">Terlambat</span>
                            <span id="month-late" class="font-bold text-amber-700">0 hari</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-on-surface-variant">Tidak Masuk</span>
                            <span id="month-absent" class="font-bold text-red-700">0 hari</span>
                        </div>
                        <div class="h-px bg-outline-variant my-1"></div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-on-surface-variant font-semibold">Total Hari Kerja</span>
                            <span id="month-total" class="font-bold text-on-surface">0 hari</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Calendar -->
            <div class="lg:col-span-8 bg-surface-container-lowest rounded-2xl border border-outline-variant p-5">
                <!-- Calendar Header -->
                <div class="flex justify-between items-center mb-5">
                    <h3 id="cal-title" class="font-geist text-xl font-bold text-on-surface">September 2026</h3>
                    <div class="flex items-center gap-1">
                        <button id="cal-prev" onclick="changeMonth(-1)"
                            class="p-2 rounded-lg hover:bg-surface-container-low transition-colors text-on-surface-variant hover:text-primary">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                        <button onclick="goToday()"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-primary-container text-on-primary-container hover:opacity-80 transition-opacity">
                            Hari Ini
                        </button>
                        <button id="cal-next" onclick="changeMonth(1)"
                            class="p-2 rounded-lg hover:bg-surface-container-low transition-colors text-on-surface-variant hover:text-primary">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>
                </div>
                <!-- Day Headers -->
                <div class="grid grid-cols-7 gap-1.5 mb-2 text-center">
                    <div class="text-xs font-bold uppercase tracking-wider text-red-400">Min</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Sen</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Sel</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Rab</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Kam</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Jum</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-red-400">Sab</div>
                </div>
                <!-- Calendar Grid -->
                <div id="cal-grid" class="grid grid-cols-7 gap-1.5"></div>
            </div>
        </div>

        <!-- History Table -->
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant overflow-hidden">
            <div
                class="p-5 border-b border-outline-variant flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h3 class="font-geist text-lg font-bold text-on-surface">Riwayat Kehadiran</h3>
                    <p class="text-xs text-on-surface-variant mt-0.5">Semua catatan kehadiran selama PKL</p>
                </div>
                <!-- Filter -->
                <div
                    class="flex items-center gap-1.5 bg-surface-container-low rounded-xl p-1 border border-outline-variant text-xs">
                    <button onclick="setFilter('all')" id="filter-all"
                        class="px-3 py-1.5 rounded-lg font-semibold bg-white text-primary shadow-sm">Semua</button>
                    <button onclick="setFilter('present')" id="filter-present"
                        class="px-3 py-1.5 rounded-lg font-semibold text-on-surface-variant hover:text-green-700">Hadir</button>
                    <button onclick="setFilter('late')" id="filter-late"
                        class="px-3 py-1.5 rounded-lg font-semibold text-on-surface-variant hover:text-amber-700">Terlambat</button>
                    <button onclick="setFilter('absent')" id="filter-absent"
                        class="px-3 py-1.5 rounded-lg font-semibold text-on-surface-variant hover:text-red-700">Tidak
                        Masuk</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th
                                class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                                Tanggal</th>
                            <th
                                class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                                Jam Masuk</th>
                            <th
                                class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                                Jam Keluar</th>
                            <th
                                class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                                Status</th>
                            <th
                                class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                                Alasan / Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="att-table-body" class="divide-y divide-outline-variant">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
                <div id="att-empty" class="hidden text-center py-16">
                    <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-3">event_busy</span>
                    <p class="font-semibold text-on-surface">Belum ada catatan kehadiran</p>
                    <p class="text-xs text-on-surface-variant mt-1">Data kehadiran diperbarui oleh Admin</p>
                </div>
            </div>
        </div>
    </main>

    <!-- ============================================================
     INPUT MODAL (Tambah Kehadiran)
     ============================================================ -->
    <div id="input-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 modal-backdrop">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl border border-outline-variant overflow-hidden">
            <div
                class="p-5 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary"
                        style="font-variation-settings:'FILL' 1;">edit_calendar</span>
                    <h3 class="font-geist font-bold text-on-surface" id="input-modal-title">Catat Kehadiran</h3>
                </div>
                <button onclick="closeInputModal()"
                    class="text-on-surface-variant hover:text-primary transition-colors p-1 rounded-lg hover:bg-surface-container-low">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="att-form" onsubmit="saveAttendance(event)" class="p-5 space-y-4">
                <!-- Date -->
                <div>
                    <label
                        class="block text-xs font-bold text-on-surface uppercase tracking-wide mb-1.5">Tanggal</label>
                    <input type="date" id="att-date" required
                        class="w-full rounded-xl border border-outline-variant bg-surface-bright px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:outline-none" />
                </div>
                <!-- Status -->
                <div>
                    <label class="block text-xs font-bold text-on-surface uppercase tracking-wide mb-1.5">Status
                        Kehadiran</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label id="status-btn-present" onclick="selectStatus('present')"
                            class="flex flex-col items-center gap-1 border-2 border-outline-variant rounded-xl p-3 cursor-pointer hover:border-green-400 transition-all">
                            <span class="material-symbols-outlined text-green-600 text-[22px]"
                                style="font-variation-settings:'FILL' 1;">check_circle</span>
                            <span class="text-xs font-bold text-green-700">Hadir</span>
                            <input type="radio" name="status" value="present" class="sr-only" required />
                        </label>
                        <label id="status-btn-late" onclick="selectStatus('late')"
                            class="flex flex-col items-center gap-1 border-2 border-outline-variant rounded-xl p-3 cursor-pointer hover:border-amber-400 transition-all">
                            <span class="material-symbols-outlined text-amber-600 text-[22px]"
                                style="font-variation-settings:'FILL' 1;">schedule</span>
                            <span class="text-xs font-bold text-amber-700">Terlambat</span>
                            <input type="radio" name="status" value="late" class="sr-only" />
                        </label>
                        <label id="status-btn-absent" onclick="selectStatus('absent')"
                            class="flex flex-col items-center gap-1 border-2 border-outline-variant rounded-xl p-3 cursor-pointer hover:border-red-400 transition-all">
                            <span class="material-symbols-outlined text-red-600 text-[22px]"
                                style="font-variation-settings:'FILL' 1;">cancel</span>
                            <span class="text-xs font-bold text-red-700">Tidak Masuk</span>
                            <input type="radio" name="status" value="absent" class="sr-only" />
                        </label>
                    </div>
                    <input type="hidden" id="att-status-hidden" name="status-val" />
                </div>
                <!-- Clock In & Out -->
                <div id="clock-fields" class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wide mb-1.5">Jam
                            Masuk</label>
                        <input type="time" id="att-clock-in"
                            class="w-full rounded-xl border border-outline-variant bg-surface-bright px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wide mb-1.5">Jam
                            Keluar</label>
                        <input type="time" id="att-clock-out"
                            class="w-full rounded-xl border border-outline-variant bg-surface-bright px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:outline-none" />
                    </div>
                </div>
                <!-- Reason -->
                <div id="reason-field">
                    <label class="block text-xs font-bold text-on-surface uppercase tracking-wide mb-1.5">
                        Alasan / Keterangan
                        <span id="reason-required-mark" class="text-red-500 ml-0.5 hidden">*</span>
                    </label>
                    <textarea id="att-reason" rows="3" placeholder="Contoh: Sakit demam, sudah izin ke mentor..."
                        class="w-full rounded-xl border border-outline-variant bg-surface-bright px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:outline-none resize-none"></textarea>
                    <p id="reason-hint" class="text-xs text-on-surface-variant mt-1 hidden">Wajib diisi jika status
                        Terlambat atau Tidak Masuk.</p>
                </div>
                <!-- Actions -->
                <div class="flex justify-end gap-2 pt-2 border-t border-outline-variant">
                    <button type="button" onclick="closeInputModal()"
                        class="px-4 py-2.5 rounded-xl border border-outline-variant text-sm font-semibold text-on-surface-variant hover:bg-surface-container-low transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-bold hover:opacity-90 transition-opacity shadow-sm">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================
     DETAIL MODAL (Lihat Detail Hari)
     ============================================================ -->
    <div id="detail-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 modal-backdrop">
        <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl border border-outline-variant overflow-hidden">
            <div id="detail-header" class="p-5 border-b border-outline-variant flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-0.5">Detail
                        Kehadiran</p>
                    <h3 id="detail-date-label" class="font-geist font-bold text-on-surface text-lg"></h3>
                </div>
                <button onclick="closeDetailModal()"
                    class="text-on-surface-variant hover:text-primary transition-colors p-1 rounded-lg hover:bg-surface-container-low">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-5 space-y-3">
                <div class="flex justify-center mb-2">
                    <span id="detail-badge"
                        class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-bold"></span>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-surface-container-low rounded-xl p-3 text-center">
                        <p class="text-xs text-on-surface-variant mb-1">Jam Masuk</p>
                        <p id="detail-clock-in" class="font-geist font-bold text-on-surface">--:--</p>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-3 text-center">
                        <p class="text-xs text-on-surface-variant mb-1">Jam Keluar</p>
                        <p id="detail-clock-out" class="font-geist font-bold text-on-surface">--:--</p>
                    </div>
                </div>
                <div id="detail-reason-wrap" class="bg-amber-50 border border-amber-200 rounded-xl p-3 hidden">
                    <p class="text-xs font-bold text-amber-700 uppercase mb-1">Alasan</p>
                    <p id="detail-reason" class="text-sm text-amber-900"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="att-toast"
        class="fixed bottom-6 right-6 z-[100] flex items-center gap-3 bg-slate-900 text-white px-5 py-3 rounded-xl shadow-xl hide pointer-events-none text-sm font-semibold">
        <span id="att-toast-icon" class="material-symbols-outlined text-[18px]">check_circle</span>
        <span id="att-toast-msg">Tersimpan!</span>
    </div>

    <script>
        // ============================================================
        // DATA STORE
        // ============================================================
        const CUTOFF_HOUR = 9; // 09:00 = batas tepat waktu

        // Tentukan intern mana yang sedang dilihat: kalau ada ?intern= (dibuka Admin dari
        // admin-dashboard/admin-attendance), tampilkan punya intern itu dalam mode baca-saja.
        // Selain itu pakai "current user" (demo login) agar konsisten dengan halaman lain.
        const attParams = new URLSearchParams(window.location.search);
        const attInternParam = attParams.get('intern') ? decodeURIComponent(attParams.get('intern')) : null;
        const isAdminPreview = !!attInternParam;
        const activeInternName = attInternParam || (window.InternStore ? InternStore.getCurrentUser() : 'Alex Doe');

        function loadData() {
            if (window.InternStore) return InternStore.getAttendance(activeInternName);
            try { return JSON.parse(localStorage.getItem('internspace-attendance-v1') || '[]'); }
            catch { return []; }
        }

        function saveData(data) {
            if (window.InternStore) { InternStore.saveAttendance(activeInternName, data); return; }
            localStorage.setItem('internspace-attendance-v1', JSON.stringify(data));
        }

        function getRecord(dateStr) {
            return loadData().find(r => r.date === dateStr) || null;
        }

        function seedSampleData() {
            if (window.InternStore) { InternStore.seedAttendanceFor(activeInternName); return; }
            if (loadData().length > 0) return;
            const samples = [
                { date: '2026-09-01', status: 'present', clockIn: '08:45', clockOut: '17:00', reason: '' },
                { date: '2026-09-02', status: 'present', clockIn: '08:30', clockOut: '17:05', reason: '' },
                { date: '2026-09-03', status: 'late', clockIn: '09:22', clockOut: '17:00', reason: 'Bus kota terlambat datang' },
                { date: '2026-09-04', status: 'present', clockIn: '08:50', clockOut: '17:00', reason: '' },
                { date: '2026-09-05', status: 'present', clockIn: '08:55', clockOut: '17:10', reason: '' },
                { date: '2026-09-08', status: 'absent', clockIn: '', clockOut: '', reason: 'Sakit demam, sudah izin ke mentor via WhatsApp' },
                { date: '2026-09-09', status: 'late', clockIn: '09:45', clockOut: '17:00', reason: 'Kendaraan mogok di jalan, menunggu derek' },
            ];
            saveData(samples);
        }

        function applyAdminPreviewMode() {
            if (!isAdminPreview) return;
            document.getElementById('page-eyebrow').textContent = 'Admin \u2013 Mode Lihat';
            document.getElementById('page-title').textContent = `Kehadiran ${activeInternName}`;
            document.getElementById('page-subtitle').textContent = 'Data kehadiran intern ini, dilihat oleh Admin.';
            document.getElementById('admin-preview-banner').classList.remove('hidden');
            document.getElementById('admin-preview-name').textContent = activeInternName;
            document.getElementById('main-input-btn')?.classList.add('hidden');
            document.querySelectorAll('.att-add-trigger').forEach(el => el.classList.add('hidden'));
        }

        // ============================================================
        // CALENDAR STATE
        // ============================================================
        let calYear, calMonth;
        let currentFilter = 'all';
        let detailDate = null;

        function initCalendar() {
            const now = new Date();
            calYear = now.getFullYear();
            calMonth = now.getMonth(); // 0-indexed
        }

        function changeMonth(dir) {
            calMonth += dir;
            if (calMonth > 11) { calMonth = 0; calYear++; }
            if (calMonth < 0) { calMonth = 11; calYear--; }
            renderCalendar();
            renderMonthlySummary();
        }

        function goToday() {
            const now = new Date();
            calYear = now.getFullYear();
            calMonth = now.getMonth();
            renderCalendar();
            renderMonthlySummary();
        }

        // ============================================================
        // RENDER CALENDAR
        // ============================================================
        function renderCalendar() {
            const data = loadData();
            const dataMap = {};
            data.forEach(r => { dataMap[r.date] = r; });

            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            document.getElementById('cal-title').textContent = `${monthNames[calMonth]} ${calYear}`;

            const grid = document.getElementById('cal-grid');
            grid.innerHTML = '';

            const firstDay = new Date(calYear, calMonth, 1).getDay(); // 0=Sun
            const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
            const today = new Date();
            const todayStr = formatDate(today);

            // Empty cells before first day
            for (let i = 0; i < firstDay; i++) {
                const cell = document.createElement('div');
                cell.className = 'cal-cell empty';
                grid.appendChild(cell);
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const dateObj = new Date(calYear, calMonth, d);
                const dateStr = formatDate(dateObj);
                const dayOfWeek = dateObj.getDay(); // 0=Sun, 6=Sat
                const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
                const isFuture = dateObj > today && dateStr !== todayStr;
                const isToday = dateStr === todayStr;
                const rec = dataMap[dateStr];

                const cell = document.createElement('div');
                cell.classList.add('cal-cell');

                if (isWeekend) {
                    cell.classList.add('weekend');
                } else if (isFuture) {
                    cell.classList.add('future');
                } else if (rec) {
                    cell.classList.add(`status-${rec.status}`);
                    cell.addEventListener('click', () => openDetailModal(dateStr));
                } else {
                    cell.style.background = '#f8fafc';
                    cell.style.color = '#64748b';
                    cell.style.cursor = 'default';
                }

                if (isToday) cell.classList.add('today');

                cell.innerHTML = `<span>${d}</span>`;
                if (rec && !isWeekend) {
                    const dot = document.createElement('div');
                    dot.className = 'status-dot';
                    dot.style.background = rec.status === 'present' ? '#16a34a' : rec.status === 'late' ? '#d97706' : '#dc2626';
                    cell.appendChild(dot);
                }
                grid.appendChild(cell);
            }
        }

        // ============================================================
        // RENDER STATS
        // ============================================================
        function renderStats() {
            const data = loadData();
            const present = data.filter(r => r.status === 'present').length;
            const late = data.filter(r => r.status === 'late').length;
            const absent = data.filter(r => r.status === 'absent').length;
            const total = present + late + absent;
            const rate = total > 0 ? Math.round(((present + late) / total) * 100) : 0;

            document.getElementById('stat-present').textContent = present;
            document.getElementById('stat-late').textContent = late;
            document.getElementById('stat-absent').textContent = absent;
            document.getElementById('stat-rate').textContent = rate + '%';

            // Streak
            const streak = calcStreak(data);
            document.getElementById('streak-count').textContent = streak;
            document.getElementById('streak-desc').textContent = streak > 0
                ? `Anda hadir ${streak} hari berturut-turut!`
                : 'Belum ada streak kehadiran';
        }

        function calcStreak(data) {
            const workdays = data
                .filter(r => r.status === 'present' || r.status === 'late')
                .map(r => r.date)
                .sort()
                .reverse();

            if (!workdays.length) return 0;

            let streak = 0;
            let checkDate = new Date();
            checkDate.setHours(0, 0, 0, 0);

            for (let i = 0; i < 60; i++) {
                const ds = formatDate(checkDate);
                const dow = checkDate.getDay();
                // Skip weekends
                if (dow === 0 || dow === 6) {
                    checkDate.setDate(checkDate.getDate() - 1);
                    continue;
                }
                if (workdays.includes(ds)) {
                    streak++;
                } else {
                    break;
                }
                checkDate.setDate(checkDate.getDate() - 1);
            }
            return streak;
        }

        function renderMonthlySummary() {
            const data = loadData();
            const prefix = `${calYear}-${String(calMonth + 1).padStart(2, '0')}`;
            const monthly = data.filter(r => r.date.startsWith(prefix));
            const p = monthly.filter(r => r.status === 'present').length;
            const l = monthly.filter(r => r.status === 'late').length;
            const a = monthly.filter(r => r.status === 'absent').length;
            document.getElementById('month-present').textContent = `${p} hari`;
            document.getElementById('month-late').textContent = `${l} hari`;
            document.getElementById('month-absent').textContent = `${a} hari`;
            document.getElementById('month-total').textContent = `${p + l + a} hari`;
        }

        // ============================================================
        // RENDER TABLE
        // ============================================================
        function renderTable(filter = 'all') {
            const all = loadData().sort((a, b) => b.date.localeCompare(a.date));
            const data = filter === 'all' ? all : all.filter(r => r.status === filter);

            const tbody = document.getElementById('att-table-body');
            const empty = document.getElementById('att-empty');
            tbody.innerHTML = '';

            if (!data.length) {
                empty.classList.remove('hidden');
                return;
            }
            empty.classList.add('hidden');

            data.forEach(rec => {
                const tr = document.createElement('tr');
                tr.className = 'att-row';
                const statusHtml = {
                    present: `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold badge-present"><span class="material-symbols-outlined text-[13px]" style="font-variation-settings:'FILL' 1;">check_circle</span>Hadir</span>`,
                    late: `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold badge-late"><span class="material-symbols-outlined text-[13px]" style="font-variation-settings:'FILL' 1;">schedule</span>Terlambat</span>`,
                    absent: `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold badge-absent"><span class="material-symbols-outlined text-[13px]" style="font-variation-settings:'FILL' 1;">cancel</span>Tidak Masuk</span>`,
                }[rec.status];

                tr.innerHTML = `
            <td class="px-5 py-3.5 font-semibold text-on-surface text-sm whitespace-nowrap">${formatDateDisplay(rec.date)}</td>
            <td class="px-5 py-3.5 text-on-surface-variant text-sm">${rec.clockIn ? formatTime12(rec.clockIn) : '<span class="text-slate-400">--:--</span>'}</td>
            <td class="px-5 py-3.5 text-on-surface-variant text-sm">${rec.clockOut ? formatTime12(rec.clockOut) : '<span class="text-slate-400">--:--</span>'}</td>
            <td class="px-5 py-3.5">${statusHtml}</td>
            <td class="px-5 py-3.5 text-sm text-on-surface-variant max-w-[220px]">
                ${rec.reason ? `<span class="truncate block" title="${escHtml(rec.reason)}">${escHtml(rec.reason)}</span>` : '<span class="text-slate-400 italic">–</span>'}
            </td>`;
                tbody.appendChild(tr);
            });
        }

        // ============================================================
        // FILTER
        // ============================================================
        function setFilter(f) {
            currentFilter = f;
            ['all', 'present', 'late', 'absent'].forEach(id => {
                const btn = document.getElementById(`filter-${id}`);
                if (!btn) return;
                btn.className = f === id
                    ? 'px-3 py-1.5 rounded-lg font-semibold bg-white text-primary shadow-sm'
                    : 'px-3 py-1.5 rounded-lg font-semibold text-on-surface-variant hover:text-primary';
            });
            renderTable(f);
        }

        // ============================================================
        // INPUT MODAL
        // ============================================================
        let editingDate = null;

        function openInputModal(preDate = null) {
            editingDate = null;
            const modal = document.getElementById('input-modal');
            const form = document.getElementById('att-form');
            form.reset();
            clearStatusSelection();

            const today = formatDate(new Date());
            document.getElementById('att-date').value = preDate || today;
            document.getElementById('input-modal-title').textContent = 'Catat Kehadiran';

            // Pre-fill time
            const now = new Date();
            document.getElementById('att-clock-in').value = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
            document.getElementById('att-clock-out').value = '';
            document.getElementById('att-reason').value = '';
            document.getElementById('reason-required-mark').classList.add('hidden');
            document.getElementById('reason-hint').classList.add('hidden');
            document.getElementById('clock-fields').classList.remove('hidden');

            // If date already has a record → pre-fill
            if (preDate) {
                const rec = getRecord(preDate);
                if (rec) {
                    editingDate = preDate;
                    document.getElementById('input-modal-title').textContent = 'Edit Kehadiran';
                    selectStatus(rec.status);
                    document.getElementById('att-clock-in').value = rec.clockIn || '';
                    document.getElementById('att-clock-out').value = rec.clockOut || '';
                    document.getElementById('att-reason').value = rec.reason || '';
                    if (rec.status === 'absent') {
                        document.getElementById('clock-fields').classList.add('hidden');
                    }
                }
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeInputModal() {
            const modal = document.getElementById('input-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            editingDate = null;
        }

        let selectedStatus = null;
        function selectStatus(status) {
            selectedStatus = status;
            ['present', 'late', 'absent'].forEach(s => {
                const btn = document.getElementById(`status-btn-${s}`);
                btn.classList.remove('border-green-500', 'border-amber-500', 'border-red-500', 'bg-green-50', 'bg-amber-50', 'bg-red-50');
                btn.classList.add('border-outline-variant');
            });
            const colors = { present: ['border-green-500', 'bg-green-50'], late: ['border-amber-500', 'bg-amber-50'], absent: ['border-red-500', 'bg-red-50'] };
            const btn = document.getElementById(`status-btn-${status}`);
            btn.classList.remove('border-outline-variant');
            colors[status].forEach(c => btn.classList.add(c));
            // Also check radio
            document.querySelector(`input[name="status"][value="${status}"]`).checked = true;
            document.getElementById('att-status-hidden').value = status;

            // Toggle clock fields & reason required
            const clockFields = document.getElementById('clock-fields');
            const reasonMark = document.getElementById('reason-required-mark');
            const reasonHint = document.getElementById('reason-hint');
            if (status === 'absent') {
                clockFields.classList.add('hidden');
                reasonMark.classList.remove('hidden');
                reasonHint.classList.remove('hidden');
            } else if (status === 'late') {
                clockFields.classList.remove('hidden');
                reasonMark.classList.remove('hidden');
                reasonHint.classList.remove('hidden');
            } else {
                clockFields.classList.remove('hidden');
                reasonMark.classList.add('hidden');
                reasonHint.classList.add('hidden');
            }
        }

        function clearStatusSelection() {
            selectedStatus = null;
            ['present', 'late', 'absent'].forEach(s => {
                const btn = document.getElementById(`status-btn-${s}`);
                btn.classList.remove('border-green-500', 'border-amber-500', 'border-red-500', 'bg-green-50', 'bg-amber-50', 'bg-red-50');
                btn.classList.add('border-outline-variant');
                document.querySelector(`input[name="status"][value="${s}"]`).checked = false;
            });
        }

        function saveAttendance(e) {
            e.preventDefault();
            const date = document.getElementById('att-date').value;
            const clockIn = document.getElementById('att-clock-in').value;
            const clockOut = document.getElementById('att-clock-out').value;
            const reason = document.getElementById('att-reason').value.trim();
            const status = selectedStatus;

            if (!status) { showToast('Pilih status kehadiran!', 'warning'); return; }
            if (!date) { showToast('Pilih tanggal!', 'warning'); return; }
            if ((status === 'late' || status === 'absent') && !reason) {
                showToast('Alasan wajib diisi untuk status ini!', 'warning');
                document.getElementById('att-reason').focus();
                return;
            }

            const data = loadData().filter(r => r.date !== date); // remove existing
            data.push({ date, status, clockIn: status === 'absent' ? '' : (clockIn || ''), clockOut: status === 'absent' ? '' : (clockOut || ''), reason });
            saveData(data);
            closeInputModal();
            refreshAll();
            showToast('Kehadiran berhasil disimpan!', 'success');
        }

        // ============================================================
        // DETAIL MODAL
        // ============================================================
        function openDetailModal(dateStr) {
            const rec = getRecord(dateStr);
            if (!rec) return;
            detailDate = dateStr;

            const statusLabel = { present: 'Hadir', late: 'Terlambat', absent: 'Tidak Masuk' }[rec.status];
            const badgeClass = { present: 'badge-present', late: 'badge-late', absent: 'badge-absent' }[rec.status];
            const iconName = { present: 'check_circle', late: 'schedule', absent: 'cancel' }[rec.status];

            document.getElementById('detail-date-label').textContent = formatDateDisplay(dateStr);
            const badge = document.getElementById('detail-badge');
            badge.className = `inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-bold ${badgeClass}`;
            badge.innerHTML = `<span class="material-symbols-outlined text-[15px]" style="font-variation-settings:'FILL' 1;">${iconName}</span>${statusLabel}`;

            document.getElementById('detail-clock-in').textContent = rec.clockIn ? formatTime12(rec.clockIn) : '--:--';
            document.getElementById('detail-clock-out').textContent = rec.clockOut ? formatTime12(rec.clockOut) : '--:--';

            const reasonWrap = document.getElementById('detail-reason-wrap');
            if (rec.reason) {
                reasonWrap.classList.remove('hidden');
                document.getElementById('detail-reason').textContent = rec.reason;
                // Different color for absent vs late
                if (rec.status === 'absent') {
                    reasonWrap.className = 'bg-red-50 border border-red-200 rounded-xl p-3';
                    document.getElementById('detail-reason').className = 'text-sm text-red-900';
                    reasonWrap.querySelector('p').className = 'text-xs font-bold text-red-700 uppercase mb-1';
                } else {
                    reasonWrap.className = 'bg-amber-50 border border-amber-200 rounded-xl p-3';
                    document.getElementById('detail-reason').className = 'text-sm text-amber-900';
                    reasonWrap.querySelector('p').className = 'text-xs font-bold text-amber-700 uppercase mb-1';
                }
            } else {
                reasonWrap.classList.add('hidden');
            }

            const modal = document.getElementById('detail-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDetailModal() {
            document.getElementById('detail-modal').classList.add('hidden');
            document.getElementById('detail-modal').classList.remove('flex');
            detailDate = null;
        }

        function editFromDetail() {
            const d = detailDate;
            closeDetailModal();
            openInputModal(d);
        }

        function deleteFromDetail() {
            if (!detailDate) return;
            if (!confirm(`Hapus catatan kehadiran tanggal ${formatDateDisplay(detailDate)}?`)) return;
            const data = loadData().filter(r => r.date !== detailDate);
            saveData(data);
            closeDetailModal();
            refreshAll();
            showToast('Catatan berhasil dihapus.', 'success');
        }

        // ============================================================
        // TABLE ROW ACTIONS
        // ============================================================
        function handleEditRow(dateStr) { openInputModal(dateStr); }

        function handleDeleteRow(dateStr) {
            if (!confirm(`Hapus catatan kehadiran tanggal ${formatDateDisplay(dateStr)}?`)) return;
            const data = loadData().filter(r => r.date !== dateStr);
            saveData(data);
            refreshAll();
            showToast('Catatan berhasil dihapus.', 'success');
        }

        // ============================================================
        // EXPORT CSV
        // ============================================================
        function exportAttendanceCSV() {
            const data = loadData().sort((a, b) => a.date.localeCompare(b.date));
            if (!data.length) { showToast('Belum ada data untuk diekspor.', 'warning'); return; }

            const statusLabel = { present: 'Hadir', late: 'Terlambat', absent: 'Tidak Masuk' };
            const rows = [['Tanggal', 'Jam Masuk', 'Jam Keluar', 'Status', 'Alasan']];
            data.forEach(r => {
                rows.push([
                    formatDateDisplay(r.date),
                    r.clockIn || '--:--',
                    r.clockOut || '--:--',
                    statusLabel[r.status] || r.status,
                    r.reason || '-'
                ]);
            });

            const csv = rows.map(r => r.map(v => `"${String(v).replace(/"/g, '""')}"`).join(',')).join('\n');
            const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `Kehadiran_PKL_${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
            showToast('Data berhasil diekspor!', 'success');
        }

        // ============================================================
        // TOAST
        // ============================================================
        function showToast(msg, type = 'success') {
            const toast = document.getElementById('att-toast');
            const icon = document.getElementById('att-toast-icon');
            const text = document.getElementById('att-toast-msg');
            text.textContent = msg;
            icon.textContent = type === 'success' ? 'check_circle' : type === 'warning' ? 'warning' : 'info';
            toast.classList.remove('hide');
            toast.classList.add('show');
            toast.style.pointerEvents = 'none';
            setTimeout(() => {
                toast.classList.remove('show');
                toast.classList.add('hide');
            }, 2800);
        }

        // ============================================================
        // HELPERS
        // ============================================================
        function formatDate(d) {
            return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
        }

        function formatDateDisplay(ds) {
            const [y, m, d] = ds.split('-');
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const dateObj = new Date(+y, +m - 1, +d);
            return `${days[dateObj.getDay()]}, ${+d} ${months[+m - 1]} ${y}`;
        }

        function formatTime12(t) {
            if (!t) return '--:--';
            const [h, min] = t.split(':').map(Number);
            const ampm = h >= 12 ? 'PM' : 'AM';
            const h12 = h % 12 || 12;
            return `${String(h12).padStart(2, '0')}:${String(min).padStart(2, '0')} ${ampm}`;
        }

        function escHtml(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        // Close modals on backdrop click
        document.getElementById('input-modal').addEventListener('click', function (e) {
            if (e.target === this) closeInputModal();
        });
        document.getElementById('detail-modal').addEventListener('click', function (e) {
            if (e.target === this) closeDetailModal();
        });

        // ============================================================
        // REFRESH ALL
        // ============================================================
        function refreshAll() {
            renderCalendar();
            renderStats();
            renderMonthlySummary();
            renderTable(currentFilter);
        }

        // ============================================================
        // INIT
        // ============================================================
        document.addEventListener('DOMContentLoaded', () => {
            applyAdminPreviewMode();
            seedSampleData();
            initCalendar();
            refreshAll();
        });
    </script>
    <script src="lang.js"></script>
    <script src="language-ui.js"></script>
    <script src="performance.js"></script>
</body>

</html>