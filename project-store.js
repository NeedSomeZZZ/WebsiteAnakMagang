const ProjectStore = (() => {
  const key = 'internspace-projects-v1';
  const uid = () => crypto.randomUUID ? crypto.randomUUID() : `${Date.now()}-${Math.random()}`;
  const now = () => new Date().toISOString();
  const load = () => JSON.parse(localStorage.getItem(key) || '[]');
  const save = projects => localStorage.setItem(key, JSON.stringify(projects));
  const seed = () => {
    if (load().length) return;
    const created = now(), id = uid();
    save([{ id, name: 'Website Anak Magang', description: 'Improve the intern portal experience.', created_by: 'Alex Doe', created_at: created, updated_at: created, tasks: [
      { id: uid(), title: 'Review onboarding flow', description: 'Audit the first-week journey and capture improvements.', status: 'todo', priority: 'Medium', assignee: 'Alex Doe', due_date: '', created_at: created, updated_at: created },
      { id: uid(), title: 'Build project dashboard', description: 'Create the project overview and key metrics.', status: 'progress', priority: 'High', assignee: 'Alex Doe', due_date: '', created_at: created, updated_at: created },
      { id: uid(), title: 'Prepare mentor feedback', description: 'Gather final notes for the sprint review.', status: 'review', priority: 'Low', assignee: 'Sarah Jenkins', due_date: '', created_at: created, updated_at: created }
    ] }]);
  };
  const projects = () => { seed(); return load(); };
  const get = id => projects().find(project => project.id === id);
  const createProject = values => { const created_at = now(); const project = { id: uid(), name: values.name.trim(), description: values.description.trim(), created_by: 'Alex Doe', created_at, updated_at: created_at, tasks: [] }; save([...projects(), project]); return project; };
  const updateProject = (id, values) => { const all = projects().map(project => project.id === id ? { ...project, name: values.name.trim(), description: values.description.trim(), updated_at: now() } : project); save(all); };
  const deleteProject = id => save(projects().filter(project => project.id !== id));
  const createTask = (projectId, values) => { const created_at = now(); const task = { id: uid(), project_id: projectId, title: values.title.trim(), description: values.description.trim(), status: values.status, priority: values.priority, assignee: values.assignee.trim(), due_date: values.due_date, created_at, updated_at: created_at }; const all = projects().map(project => project.id === projectId ? { ...project, updated_at: created_at, tasks: [...project.tasks, task] } : project); save(all); return task; };
  const updateTask = (projectId, taskId, values) => { const all = projects().map(project => project.id === projectId ? { ...project, updated_at: now(), tasks: project.tasks.map(task => task.id === taskId ? { ...task, ...values, title: values.title.trim(), description: values.description.trim(), assignee: values.assignee.trim(), updated_at: now() } : task) } : project); save(all); };
  const deleteTask = (projectId, taskId) => { const all = projects().map(project => project.id === projectId ? { ...project, updated_at: now(), tasks: project.tasks.filter(task => task.id !== taskId) } : project); save(all); };
  const moveTask = (projectId, taskId, status) => { const project = get(projectId), task = project?.tasks.find(item => item.id === taskId); if (task) updateTask(projectId, taskId, { ...task, status }); };
  return { projects, get, createProject, updateProject, deleteProject, createTask, updateTask, deleteTask, moveTask };
})();
