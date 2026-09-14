const ProjectStore = {
  _cache: [],
  _activeProjectId: localStorage.getItem('active_project_id') || null,

  // Fetch data awal dari MySQL API & mapping properti agar cocok dengan UI
  async init() {
    try {
      const res = await fetch('projects.php?action=api');
      if (!res.ok) throw new Error('Gagal mengambil data');
      const data = await res.json();
      
      // Map properti DB 'title' ke properti UI 'name'
      this._cache = data.map(item => ({
        ...item,
        name: item.title || item.name || 'Untitled Project',
        tasks: item.tasks || []
      }));
    } catch (err) {
      console.error(err);
      this._cache = [];
    }
  },

  projects() {
    return this._cache;
  },

  get(id) {
    return this._cache.find(p => String(p.id) === String(id));
  },

  getActiveProjectId() {
    return this._activeProjectId;
  },

  setActiveProjectId(id) {
    this._activeProjectId = id;
    localStorage.setItem('active_project_id', id);
  },

  async createProject(data) {
    try {
      const res = await fetch('projects.php?action=api', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          title: data.name || data.title,
          description: data.description || ''
        })
      });
      const result = await res.json();
      await this.init(); // Refresh data setelah simpan
      return result;
    } catch (err) {
      console.error(err);
      throw err;
    }
  },

  async updateProject(id, data) {
    try {
      const res = await fetch('projects.php?action=api', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          id: id,
          title: data.name || data.title,
          description: data.description || ''
        })
      });
      const result = await res.json();
      await this.init(); // Refresh data setelah update
      return result;
    } catch (err) {
      console.error(err);
      throw err;
    }
  },

  async deleteProject(id) {
    try {
      const res = await fetch(`projects.php?action=api&id=${id}`, {
        method: 'DELETE'
      });
      const result = await res.json();
      await this.init(); // Refresh data setelah hapus
      return result;
    } catch (err) {
      console.error(err);
      throw err;
    }
  },

  // Stub method untuk integrasi Kanban Task
  async createTask(projectId, taskData) {},
  async updateTask(projectId, taskId, taskData) {},
  async deleteTask(projectId, taskId) {},
  async moveTask(projectId, taskId, status) {}
};