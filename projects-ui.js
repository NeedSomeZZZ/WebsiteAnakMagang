const app = document.getElementById('app'), modal = document.getElementById('modal'), toast = document.getElementById('toast');
const params = new URLSearchParams(location.search), projectId = params.get('project');
const escape = value => String(value || '').replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[char]));

function notify(message) {
  if (!toast) return;
  toast.textContent = message;
  toast.classList.remove('hidden');
  setTimeout(() => toast.classList.add('hidden'), 2800);
}

function closeModal() {
  if (!modal) return;
  modal.classList.add('hidden');
  modal.innerHTML = '';
}

function formModal(title, body, onSubmit) {
  if (!modal) return;
  modal.innerHTML = `
    <form id="editor" class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl animate-in fade-in zoom-in duration-200">
      <div class="mb-5 flex items-center justify-between border-b border-line pb-3">
        <h2 class="font-geist text-xl font-bold text-primary">${title}</h2>
        <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-700 transition-colors">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      ${body}
      <div class="mt-6 flex justify-end gap-3 border-t border-line pt-4">
        <button type="button" onclick="closeModal()" class="rounded-lg border border-line px-4 py-2 font-semibold text-slate-600 hover:bg-slate-50 transition-colors">Batal</button>
        <button type="submit" class="rounded-lg bg-primary px-5 py-2 font-semibold text-white hover:bg-primary-container transition-colors shadow-sm">Simpan</button>
      </div>
    </form>
  `;
  modal.classList.remove('hidden');
  modal.classList.add('flex');
  document.getElementById('editor').onsubmit = async event => {
    event.preventDefault();
    await onSubmit(new FormData(event.currentTarget));
  };
}

function projectForm(project = {}) {
  const nameVal = project.title || project.name || '';
  return `
    <label class="block text-sm font-semibold text-slate-700">Nama Project
      <input required name="name" value="${escape(nameVal)}" placeholder="Contoh: Website Anak Magang" class="mt-1 w-full rounded-lg border border-line px-3 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-primary" maxlength="80">
    </label>
    <label class="mt-4 block text-sm font-semibold text-slate-700">Deskripsi
      <textarea name="description" placeholder="Jelaskan tujuan dan cakupan project..." class="mt-1 min-h-24 w-full rounded-lg border border-line px-3 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-primary" maxlength="500">${escape(project.description)}</textarea>
    </label>
  `;
}

function renderProjects() {
  const projects = ProjectStore.projects();
  const activeProjectId = ProjectStore.getActiveProjectId();
  const kanbanLink = activeProjectId ? `tasks.php?project=${encodeURIComponent(activeProjectId)}` : 'tasks.php';

  app.innerHTML = `
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
      <div>
        <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-primary">Workspace</p>
        <h2 class="font-geist text-3xl font-bold text-on-surface">Daftar Project</h2>
        <p class="mt-2 text-slate-600">Kelola project dan pekerjaan tim dalam satu tempat yang terhubung langsung dengan Active Sprint Kanban.</p>
      </div>
      <div class="flex items-center gap-3">
        <a href="${kanbanLink}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-outline-variant bg-white px-4 py-3 font-bold text-primary hover:bg-surface-container-high transition-colors shadow-sm">
          <span class="material-symbols-outlined">view_kanban</span>
          Lihat Papan Kanban
        </a>
        <button onclick="newProject()" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-3 font-bold text-white hover:bg-primary-container transition-colors shadow-sm">
          <span class="material-symbols-outlined">add</span>
          Tambah Project
        </button>
      </div>
    </div>
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
      ${projects.length ? projects.map(p => {
        const title = p.title || p.name || 'Untitled';
        const completedTasks = (p.tasks || []).filter(t => t.status === 'done').length;
        return `
          <article class="card rounded-2xl border border-line bg-white p-5 hover-card-shadow flex flex-col justify-between">
            <div>
              <div class="flex items-start justify-between">
                <span class="material-symbols-outlined rounded-xl bg-blue-100 p-3 text-primary">folder</span>
                <div class="flex items-center gap-2">
                  <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">${p.tasks ? p.tasks.length : 0} tasks</span>
                  <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-bold text-green-700">${completedTasks} done</span>
                </div>
              </div>
              <h3 class="mt-4 font-geist text-xl font-bold text-on-surface">${escape(title)}</h3>
              <p class="mt-2 min-h-12 text-sm text-slate-600">${escape(p.description || 'Belum ada deskripsi project.')}</p>
              <p class="mt-3 text-xs text-slate-500">Dibuat tanggal ${new Date(p.created_at || Date.now()).toLocaleDateString('id-ID')}</p>
            </div>
            <div class="mt-5 flex items-center gap-2 border-t border-line pt-4">
              <a href="tasks.php?project=${encodeURIComponent(p.id)}" onclick="ProjectStore.setActiveProjectId('${p.id}')" class="flex-1 rounded-lg bg-primary px-3 py-2 text-center text-sm font-bold text-white hover:bg-primary-container transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">view_kanban</span>
                Buka Kanban
              </a>
              <a href="projects.php?project=${encodeURIComponent(p.id)}" class="rounded-lg border border-line px-3 py-2 text-primary hover:bg-slate-50 flex items-center justify-center transition-colors" title="Kelola Project">
                <span class="material-symbols-outlined text-[18px]">folder_open</span>
              </a>
              <button onclick="editProject('${p.id}')" aria-label="Edit" class="rounded-lg border border-line px-3 py-2 text-primary hover:bg-slate-50 transition-colors">
                <span class="material-symbols-outlined text-[18px]">edit</span>
              </button>
              <button onclick="removeProject('${p.id}')" aria-label="Hapus" class="rounded-lg border border-line px-3 py-2 text-red-600 hover:bg-red-50 transition-colors">
                <span class="material-symbols-outlined text-[18px]">delete</span>
              </button>
            </div>
          </article>
        `;
      }).join('') : `
        <div class="col-span-full rounded-2xl border border-dashed border-line bg-white p-14 text-center">
          <span class="material-symbols-outlined text-4xl text-primary">folder_off</span>
          <h3 class="mt-3 font-geist text-xl font-bold">Belum ada project</h3>
          <p class="mt-2 text-slate-600">Buat project pertama Anda untuk disimpan ke database MySQL.</p>
        </div>
      `}
    </div>
  `;
}

function renderBoard() {
  const project = ProjectStore.get(projectId);
  if (!project) {
    location.href = 'projects.php';
    return;
  }
  const title = project.title || project.name || 'Untitled';
  app.innerHTML = `
    <div class="flex items-center justify-between">
      <a href="projects.php" class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:underline">
        <span class="material-symbols-outlined text-lg">arrow_back</span>
        Semua Project
      </a>
    </div>
    <div class="mt-4">
      <h2 class="font-geist text-3xl font-bold text-on-surface">${escape(title)}</h2>
      <p class="mt-2 text-slate-600">${escape(project.description || 'Project terhubung ke MySQL.')}</p>
    </div>
  `;
}

function render() {
  projectId ? renderBoard() : renderProjects();
  window.refreshLanguage?.();
}

function newProject() {
  formModal('Tambah Project', projectForm(), async data => {
    await ProjectStore.createProject(Object.fromEntries(data));
    closeModal();
    render();
    notify('Project berhasil disimpan ke database.');
  });
}

function editProject(id) {
  const project = ProjectStore.get(id);
  if (!project) return;
  formModal('Edit Project', projectForm(project), async data => {
    await ProjectStore.updateProject(id, Object.fromEntries(data));
    closeModal();
    render();
    notify('Project diperbarui di database.');
  });
}

async function removeProject(id) {
  let confirmed = false;
  if (typeof window.showConfirmModal === 'function') {
    confirmed = await window.showConfirmModal({
      title: 'Hapus Project',
      message: 'Apakah Anda yakin ingin menghapus project ini dari database?',
      confirmText: 'Ya, Hapus',
      cancelText: 'Batal',
      type: 'danger'
    });
  } else {
    confirmed = confirm('Hapus project ini dari database?');
  }

  if (confirmed) {
    await ProjectStore.deleteProject(id);
    render();
    notify('Project berhasil dihapus.');
  }
}

// Inisialisasi utama: Ambil data dari API PHP terlebih dahulu baru render
(async function initApp() {
  app.innerHTML = '<div class="p-8 text-center text-slate-500">Memuat data dari database...</div>';
  await ProjectStore.init();
  render();
})();