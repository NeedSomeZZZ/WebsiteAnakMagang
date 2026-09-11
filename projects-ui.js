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
  document.getElementById('editor').onsubmit = event => {
    event.preventDefault();
    onSubmit(new FormData(event.currentTarget));
  };
}

function projectForm(project = {}) {
  return `
    <label class="block text-sm font-semibold text-slate-700">Nama Project
      <input required name="name" value="${escape(project.name)}" placeholder="Contoh: Website Anak Magang" class="mt-1 w-full rounded-lg border border-line px-3 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-primary" maxlength="80">
    </label>
    <label class="mt-4 block text-sm font-semibold text-slate-700">Deskripsi
      <textarea name="description" placeholder="Jelaskan tujuan dan cakupan project..." class="mt-1 min-h-24 w-full rounded-lg border border-line px-3 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-primary" maxlength="500">${escape(project.description)}</textarea>
    </label>
  `;
}

function taskForm(task = {}) {
  const currentStatus = task.status ? (ProjectStore.normalizeStatus ? ProjectStore.normalizeStatus(task.status) : task.status) : 'todo';
  const statusOptions = [
    ['todo', 'To Do'],
    ['inprogress', 'In Progress'],
    ['underreview', 'Under Review'],
    ['done', 'Done']
  ];
  return `
    <label class="block text-sm font-semibold text-slate-700">Judul Task
      <input required name="title" value="${escape(task.title)}" placeholder="Judul tugas..." class="mt-1 w-full rounded-lg border border-line px-3 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-primary" maxlength="100">
    </label>
    <label class="mt-3 block text-sm font-semibold text-slate-700">Deskripsi
      <textarea name="description" placeholder="Detail dan kriteria tugas..." class="mt-1 min-h-20 w-full rounded-lg border border-line px-3 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-primary" maxlength="500">${escape(task.description)}</textarea>
    </label>
    <div class="mt-3 grid grid-cols-2 gap-3">
      <label class="text-sm font-semibold text-slate-700">Priority
        <select name="priority" class="mt-1 w-full rounded-lg border border-line px-3 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-primary">
          ${['Low', 'Medium', 'High'].map(x => `<option ${task.priority === x ? 'selected' : ''}>${x}</option>`).join('')}
        </select>
      </label>
      <label class="text-sm font-semibold text-slate-700">Status
        <select name="status" class="mt-1 w-full rounded-lg border border-line px-3 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-primary">
          ${statusOptions.map(([v, n]) => `<option value="${v}" ${currentStatus === v ? 'selected' : ''}>${n}</option>`).join('')}
        </select>
      </label>
    </div>
    <div class="mt-3 grid grid-cols-2 gap-3">
      <label class="text-sm font-semibold text-slate-700">Due Date
        <input type="date" name="due_date" value="${task.due_date || ''}" class="mt-1 w-full rounded-lg border border-line px-3 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-primary">
      </label>
      <label class="text-sm font-semibold text-slate-700">Assignee
        <input name="assignee" value="${escape(task.assignee || 'Alex Doe')}" class="mt-1 w-full rounded-lg border border-line px-3 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Alex Doe">
      </label>
    </div>
  `;
}

function renderProjects() {
  const projects = ProjectStore.projects();
  app.innerHTML = `
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
      <div>
        <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-primary">Workspace</p>
        <h2 class="font-geist text-3xl font-bold text-on-surface">Daftar Project</h2>
        <p class="mt-2 text-slate-600">Kelola project dan pekerjaan tim dalam satu tempat yang terhubung langsung dengan Active Sprint Kanban.</p>
      </div>
      <div class="flex items-center gap-3">
        <a href="tasks.html" class="inline-flex items-center justify-center gap-2 rounded-lg border border-outline-variant bg-white px-4 py-3 font-bold text-primary hover:bg-surface-container-high transition-colors shadow-sm">
          <span class="material-symbols-outlined">view_kanban</span>
          Lihat Semua Tasks
        </a>
        <button onclick="newProject()" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-3 font-bold text-white hover:bg-primary-container transition-colors shadow-sm">
          <span class="material-symbols-outlined">add</span>
          Tambah Project
        </button>
      </div>
    </div>
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
      ${projects.length ? projects.map(p => {
        const completedTasks = (p.tasks || []).filter(t => ProjectStore.statusMatches ? ProjectStore.statusMatches(t.status, 'done') : t.status === 'done').length;
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
              <h3 class="mt-4 font-geist text-xl font-bold text-on-surface">${escape(p.name)}</h3>
              <p class="mt-2 min-h-12 text-sm text-slate-600">${escape(p.description || 'Belum ada deskripsi project.')}</p>
              <p class="mt-3 text-xs text-slate-500">Dibuat oleh ${escape(p.created_by)} · ${new Date(p.created_at).toLocaleDateString('id-ID')}</p>
            </div>
            <div class="mt-5 flex items-center gap-2 border-t border-line pt-4">
              <a href="tasks.html?project=${p.id}" class="flex-1 rounded-lg bg-primary px-3 py-2 text-center text-sm font-bold text-white hover:bg-primary-container transition-colors flex items-center justify-center gap-1.5 shadow-sm" title="Buka di Kanban Board">
                <span class="material-symbols-outlined text-[18px]">view_kanban</span>
                Buka Kanban
              </a>
              <a href="projects.html?project=${p.id}" class="rounded-lg border border-line px-3 py-2 text-primary hover:bg-slate-50 flex items-center justify-center transition-colors" title="Kelola Project">
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
          <p class="mt-2 text-slate-600">Buat project pertama Anda untuk memulai Kanban.</p>
        </div>
      `}
    </div>
  `;
}

function renderBoard() {
  const project = ProjectStore.get(projectId);
  if (!project) {
    location.href = 'projects.html';
    return;
  }
  const columns = [
    ['todo', 'To Do'],
    ['inprogress', 'In Progress'],
    ['underreview', 'Under Review'],
    ['done', 'Done']
  ];
  const badge = {
    Low: 'bg-slate-100 text-slate-700',
    Medium: 'bg-amber-100 text-amber-800',
    High: 'bg-red-100 text-red-800'
  };

  app.innerHTML = `
    <div class="flex items-center justify-between">
      <a href="projects.html" class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:underline">
        <span class="material-symbols-outlined text-lg">arrow_back</span>
        Semua Project
      </a>
      <a href="tasks.html?project=${project.id}" class="inline-flex items-center gap-1.5 rounded-lg bg-surface-container-high px-3 py-1.5 text-xs font-bold text-primary hover:bg-primary hover:text-white transition-colors shadow-sm">
        <span class="material-symbols-outlined text-[16px]">open_in_new</span>
        Buka di Active Sprint Kanban (tasks.html)
      </a>
    </div>
    <div class="mt-4 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
      <div>
        <h2 class="font-geist text-3xl font-bold text-on-surface">${escape(project.name)}</h2>
        <p class="mt-2 text-slate-600">${escape(project.description || 'Kanban board project.')}</p>
      </div>
      <div class="flex items-center gap-2">
        <a href="tasks.html?project=${project.id}" class="inline-flex items-center gap-1.5 rounded-lg border border-outline-variant bg-white px-4 py-2.5 font-bold text-primary hover:bg-surface-container-high transition-colors shadow-sm">
          <span class="material-symbols-outlined text-[18px]">view_kanban</span>
          Sprint Kanban
        </a>
        <button onclick="newTask()" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 font-bold text-white hover:bg-primary-container transition-colors shadow-sm">
          <span class="material-symbols-outlined text-[18px]">add</span>
          Tambah Task
        </button>
      </div>
    </div>
    <div class="mt-8 grid min-w-[1000px] grid-cols-4 gap-4 overflow-x-auto pb-4">
      ${columns.map(([status, label]) => {
        const tasks = (project.tasks || []).filter(t => ProjectStore.statusMatches ? ProjectStore.statusMatches(t.status, status) : (t.status === status));
        return `
          <section ondragover="event.preventDefault()" ondrop="dropTask(event,'${status}')" class="rounded-2xl bg-blue-50/70 p-3 flex flex-col">
            <div class="mb-3 flex items-center justify-between px-2">
              <h3 class="font-geist font-bold text-slate-800">${label}</h3>
              <span class="rounded-full bg-white px-2.5 py-0.5 text-xs font-bold text-primary shadow-sm">${tasks.length}</span>
            </div>
            <div class="min-h-32 flex-1 space-y-3">
              ${tasks.length ? tasks.map(t => `
                <article draggable="true" ondragstart="dragTask(event,'${t.id}')" onclick="showTask('${t.id}')" class="cursor-pointer rounded-xl border border-line bg-white p-4 shadow-sm hover:shadow-md transition-shadow">
                  <div class="flex items-start justify-between gap-2">
                    <h4 class="font-geist font-bold text-slate-900">${escape(t.title)}</h4>
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold ${badge[t.priority] || badge.Medium}">${t.priority}</span>
                  </div>
                  ${t.description ? `<p class="mt-2 line-clamp-2 text-sm text-slate-600">${escape(t.description)}</p>` : ''}
                  <div class="mt-4 flex justify-between text-xs text-slate-500 border-t border-slate-100 pt-2">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">person</span>${t.assignee ? escape(t.assignee) : 'Unassigned'}</span>
                    <span>${t.due_date ? new Date(t.due_date + 'T00:00').toLocaleDateString('id-ID') : 'No date'}</span>
                  </div>
                </article>
              `).join('') : `
                <div class="rounded-xl border border-dashed border-line p-5 text-center text-xs text-slate-400">Belum ada task</div>
              `}
            </div>
            <button onclick="newTask('${status}')" class="mt-3 w-full rounded-lg py-2 text-xs font-bold text-primary hover:bg-white transition-colors border border-transparent hover:border-line">
              + Tambah Task
            </button>
          </section>
        `;
      }).join('')}
    </div>
  `;
}

function render() {
  projectId ? renderBoard() : renderProjects();
  window.refreshLanguage?.();
}

function newProject() {
  formModal('Tambah Project', projectForm(), data => {
    const created = ProjectStore.createProject(Object.fromEntries(data));
    closeModal();
    render();
    notify('Project berhasil dibuat.');
  });
}

function editProject(id) {
  const project = ProjectStore.get(id);
  if (!project) return;
  formModal('Edit Project', projectForm(project), data => {
    ProjectStore.updateProject(id, Object.fromEntries(data));
    closeModal();
    render();
    notify('Project diperbarui.');
  });
}

function removeProject(id) {
  if (confirm('Hapus project ini? Semua task di dalamnya juga akan terhapus.')) {
    ProjectStore.deleteProject(id);
    render();
    notify('Project dihapus.');
  }
}

function newTask(status = 'todo') {
  formModal('Tambah Task', taskForm({ status, priority: 'Medium' }), data => {
    const v = Object.fromEntries(data);
    ProjectStore.createTask(projectId, v);
    closeModal();
    render();
    notify('Task berhasil dibuat.');
  });
}

function showTask(taskId) {
  const project = ProjectStore.get(projectId);
  const task = project?.tasks?.find(t => t.id === taskId);
  if (!task) return;

  formModal('Detail Task', taskForm(task), data => {
    ProjectStore.updateTask(projectId, taskId, Object.fromEntries(data));
    closeModal();
    render();
    notify('Task diperbarui.');
  });

  const editor = modal.querySelector('#editor');
  if (editor) {
    editor.insertAdjacentHTML('beforeend', `
      <div class="mt-4 flex justify-between items-center border-t border-line pt-3">
        <button type="button" onclick="removeTask('${taskId}')" class="text-sm font-semibold text-red-600 hover:text-red-700 flex items-center gap-1">
          <span class="material-symbols-outlined text-[16px]">delete</span> Hapus task
        </button>
        <a href="tasks.html?project=${projectId}" class="text-xs text-primary font-semibold hover:underline flex items-center gap-1">
          Buka di Sprint Kanban <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
        </a>
      </div>
    `);
  }
}

function removeTask(id) {
  if (confirm('Hapus task ini?')) {
    ProjectStore.deleteTask(projectId, id);
    closeModal();
    render();
    notify('Task dihapus.');
  }
}

function dragTask(event, id) {
  event.dataTransfer.setData('task', id);
}

function dropTask(event, status) {
  event.preventDefault();
  const taskId = event.dataTransfer.getData('task');
  if (taskId) {
    ProjectStore.moveTask(projectId, taskId, status);
    render();
    notify('Status task diperbarui.');
  }
}

render();
