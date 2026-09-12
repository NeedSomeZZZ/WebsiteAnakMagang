<!DOCTYPE html>
<html class="h-full" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Kedayweb - Certificate Verification</title>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Geist:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="shared-config.js"></script>
    <style>
        .filled-icon {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .soft-shadow {
            box-shadow: 0 10px 15px -3px rgba(30, 58, 138, 0.05), 0 4px 6px -2px rgba(30, 58, 138, 0.02);
        }
        /* Search input area */
        #search-section { display: block; }
        #result-section { display: none; }
        .verified-state #search-section { display: none; }
        .verified-state #result-section { display: block; }
    </style>
</head>
<body class="bg-background min-h-screen flex flex-col font-body-md text-body-md text-on-surface">
<?php
$nav_icon = 'school';
$nav_cta_label = 'Portal Login';
$nav_cta_href = 'index.php';
include 'partials/topnav-public.php';
?>

<!-- Main Content -->
<main id="main-content" class="flex-1 w-full max-w-container-max mx-auto px-md md:px-gutter py-xl md:py-3xl">

    <!-- Search Section (initial state) -->
    <div id="search-section" class="flex flex-col items-center justify-center min-h-[60vh]">
        <div class="w-20 h-20 rounded-full bg-primary-fixed flex items-center justify-center text-primary mb-lg">
            <span class="material-symbols-outlined filled-icon text-4xl">verified</span>
        </div>
        <h1 class="font-headline-xl text-headline-xl text-on-surface mb-md text-center">Certificate Verification</h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-lg text-center mb-xl">
            Enter a certificate ID to verify its authenticity through our secure cryptographic ledger.
        </p>
        <div class="w-full max-w-md flex gap-sm">
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input id="cert-id-input" class="w-full bg-surface-container-lowest border border-outline-variant pl-10 pr-4 py-3 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-md text-body-md text-on-surface" placeholder="e.g. IS-2024-00482" type="text" value="IS-2024-00482"/>
            </div>
            <button onclick="verifyCertificate()" class="bg-primary text-on-primary px-xl py-3 rounded-xl font-label-md text-label-md hover:bg-primary-container transition-colors active:scale-95 shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">verified_user</span>
                Verify
            </button>
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-md">Try the demo ID: <code class="bg-surface-container-high px-2 py-1 rounded font-mono text-primary font-semibold">IS-2024-00482</code></p>
    </div>

    <!-- Result Section (shown after verification) -->
    <div id="result-section" class="hidden">
        <!-- Trust Badge Banner -->
        <div class="w-full bg-surface-container-lowest rounded-2xl border border-outline-variant p-md md:p-lg mb-xl flex flex-col md:flex-row items-center justify-between gap-md soft-shadow">
            <div class="flex items-center gap-md">
                <div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center">
                    <span class="material-symbols-outlined filled-icon text-primary text-3xl">verified</span>
                </div>
                <div>
                    <h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary font-bold">Certificate Validated</h1>
                    <p class="text-on-surface-variant font-body-sm text-body-sm mt-1">This document has been securely verified via Kedayweb cryptographic ledger.</p>
                </div>
            </div>
            <div class="flex items-center gap-sm text-label-md font-label-md">
                <span class="bg-[#dcfce7] text-[#166534] px-4 py-2 rounded-full flex items-center gap-2 font-bold shadow-sm">
                    <span class="material-symbols-outlined text-[20px] filled-icon">check_circle</span>
                    Status: Verified
                </span>
            </div>
        </div>

        <!-- Bento Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
            <!-- Certificate Preview (Left) -->
            <div class="lg:col-span-7 bg-surface-container-lowest rounded-2xl border border-outline-variant overflow-hidden group hover:soft-shadow transition-shadow duration-300">
                <div class="p-4 bg-surface-container-low border-b border-outline-variant flex justify-between items-center">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Original Document Preview</span>
                    <button onclick="downloadVerifiedCertificatePDF()" class="text-primary hover:bg-primary-container hover:text-on-primary-container p-2 rounded-full transition-colors" title="Download PDF">
                        <span class="material-symbols-outlined">download</span>
                    </button>
                </div>
                <div class="p-lg bg-surface flex items-center justify-center min-h-[500px]">
                    <div class="relative w-full max-w-[600px] aspect-[1.414] bg-white shadow-lg rounded-sm border border-slate-200 overflow-hidden">
                        <img alt="Certificate Mockup" class="absolute inset-0 w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBZuMsOoiPdjq4Qxq-jP3uJdqaYa8VJgsK7OtirpN31BKnTZeqPnSgT7ZNUvbDVWRS2svcjOA6FiLionQiTGJvvlY2_1ivQsUvoKTd94zk_jyedrYgEHVjfKrHqjHz5d0D1M5XbLsep2i9tBJrRmnLPMl25HseTRfltxpVamHSU2wj3tOMuQfUsQTkgyJ8RCclIzaZcNb4LJ3NrQc70Ujx7exvmGTxWfzKP-VWvJHgts0PJggTuk-DL"/>
                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button onclick="downloadVerifiedCertificatePDF()" class="bg-primary/90 text-on-primary px-6 py-3 rounded-lg font-label-md text-label-md shadow-xl flex items-center gap-2 backdrop-blur-sm">
                                <span class="material-symbols-outlined">zoom_in</span>
                                View &amp; Download PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Details (Right) -->
            <div class="lg:col-span-5 flex flex-col gap-gutter">
                <!-- Profile Card -->
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-lg hover:soft-shadow transition-shadow duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary-fixed rounded-bl-full opacity-20 -z-10"></div>
                    <div class="flex items-start gap-md mb-xl">
                        <div class="w-16 h-16 rounded-xl bg-surface-variant flex items-center justify-center text-on-surface-variant overflow-hidden">
                            <img alt="Student Profile" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB53IjNKRugZSaGndzRTEVELKufaAD6Y6SJ5isLpTaleD8Jsv_epfJG3OgETinLz74EIwvncgD_sexY-ICg-vKw1LjgIPy3bShiE3mhVarUNSpHQatSBKjOOB6Zk37hXCTKT9FOfARASEEA0dj3vZsLuygF5K6hVSWTBZVEu2vqnTwzU8e13tJf1Ex0sz33hiX6hHLOB54qmYbc0q8efw4Sj8WlRQgI1GOYuMjTqb5i9QRCeVwZhXbV"/>
                        </div>
                        <div>
                            <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Alexia Vance</h2>
                            <p class="font-body-md text-body-md text-on-surface-variant flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-[16px]">school</span>
                                Stanford University
                            </p>
                            <p class="font-body-sm text-body-sm text-secondary mt-1">B.S. Computer Science • Class of 2025</p>
                        </div>
                    </div>
                    <div class="border-t border-outline-variant pt-md">
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-1">Final Grade</span>
                                <span class="font-headline-lg text-headline-lg text-primary font-bold">A+</span>
                            </div>
                            <div class="text-right">
                                <span class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-1">Internship Role</span>
                                <span class="font-body-md text-body-md text-on-surface font-bold">Software Engineering Intern</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics Card -->
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-lg hover:soft-shadow transition-shadow duration-300 flex-1">
                    <h3 class="font-label-md text-label-md text-on-surface uppercase tracking-wider mb-lg flex items-center gap-2 font-bold">
                        <span class="material-symbols-outlined text-primary">analytics</span>
                        Performance Metrics
                    </h3>
                    <div class="space-y-xl">
                        <!-- Technical Score -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-body-md text-body-md text-on-surface font-medium flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary text-[18px]">code</span>
                                    Technical Proficiency
                                </span>
                                <span class="font-label-md text-label-md text-primary bg-primary-fixed px-2 py-1 rounded-md font-bold">96/100</span>
                            </div>
                            <div class="w-full h-3 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-primary-container to-primary rounded-full transition-all duration-1000" style="width: 96%"></div>
                            </div>
                        </div>
                        <!-- Discipline Score -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-body-md text-body-md text-on-surface font-medium flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary text-[18px]">schedule</span>
                                    Professional Discipline
                                </span>
                                <span class="font-label-md text-label-md text-primary bg-primary-fixed px-2 py-1 rounded-md font-bold">92/100</span>
                            </div>
                            <div class="w-full h-3 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-primary-container to-primary rounded-full transition-all duration-1000" style="width: 92%"></div>
                            </div>
                        </div>
                        <!-- Attitude Score -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-body-md text-body-md text-on-surface font-medium flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary text-[18px]">groups</span>
                                    Attitude &amp; Teamwork
                                </span>
                                <span class="font-label-md text-label-md text-primary bg-primary-fixed px-2 py-1 rounded-md font-bold">98/100</span>
                            </div>
                            <div class="w-full h-3 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-primary-container to-primary rounded-full transition-all duration-1000" style="width: 98%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-xl pt-md border-t border-outline-variant">
                        <button onclick="resetVerification()" class="w-full bg-surface-bright border border-outline hover:border-primary hover:bg-surface-container-low text-primary font-label-md text-label-md py-3 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">search</span>
                            Verify Another Certificate
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="bg-surface-container-high border-t border-outline-variant w-full py-xl mt-auto">
    <div class="flex flex-col md:flex-row justify-between items-center px-gutter w-full max-w-container-max mx-auto gap-md">
        <div class="font-label-md text-label-md font-black text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined filled-icon">school</span>
            Kedayweb Platform
        </div>
        <nav class="flex flex-wrap justify-center gap-md font-body-sm text-body-sm">
            <a class="text-on-surface-variant hover:text-primary transition-colors" href="#">Privacy Policy</a>
            <a class="text-on-surface-variant hover:text-primary transition-colors" href="#">Terms of Service</a>
            
            <a class="text-primary font-semibold" href="verification.php">Verification</a>
        </nav>
        <div class="font-body-sm text-body-sm text-on-surface-variant">
            © 2024 Kedayweb Platform. Hak cipta dilindungi.
        </div>
    </div>
</footer>

<script>
    function verifyCertificate() {
        const certId = document.getElementById('cert-id-input').value.trim();
        if (!certId) {
            alert('Please enter a certificate ID.');
            return;
        }

        // Show result
        document.getElementById('search-section').style.display = 'none';
        document.getElementById('result-section').style.display = 'block';

        // Smooth scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetVerification() {
        document.getElementById('search-section').style.display = 'flex';
        document.getElementById('result-section').style.display = 'none';
        document.getElementById('cert-id-input').value = '';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Allow Enter key to trigger verification
    document.getElementById('cert-id-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') verifyCertificate();
    });

    function downloadVerifiedCertificatePDF() {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Internship_Certificate_IS-2024-00482.pdf</title>
                <meta charset="utf-8">
                <script src="https://cdn.tailwindcss.com"><\/script>
                <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
                <style>
                    @page { size: landscape; margin: 12mm; }
                    body { font-family: Inter, sans-serif; background: #fff; margin: 0; padding: 20px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                </style>
            </head>
            <body class="flex items-center justify-center min-h-screen">
                <div class="w-full max-w-4xl border-4 border-double border-blue-900/60 rounded-2xl p-12 bg-white text-center shadow-lg relative font-sans">
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <span class="text-xl font-black text-blue-900 tracking-wider uppercase">Kedayweb</span>
                    </div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500 font-bold mb-2">Certificate of Internship Completion</p>
                    <h1 class="text-4xl font-serif font-bold text-slate-900 mb-4">CERTIFICATE OF EXCELLENCE</h1>
                    <p class="text-sm text-slate-600 mb-6">This is proudly awarded to</p>
                    <h2 class="text-4xl font-bold text-blue-900 font-serif mb-2 tracking-wide">Alexia Vance</h2>
                    <div class="w-48 h-0.5 bg-blue-900 mx-auto mb-4"></div>
                    <p class="text-base text-slate-700 max-w-2xl mx-auto leading-relaxed mb-6">
                        for outstanding completion of the internship program as <strong>Software Engineering Intern</strong> at <strong>Kedayweb</strong> with an overall performance grade of <strong>A+ (96/100)</strong>.
                    </p>
                    <div class="grid grid-cols-3 gap-6 mt-10 pt-6 border-t border-slate-200 items-end">
                        <div class="text-left">
                            <p class="text-[11px] text-slate-500 uppercase font-semibold">Certificate ID</p>
                            <p class="text-sm font-mono font-bold text-slate-800">IS-2024-00482</p>
                            <p class="text-[10px] text-emerald-700 font-semibold mt-1">✓ Cryptographically Verified</p>
                        </div>
                        <div class="text-center">
                            <div class="inline-block p-2 rounded-full border-2 border-blue-900 text-blue-900 font-bold text-xs uppercase tracking-wider mb-1">
                                ★ Verified Honors ★
                            </div>
                            <p class="text-[10px] text-slate-400">Issue Date: October 22, 2024</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-serif italic font-bold text-slate-800 mb-1 border-b border-slate-300 pb-1 inline-block">Sarah Jenkins</div>
                            <p class="text-[11px] text-slate-500 uppercase font-semibold">Lead Mentor &amp; Program Director</p>
                        </div>
                    </div>
                </div>
                <script>
                    window.onload = function() {
                        setTimeout(() => {
                            window.print();
                        }, 500);
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
<script src="lang.js"></script>
<script src="language-ui.js"></script>
<script src="performance.js"></script>
</body>
</html>
