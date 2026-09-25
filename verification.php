<!DOCTYPE html>
<html class="h-full" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Kedayweb – Verifikasi Sertifikat Magang</title>
    <meta name="description" content="Verifikasi keaslian sertifikat magang dari Kedayweb secara online menggunakan ID sertifikat."/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Geist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="shared-config.js"></script>
    <style>
        .filled-icon { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .soft-shadow  { box-shadow: 0 10px 15px -3px rgba(30,58,138,.05), 0 4px 6px -2px rgba(30,58,138,.02); }
        #search-section { display: flex; }
        #result-section,#error-section { display: none; }
        .bar-fill { transition: width 1.2s cubic-bezier(.4,0,.2,1); }
        @keyframes fadeSlideUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
        .animate-in { animation: fadeSlideUp .5s ease both; }
        .spin { animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-background min-h-screen flex flex-col font-body-md text-body-md text-on-surface">
<?php
$nav_icon      = 'school';
$nav_cta_label = 'Portal Login';
$nav_cta_href  = 'Login/login.php';
include 'partials/topnav-public.php';
?>

<!-- Main Content -->
<main id="main-content" class="flex-1 w-full max-w-container-max mx-auto px-md md:px-gutter py-xl md:py-3xl">

    <!-- ── SEARCH SECTION (initial) ── -->
    <div id="search-section" class="flex flex-col items-center justify-center min-h-[60vh]">
        <div class="w-20 h-20 rounded-full bg-primary-fixed flex items-center justify-center text-primary mb-lg">
            <span class="material-symbols-outlined filled-icon text-4xl">verified</span>
        </div>
        <h1 class="font-headline-xl text-headline-xl text-on-surface mb-md text-center">Verifikasi Sertifikat</h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-lg text-center mb-xl">
            Masukkan ID sertifikat untuk memverifikasi keasliannya melalui sistem Kedayweb.
        </p>
        <div class="w-full max-w-md flex gap-sm">
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input id="cert-id-input"
                       class="w-full bg-surface-container-lowest border border-outline-variant pl-10 pr-4 py-3 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-md text-body-md text-on-surface uppercase"
                       placeholder="Contoh: IS-2024-001" type="text" autocomplete="off"/>
            </div>
            <button id="verify-btn" onclick="verifyCertificate()"
                    class="bg-primary text-on-primary px-xl py-3 rounded-xl font-label-md text-label-md hover:opacity-90 transition-all active:scale-95 shadow-sm flex items-center gap-2 min-w-[100px] justify-center">
                <span id="verify-icon" class="material-symbols-outlined text-[20px]">verified_user</span>
                <span id="verify-label">Verifikasi</span>
            </button>
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-md">
            Coba ID demo: <code class="bg-surface-container-high px-2 py-1 rounded font-mono text-primary font-semibold">IS-2024-001</code>
        </p>
    </div>

    <!-- ── ERROR SECTION ── -->
    <div id="error-section" class="flex flex-col items-center justify-center min-h-[50vh] animate-in">
        <div class="w-20 h-20 rounded-full bg-error-container flex items-center justify-center mb-lg">
            <span class="material-symbols-outlined text-error text-4xl filled-icon">cancel</span>
        </div>
        <h2 class="font-headline-md text-on-surface font-bold mb-sm text-center" id="error-title">Sertifikat Tidak Ditemukan</h2>
        <p class="font-body-md text-on-surface-variant text-center max-w-sm mb-xl" id="error-msg">ID sertifikat yang Anda masukkan tidak ada dalam sistem kami.</p>
        <button onclick="resetVerification()"
                class="bg-primary text-on-primary px-xl py-3 rounded-xl font-label-md hover:opacity-90 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined">arrow_back</span> Coba Lagi
        </button>
    </div>

    <!-- ── RESULT SECTION (verified) ── -->
    <div id="result-section" class="hidden animate-in">
        <!-- Trust Badge -->
        <div class="w-full bg-surface-container-lowest rounded-2xl border border-outline-variant p-md md:p-lg mb-xl flex flex-col md:flex-row items-center justify-between gap-md soft-shadow">
            <div class="flex items-center gap-md">
                <div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center">
                    <span class="material-symbols-outlined filled-icon text-primary text-3xl">verified</span>
                </div>
                <div>
                    <h1 class="font-headline-lg text-primary font-bold">Sertifikat Valid</h1>
                    <p class="text-on-surface-variant font-body-sm mt-1">Dokumen ini terverifikasi melalui sistem Kedayweb.</p>
                </div>
            </div>
            <div class="flex items-center gap-sm">
                <span class="bg-[#dcfce7] text-[#166534] px-4 py-2 rounded-full flex items-center gap-2 font-bold text-sm shadow-sm">
                    <span class="material-symbols-outlined text-[20px] filled-icon">check_circle</span>
                    Status: Aktif
                </span>
            </div>
        </div>

        <!-- Bento Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
            <!-- Certificate Preview (Left) -->
            <div class="lg:col-span-7 bg-surface-container-lowest rounded-2xl border border-outline-variant overflow-hidden group hover:soft-shadow transition-shadow duration-300">
                <div class="p-4 bg-surface-container-low border-b border-outline-variant flex justify-between items-center">
                    <span class="font-label-md text-on-surface-variant uppercase tracking-wider">Preview Sertifikat</span>
                    <button onclick="downloadCertificate()"
                            class="text-primary hover:bg-primary-container hover:text-on-primary-container p-2 rounded-full transition-colors" title="Unduh PDF">
                        <span class="material-symbols-outlined">download</span>
                    </button>
                </div>
                <!-- Certificate Design -->
                <div class="p-lg bg-surface flex items-center justify-center min-h-[500px]">
                    <div id="cert-preview" class="relative w-full max-w-[600px] bg-white shadow-lg border-[6px] border-double border-blue-900/50 rounded-xl p-10 text-center font-sans">
                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-900 via-blue-500 to-blue-900 rounded-t-lg"></div>
                        <div class="flex items-center justify-center gap-2 mb-3">
                            <span class="material-symbols-outlined filled-icon text-blue-900 text-2xl">school</span>
                            <span class="text-lg font-black text-blue-900 tracking-wider uppercase">Kedayweb</span>
                        </div>
                        <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400 font-bold mb-4">Certificate of Internship Completion</p>
                        <h2 class="text-2xl font-bold text-slate-800 mb-1">SERTIFIKAT MAGANG</h2>
                        <p class="text-xs text-slate-500 mb-5">Dengan bangga diberikan kepada</p>
                        <p id="cert-name" class="text-3xl font-bold text-blue-900 mb-1 tracking-wide">—</p>
                        <div class="w-32 h-0.5 bg-blue-900 mx-auto mb-5"></div>
                        <p id="cert-desc" class="text-sm text-slate-600 max-w-sm mx-auto leading-relaxed mb-6">—</p>
                        <div class="grid grid-cols-3 gap-4 mt-6 pt-4 border-t border-slate-200 text-left">
                            <div>
                                <p class="text-[9px] text-slate-400 uppercase font-bold">ID Sertifikat</p>
                                <p id="cert-id-display" class="text-xs font-mono font-bold text-slate-700">—</p>
                                <p class="text-[9px] text-emerald-600 font-semibold mt-1">✓ Terverifikasi</p>
                            </div>
                            <div class="text-center">
                                <div class="inline-block p-1 rounded-full border-2 border-blue-900 text-blue-900 text-[9px] font-bold uppercase tracking-wide">★ Verified ★</div>
                                <p id="cert-issue-display" class="text-[9px] text-slate-400 mt-1">—</p>
                            </div>
                            <div class="text-right">
                                <p id="cert-supervisor" class="text-xs font-bold text-slate-700 italic border-b border-slate-300 pb-1 inline-block">—</p>
                                <p class="text-[9px] text-slate-400 uppercase font-bold mt-1">Pembimbing</p>
                            </div>
                        </div>
                        <div class="absolute bottom-0 left-0 w-full h-2 bg-gradient-to-r from-blue-900 via-blue-500 to-blue-900 rounded-b-lg"></div>
                    </div>
                    <!-- Hover overlay -->
                    <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center pb-4" style="border-radius:1rem">
                        <button onclick="downloadCertificate()"
                                class="bg-primary/90 text-on-primary px-6 py-2 rounded-lg text-sm font-semibold shadow-xl flex items-center gap-2 backdrop-blur-sm">
                            <span class="material-symbols-outlined text-[18px]">download</span> Unduh PDF
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-5 flex flex-col gap-gutter">
                <!-- Profile Card -->
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-lg hover:soft-shadow transition-shadow duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary-fixed rounded-bl-full opacity-20 -z-10"></div>
                    <div class="flex items-start gap-md mb-lg">
                        <div id="avatar-circle" class="w-16 h-16 rounded-xl bg-primary flex items-center justify-center text-on-primary text-2xl font-bold flex-shrink-0">?</div>
                        <div>
                            <h2 id="res-name" class="font-headline-md text-on-surface font-bold">—</h2>
                            <p class="font-body-md text-on-surface-variant flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-[16px]">school</span>
                                <span id="res-university">—</span>
                            </p>
                            <p id="res-major" class="font-body-sm text-secondary mt-1">—</p>
                        </div>
                    </div>
                    <div class="border-t border-outline-variant pt-md grid grid-cols-2 gap-md">
                        <div>
                            <span class="block font-label-sm text-on-surface-variant uppercase mb-1 text-[11px]">Posisi Magang</span>
                            <span id="res-position" class="font-body-md text-on-surface font-bold">—</span>
                        </div>
                        <div class="text-right">
                            <span class="block font-label-sm text-on-surface-variant uppercase mb-1 text-[11px]">Nilai Akhir</span>
                            <span id="res-grade" class="font-headline-lg text-primary font-bold">—</span>
                        </div>
                        <div>
                            <span class="block font-label-sm text-on-surface-variant uppercase mb-1 text-[11px]">Periode Magang</span>
                            <span id="res-period" class="font-body-sm text-on-surface">—</span>
                        </div>
                        <div class="text-right">
                            <span class="block font-label-sm text-on-surface-variant uppercase mb-1 text-[11px]">Tanggal Terbit</span>
                            <span id="res-issue" class="font-body-sm text-on-surface">—</span>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics Card -->
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-lg hover:soft-shadow transition-shadow duration-300 flex-1">
                    <h3 class="font-label-md text-on-surface uppercase tracking-wider mb-lg flex items-center gap-2 font-bold">
                        <span class="material-symbols-outlined text-primary">analytics</span>
                        Metrik Performa
                    </h3>
                    <div class="space-y-lg">
                        <!-- Technical -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-body-md text-on-surface font-medium flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary text-[18px]">code</span>
                                    Kemampuan Teknis
                                </span>
                                <span id="score-tech-label" class="font-label-md text-primary bg-primary-fixed px-2 py-1 rounded-md font-bold">0/100</span>
                            </div>
                            <div class="w-full h-3 bg-slate-200 rounded-full overflow-hidden">
                                <div id="score-tech-bar" class="h-full bg-gradient-to-r from-primary-container to-primary rounded-full bar-fill" style="width:0%"></div>
                            </div>
                        </div>
                        <!-- Discipline -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-body-md text-on-surface font-medium flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary text-[18px]">schedule</span>
                                    Kedisiplinan
                                </span>
                                <span id="score-disc-label" class="font-label-md text-primary bg-primary-fixed px-2 py-1 rounded-md font-bold">0/100</span>
                            </div>
                            <div class="w-full h-3 bg-slate-200 rounded-full overflow-hidden">
                                <div id="score-disc-bar" class="h-full bg-gradient-to-r from-primary-container to-primary rounded-full bar-fill" style="width:0%"></div>
                            </div>
                        </div>
                        <!-- Attitude -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-body-md text-on-surface font-medium flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary text-[18px]">groups</span>
                                    Sikap & Kerjasama
                                </span>
                                <span id="score-att-label" class="font-label-md text-primary bg-primary-fixed px-2 py-1 rounded-md font-bold">0/100</span>
                            </div>
                            <div class="w-full h-3 bg-slate-200 rounded-full overflow-hidden">
                                <div id="score-att-bar" class="h-full bg-gradient-to-r from-primary-container to-primary rounded-full bar-fill" style="width:0%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-xl pt-md border-t border-outline-variant">
                        <button onclick="resetVerification()"
                                class="w-full bg-surface-bright border border-outline hover:border-primary hover:bg-surface-container-low text-primary font-label-md py-3 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">search</span>
                            Verifikasi Sertifikat Lain
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'partials/footer.php'; ?>

<script>
// Data sertifikat aktif (diisi setelah fetch)
let activeCert = null;

async function verifyCertificate() {
    const certId = document.getElementById('cert-id-input').value.trim().toUpperCase();
    if (!certId) {
        document.getElementById('cert-id-input').focus();
        return;
    }

    // Loading state
    setVerifyLoading(true);

    try {
        const res = await fetch(`certificate-api.php?action=verify&id=${encodeURIComponent(certId)}`);
        const data = await res.json();

        if (data.success) {
            activeCert = data;
            showResult(data);
        } else if (data.revoked) {
            showError('Sertifikat Dicabut', data.message);
        } else {
            showError('Sertifikat Tidak Ditemukan', data.message || 'ID sertifikat tidak ada dalam sistem.');
        }
    } catch (err) {
        showError('Terjadi Kesalahan', 'Gagal menghubungi server. Periksa koneksi internet Anda.');
    } finally {
        setVerifyLoading(false);
    }
}

function setVerifyLoading(loading) {
    const btn   = document.getElementById('verify-btn');
    const icon  = document.getElementById('verify-icon');
    const label = document.getElementById('verify-label');
    btn.disabled = loading;
    icon.textContent  = loading ? 'refresh' : 'verified_user';
    label.textContent = loading ? 'Memeriksa…' : 'Verifikasi';
    if (loading) icon.classList.add('spin'); else icon.classList.remove('spin');
}

function showResult(d) {
    // Fill cert preview
    document.getElementById('cert-name').textContent       = d.intern_name;
    document.getElementById('cert-id-display').textContent = d.certificate_id;
    document.getElementById('cert-issue-display').textContent = 'Diterbitkan: ' + d.issue_date;
    document.getElementById('cert-supervisor').textContent = d.supervisor_name || 'Pembimbing';
    document.getElementById('cert-desc').textContent =
        `atas keberhasilan menyelesaikan program magang sebagai ${d.intern_position} di Kedayweb` +
        (d.final_grade ? ` dengan nilai ${d.final_grade} (${d.avg_score}/100)` : '') + '.';

    // Fill right panel
    document.getElementById('res-name').textContent       = d.intern_name;
    document.getElementById('res-university').textContent = d.university || 'Universitas';
    document.getElementById('res-major').textContent      = d.major || '';
    document.getElementById('res-position').textContent   = d.intern_position;
    document.getElementById('res-grade').textContent      = d.final_grade || '—';
    document.getElementById('res-period').textContent     = d.start_date + ' – ' + d.end_date;
    document.getElementById('res-issue').textContent      = d.issue_date;

    // Avatar initials
    const initials = d.intern_name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
    document.getElementById('avatar-circle').textContent = initials;

    // Score bars (animate after tiny delay so CSS transition kicks in)
    setTimeout(() => {
        setBar('tech',  d.score_technical);
        setBar('disc',  d.score_discipline);
        setBar('att',   d.score_attitude);
    }, 100);

    // Switch sections
    document.getElementById('search-section').style.display = 'none';
    document.getElementById('error-section').style.display  = 'none';
    document.getElementById('result-section').style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function setBar(key, value) {
    const short = { tech: 'tech', disc: 'disc', att: 'att' };
    document.getElementById(`score-${key}-label`).textContent = value + '/100';
    document.getElementById(`score-${key}-bar`).style.width   = value + '%';
}

function showError(title, msg) {
    document.getElementById('error-title').textContent = title;
    document.getElementById('error-msg').textContent   = msg;
    document.getElementById('search-section').style.display = 'none';
    document.getElementById('result-section').style.display = 'none';
    document.getElementById('error-section').style.display  = 'flex';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetVerification() {
    document.getElementById('search-section').style.display = 'flex';
    document.getElementById('result-section').style.display = 'none';
    document.getElementById('error-section').style.display  = 'none';
    document.getElementById('cert-id-input').value = '';
    document.getElementById('cert-id-input').focus();
    activeCert = null;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Enter key support
document.getElementById('cert-id-input').addEventListener('keydown', e => {
    if (e.key === 'Enter') verifyCertificate();
});

// Auto-verify from URL param  ?id=IS-2024-001
(function() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    if (id) {
        document.getElementById('cert-id-input').value = id;
        verifyCertificate();
    }
})();

// Download / Print certificate
function downloadCertificate() {
    if (!activeCert) return;
    const d = activeCert;
    const win = window.open('', '_blank');
    win.document.write(`<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat_${d.certificate_id}.pdf</title>
    <meta charset="utf-8">
    <script src="https://cdn.tailwindcss.com"><\/script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page { size: landscape; margin: 12mm; }
        body { font-family: Inter, sans-serif; background: #fff; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-4xl border-[6px] border-double border-blue-900/60 rounded-2xl p-14 bg-white text-center shadow-lg relative">
        <div class="absolute top-0 left-0 right-0 h-3 bg-gradient-to-r from-blue-900 via-blue-500 to-blue-900 rounded-t-2xl"></div>
        <div class="flex items-center justify-center gap-2 mb-3">
            <span style="font-family:'Material Symbols Outlined';font-variation-settings:'FILL' 1" class="text-2xl text-blue-900">school</span>
            <span class="text-xl font-black text-blue-900 tracking-wider uppercase">Kedayweb</span>
        </div>
        <p class="text-[10px] uppercase tracking-[0.3em] text-slate-500 font-bold mb-2">Certificate of Internship Completion</p>
        <h1 class="text-4xl font-bold text-slate-900 mb-4" style="font-family:Georgia,serif">SERTIFIKAT MAGANG</h1>
        <p class="text-sm text-slate-600 mb-5">Dengan bangga diberikan kepada</p>
        <h2 class="text-4xl font-bold text-blue-900 mb-2 tracking-wide" style="font-family:Georgia,serif">${d.intern_name}</h2>
        <div class="w-48 h-0.5 bg-blue-900 mx-auto mb-5"></div>
        <p class="text-base text-slate-700 max-w-2xl mx-auto leading-relaxed mb-8">
            atas keberhasilan menyelesaikan program magang sebagai <strong>${d.intern_position}</strong> di <strong>Kedayweb</strong>
            ${d.university ? 'dari <strong>' + d.university + '</strong>' : ''}
            dengan nilai akhir <strong>${d.final_grade} (${d.avg_score}/100)</strong>.
        </p>
        <div class="grid grid-cols-3 gap-6 pt-6 border-t border-slate-200 items-end">
            <div class="text-left">
                <p class="text-[11px] text-slate-500 uppercase font-semibold">ID Sertifikat</p>
                <p class="text-sm font-mono font-bold text-slate-800">${d.certificate_id}</p>
                <p class="text-[10px] text-emerald-700 font-semibold mt-1">✓ Terverifikasi</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Periode: ${d.start_date} – ${d.end_date}</p>
            </div>
            <div class="text-center">
                <div class="inline-block px-3 py-1 rounded-full border-2 border-blue-900 text-blue-900 font-bold text-xs uppercase tracking-wider mb-2">★ Verified ★</div>
                <p class="text-[10px] text-slate-400">Diterbitkan: ${d.issue_date}</p>
            </div>
            <div class="text-right">
                <div class="text-sm font-bold text-slate-800 italic border-b border-slate-300 pb-1 inline-block">${d.supervisor_name || 'Pembimbing'}</div>
                <p class="text-[11px] text-slate-500 uppercase font-semibold mt-1">Pembimbing Program</p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-3 bg-gradient-to-r from-blue-900 via-blue-500 to-blue-900 rounded-b-2xl"></div>
    </div>
    <script>window.onload=function(){setTimeout(()=>window.print(),600)};<\/script>
</body>
</html>`);
    win.document.close();
}
</script>
</body>
</html>
