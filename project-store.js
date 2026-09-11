const ProjectStore = (() => {
  const key = 'internspace-projects-v1';
  const activeKey = 'internspace-active-project';
  const uid = () => (typeof crypto !== 'undefined' && crypto.randomUUID) ? crypto.randomUUID() : `task-${Date.now()}-${Math.floor(Math.random() * 1000)}`;
  const now = () => new Date().toISOString();

  const getStorage = () => {
    if (typeof localStorage !== 'undefined') return localStorage;
    if (typeof globalThis !== 'undefined' && globalThis.__mockStorage) return globalThis.__mockStorage;
    const memStore = {};
    const mock = {
      getItem: k => memStore[k] || null,
      setItem: (k, v) => { memStore[k] = String(v); },
      removeItem: k => { delete memStore[k]; }
    };
    if (typeof globalThis !== 'undefined') globalThis.__mockStorage = mock;
    return mock;
  };

  const normalizeStatus = s => {
    if (!s) return 'todo';
    const lower = String(s).toLowerCase();
    if (lower === 'progress' || lower === 'in_progress' || lower === 'inprogress') return 'inprogress';
    if (lower === 'review' || lower === 'under_review' || lower === 'underreview') return 'underreview';
    if (lower === 'done' || lower === 'completed') return 'done';
    return 'todo';
  };

  const statusMatches = (taskStatus, columnStatus) => {
    return normalizeStatus(taskStatus) === normalizeStatus(columnStatus);
  };

  const load = () => {
    try {
      const storage = getStorage();
      const parsed = JSON.parse(storage.getItem(key) || '[]');
      return Array.isArray(parsed) ? parsed : [];
    } catch (e) {
      console.error('Failed to parse project store from storage:', e);
      return [];
    }
  };

  const save = projects => {
    try {
      const storage = getStorage();
      storage.setItem(key, JSON.stringify(projects));
    } catch (e) {
      console.error('Failed to save projects to storage:', e);
    }
  };

  const seed = () => {
    const existing = load();
    if (existing.length > 0) return;

    const created = now();
    const defaultProjectId = uid();
    const initialProjects = [
      {
        id: defaultProjectId,
        name: 'Website Anak Magang',
        description: 'Improve the intern portal experience & frontend implementation.',
        created_by: 'Alex Doe',
        created_at: created,
        updated_at: created,
        tasks: [
          {
            id: 'task-1',
            project_id: defaultProjectId,
            title: 'Implement Auth Middleware',
            description: 'Set up JWT validation on all protected routes and handle token refresh logic.',
            status: 'todo',
            priority: 'High',
            assignee: 'Alex Doe',
            due_date: '2026-10-15',
            created_at: created,
            updated_at: created
          },
          {
            id: 'task-2',
            project_id: defaultProjectId,
            title: 'Design System Audit',
            description: 'Review current components against Figma designs for margin inconsistencies.',
            status: 'todo',
            priority: 'Medium',
            assignee: 'Sarah Jenkins',
            due_date: '2026-10-18',
            created_at: created,
            updated_at: created
          },
          {
            id: 'task-3',
            project_id: defaultProjectId,
            title: 'Build Kanban UI',
            description: 'Implement the drag-and-drop interface for task management using strict Tailwind tokens.',
            status: 'inprogress',
            priority: 'High',
            assignee: 'Alex Doe',
            due_date: '2026-10-20',
            created_at: created,
            updated_at: created
          },
          {
            id: 'task-4',
            project_id: defaultProjectId,
            title: 'Update Onboarding Copy',
            description: 'Revise the welcome emails to align with new brand voice guidelines.',
            status: 'underreview',
            priority: 'Low',
            assignee: 'Sarah Jenkins',
            due_date: '2026-10-22',
            created_at: created,
            updated_at: created
          }
        ]
      },
      {
        id: uid(),
        name: 'Mobile App Optimization',
        description: 'Enhance responsiveness and touch gestures for mobile intern attendance.',
        created_by: 'Sarah Jenkins',
        created_at: created,
        updated_at: created,
        tasks: [
          {
            id: uid(),
            title: 'Audit mobile navigation breakpoints',
            description: 'Check drawer interactions on small screen sizes below 640px.',
            status: 'todo',
            priority: 'Medium',
            assignee: 'Alex Doe',
            due_date: '2026-10-25',
            created_at: created,
            updated_at: created
          },
          {
            id: uid(),
            title: 'Biometric clock-in prototyping',
            description: 'Investigate WebAuthn fingerprint attendance authentication.',
            status: 'inprogress',
            priority: 'High',
            assignee: 'Alex Doe',
            due_date: '2026-10-28',
            created_at: created,
            updated_at: created
          }
        ]
      }
    ];

    save(initialProjects);
    const storage = getStorage();
    storage.setItem(activeKey, defaultProjectId);
  };

  const projects = () => {
    seed();
    return load();
  };

  const get = id => {
    if (!id) return null;
    return projects().find(project => project.id === id) || null;
  };

  const getActiveProjectId = () => {
    const list = projects();
    if (!list.length) return null;

    // Check URL parameter if in browser environment
    if (typeof location !== 'undefined' && location.search) {
      const params = new URLSearchParams(location.search);
      const paramId = params.get('project');
      if (paramId && list.some(p => p.id === paramId)) {
        setActiveProjectId(paramId);
        return paramId;
      }
    }

    // Check storage
    const storage = getStorage();
    const storedId = storage.getItem(activeKey);
    if (storedId && list.some(p => p.id === storedId)) {
      return storedId;
    }

    // Fallback to first project
    const defaultId = list[0].id;
    setActiveProjectId(defaultId);
    return defaultId;
  };

  const setActiveProjectId = id => {
    if (id) {
      const storage = getStorage();
      storage.setItem(activeKey, id);
    }
  };

  const createProject = values => {
    const created_at = now();
    const project = {
      id: uid(),
      name: (values.name || 'Untitled Project').trim(),
      description: (values.description || '').trim(),
      created_by: values.created_by || 'Alex Doe',
      created_at,
      updated_at: created_at,
      tasks: []
    };
    save([...projects(), project]);
    setActiveProjectId(project.id);
    return project;
  };

  const updateProject = (id, values) => {
    const all = projects().map(project => {
      if (project.id !== id) return project;
      return {
        ...project,
        name: (values.name || project.name).trim(),
        description: (values.description !== undefined ? values.description : project.description).trim(),
        updated_at: now()
      };
    });
    save(all);
  };
  const deleteProject = id => {
    if (!id) return;
    const targetId = String(id).trim();
    const remaining = projects().filter(project => String(project.id).trim() !== targetId);
    save(remaining);
    const storage = getStorage();
    if (storage.getItem(activeKey) === targetId) {
      storage.removeItem(activeKey);
      if (remaining.length > 0) {
        setActiveProjectId(remaining[0].id);
      }
    }
  };

  const createTask = (projectId, values) => {
    const targetProjectId = projectId || getActiveProjectId();
    const created_at = now();
    const task = {
      id: values.id || uid(),
      project_id: targetProjectId,
      title: (values.title || 'Untitled Task').trim(),
      description: (values.description || '').trim(),
      status: normalizeStatus(values.status),
      priority: values.priority || 'Medium',
      assignee: (values.assignee || 'Alex Doe').trim(),
      due_date: values.due_date || '',
      created_at,
      updated_at: created_at
    };

    const all = projects().map(project => {
      if (String(project.id).trim() !== String(targetProjectId).trim()) return project;
      return {
        ...project,
        updated_at: created_at,
        tasks: [...(project.tasks || []), task]
      };
    });
    save(all);
    return task;
  };

  const updateTask = (projectId, taskId, values) => {
    let targetProjectId = projectId;
    let targetTaskId = taskId;

    // Handle single-argument variant: updateTask(taskId, values)
    if (typeof taskId === 'object' && values === undefined) {
      values = taskId;
      targetTaskId = projectId;
      targetProjectId = null;
    }

    const taskIdStr = String(targetTaskId || '').trim();
    const projIdStr = targetProjectId ? String(targetProjectId).trim() : null;

    const all = projects().map(project => {
      if (projIdStr && String(project.id).trim() !== projIdStr) return project;

      const hasTask = (project.tasks || []).some(t => String(t.id).trim() === taskIdStr);
      if (!hasTask && projIdStr) return project;

      return {
        ...project,
        updated_at: now(),
        tasks: (project.tasks || []).map(task => {
          if (String(task.id).trim() !== taskIdStr) return task;
          return {
            ...task,
            ...values,
            title: values.title !== undefined ? String(values.title).trim() : task.title,
            description: values.description !== undefined ? String(values.description).trim() : task.description,
            status: values.status ? normalizeStatus(values.status) : task.status,
            priority: values.priority || task.priority,
            assignee: values.assignee !== undefined ? String(values.assignee).trim() : task.assignee,
            due_date: values.due_date !== undefined ? values.due_date : task.due_date,
            updated_at: now()
          };
        })
      };
    });
    save(all);
  };

  const deleteTask = (projectId, taskId) => {
    let targetProjectId = projectId;
    let targetTaskId = taskId;

    // Handle single argument call: deleteTask(taskId)
    if (!taskId && projectId) {
      targetTaskId = projectId;
      targetProjectId = null;
    }

    const taskIdStr = String(targetTaskId || '').trim();
    const projIdStr = targetProjectId ? String(targetProjectId).trim() : null;

    const all = projects().map(project => {
      if (projIdStr && String(project.id).trim() !== projIdStr) return project;

      const originalTasks = project.tasks || [];
      const remainingTasks = originalTasks.filter(t => String(t.id).trim() !== taskIdStr);

      if (remainingTasks.length !== originalTasks.length) {
        return {
          ...project,
          updated_at: now(),
          tasks: remainingTasks
        };
      }
      return project;
    });
    save(all);
  };


  const moveTask = (projectId, taskId, status) => {
    const project = get(projectId);
    const task = project?.tasks?.find(item => item.id === taskId);
    if (task) {
      updateTask(projectId, taskId, { status: normalizeStatus(status) });
    }
  };

  const getAllTasks = () => {
    const list = projects();
    const result = [];
    list.forEach(project => {
      (project.tasks || []).forEach(task => {
        result.push({
          ...task,
          projectName: project.name,
          projectId: project.id
        });
      });
    });
    return result;
  };

  return {
    projects,
    get,
    getActiveProjectId,
    setActiveProjectId,
    createProject,
    updateProject,
    deleteProject,
    createTask,
    updateTask,
    deleteTask,
    moveTask,
    getAllTasks,
    normalizeStatus,
    statusMatches
  };
})();

if (typeof window !== 'undefined') {
  window.ProjectStore = ProjectStore;
}
if (typeof globalThis !== 'undefined') {
  globalThis.ProjectStore = ProjectStore;
}
if (typeof module !== 'undefined' && module.exports) {
  module.exports = ProjectStore;
}

