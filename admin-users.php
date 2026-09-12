<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <title>Kedayweb Admin - Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="shared-config.js"></script>
    <script src="intern-store.js"></script>
    <link rel="stylesheet" href="style.css"/>
    <style>
        .glass-card { background: rgba(255,255,255,0.8); backdrop-filter: blur(12px); }
    </style>
</head>
<body class="bg-background text-on-surface font-body-md flex h-screen overflow-hidden">
    <!-- Sidebar -->
<?php $active = 'users'; include 'partials/sidebar-admin.php'; ?>

    <!-- Main -->
    <main class="flex-1 flex flex-col md:ml-[16.5rem] h-screen overflow-y-auto">
        <header class="w-full h-16 bg-surface-container-lowest border-b border-outline-variant sticky top-0 flex justify-between items-center px-6 z-10">
            <h2 class="font-headline-lg">Manajemen Users</h2>
            <div class="flex items-center gap-2">
                <button class="p-2 rounded-full hover:bg-surface-container-low" title="Notifications">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">account_circle</span>
                    </div>
                    <span class="hidden sm:inline-block font-label-md">Alex Doe</span>
                </div>
            </div>
        </header>

        <div class="p-6 flex flex-col gap-6">
            <!-- Stats Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="glass-card rounded-xl border border-outline-variant p-4 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary-container flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                    <div>
                        <p class="text-sm text-on-surface-variant">Total Intern</p>
                        <p class="text-2xl font-bold text-on-surface" id="total-interns">0</p>
                    </div>
                </div>
                <div class="glass-card rounded-xl border border-outline-variant p-4 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                        <span class="material-symbols-outlined">check_circle</span>
                    </div>
                    <div>
                        <p class="text-sm text-on-surface-variant">Aktif Hari Ini</p>
                        <p class="text-2xl font-bold text-on-surface" id="active-today">0</p>
                    </div>
                </div>
                <div class="glass-card rounded-xl border border-outline-variant p-4 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-outlined">admin_panel_settings</span>
                    </div>
                    <div>
                        <p class="text-sm text-on-surface-variant">Admin</p>
                        <p class="text-2xl font-bold text-on-surface">1</p>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="glass-card rounded-xl border border-outline-variant p-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-headline-md">Daftar Intern</h3>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-2 top-2 text-on-surface-variant text-lg">search</span>
                        <input type="text" id="search-user" placeholder="Cari intern..." class="pl-8 pr-3 py-1.5 text-sm border border-outline-variant rounded-lg focus:outline-none focus:border-primary bg-surface-container-low"/>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-outline-variant text-on-surface-variant text-left">
                                <th class="pb-2 pr-4 font-semibold">Nama</th>
                                <th class="pb-2 pr-4 font-semibold">Role</th>
                                <th class="pb-2 pr-4 font-semibold">Departemen</th>
                                <th class="pb-2 pr-4 font-semibold">Status</th>
                                <th class="pb-2 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="users-table-body" class="divide-y divide-outline-variant">
                            <!-- Diisi dinamis -->
                        </tbody>
                    </table>
                    <p id="no-users-msg" class="hidden text-center py-8 text-on-surface-variant">Tidak ada intern yang ditemukan.</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        function renderUsers(filter = '') {
            const tbody = document.getElementById('users-table-body');
            const noMsg = document.getElementById('no-users-msg');
            const interns = (window.InternStore ? InternStore.all() : []);
            document.getElementById('total-interns').textContent = interns.length;
            document.getElementById('active-today').textContent = interns.filter(i => i.status === 'present' || i.status === 'active').length;

            const filtered = filter
                ? interns.filter(i => (i.name||'').toLowerCase().includes(filter.toLowerCase()))
                : interns;

            if (filtered.length === 0) {
                tbody.innerHTML = '';
                noMsg.classList.remove('hidden');
                return;
            }
            noMsg.classList.add('hidden');
            tbody.innerHTML = filtered.map(intern => `
                <tr class="hover:bg-surface-container-low transition-colors">
                    <td class="py-2 pr-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-primary-container text-primary flex items-center justify-center font-bold text-xs">
                                ${(intern.name||'?')[0].toUpperCase()}
                            </div>
                            <span class="font-medium">${intern.name || '-'}</span>
                        </div>
                    </td>
                    <td class="py-2 pr-4 text-on-surface-variant">${intern.role || 'Intern'}</td>
                    <td class="py-2 pr-4 text-on-surface-variant">${intern.department || '-'}</td>
                    <td class="py-2 pr-4">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold ${intern.status === 'present' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600'}">
                            ${intern.status === 'present' ? 'Aktif' : 'Tidak Aktif'}
                        </span>
                    </td>
                    <td class="py-2">
                        <a href="admin-dashboard.php?intern=${encodeURIComponent(intern.name||'')}"
                           class="text-xs text-primary font-semibold hover:underline">Lihat Dashboard</a>
                    </td>
                </tr>
            `).join('');
        }

        document.getElementById('search-user').addEventListener('input', e => renderUsers(e.target.value));
        document.addEventListener('DOMContentLoaded', () => renderUsers());
    </script>
    <script src="lang.js"></script>
    <script src="language-ui.js"></script>
</body>
</html>
