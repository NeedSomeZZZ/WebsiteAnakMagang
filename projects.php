<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Kedayweb - Projects</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=Material+Symbols+Outlined:FILL@0..1" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="shared-config.js"></script>
  <style>
    body { font-family: Inter, sans-serif; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 500; }
    .modal { background: rgba(11, 28, 48, 0.45); }
    .card { transition: all 0.2s ease; }
    .card:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(30, 58, 138, 0.08); }
  </style>
</head>
<body class="min-h-screen bg-canvas text-slate-900">
<?php $active = 'projects'; include 'partials/sidebar-intern.php'; ?>
  <main class="md:ml-[16.5rem]">
    <header class="flex h-16 items-center justify-between border-b border-line bg-white px-5 md:px-8">
      <h1 class="font-geist text-lg font-bold text-primary" data-i18n="nav_projects">Projects</h1>
      <span class="rounded-full border border-line px-3 py-1 text-sm font-semibold text-slate-700">Alex Doe</span>
    </header>
    <div id="app" class="mx-auto max-w-7xl p-5 md:p-8"></div>
  </main>
  <div id="modal" class="modal fixed inset-0 z-50 hidden items-center justify-center p-4"></div>
  <div id="toast" class="fixed bottom-5 right-5 z-[60] hidden rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white"></div>
  <script src="project-store.js"></script>
  <script src="projects-ui.js"></script>
  <script src="lang.js"></script>
  <script src="language-ui.js"></script>
  <script src="performance.js"></script>
</body>
</html>

