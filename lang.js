/* InternSpace – i18n Engine (Indonesian default, English toggle) */
const I18n = (() => {
  const STORAGE_KEY = 'internspace-lang';
  const DEFAULT_LANG = 'id';

  /* ── Translation dictionaries ───────────────────────────────── */
  const dict = {
    /* ─── SHARED NAV ─── */
    nav_dashboard:     { en: 'Dashboard',     id: 'Dasbor' },
    nav_projects:      { en: 'Projects',      id: 'Proyek' },
    nav_attendance:    { en: 'Attendance',     id: 'Kehadiran' },
    nav_tasks:         { en: 'Tasks',          id: 'Tugas' },
    nav_profile:       { en: 'Profile',        id: 'Profil' },
    nav_applications:  { en: 'Applications',   id: 'Lamaran' },
    nav_verification:  { en: 'Verification',   id: 'Verifikasi' },
    nav_settings:      { en: 'Settings',       id: 'Pengaturan' },
    nav_recruitment:   { en: 'Recruitment',     id: 'Rekrutmen' },
    brand_subtitle:    { en: 'Management Portal', id: 'Portal Manajemen' },
    search_placeholder:{ en: 'Search tasks, projects...', id: 'Cari tugas, proyek...' },
    btn_portal_login:  { en: 'Portal Login',   id: 'Masuk Portal' },

    /* ─── INDEX / DASHBOARD ─── */
    welcome_title:     { en: 'Welcome back, Alex', id: 'Selamat datang kembali, Alex' },
    welcome_subtitle:  { en: "Here's what's happening with your internship today.", id: 'Berikut perkembangan magang Anda hari ini.' },
    stat_attendance:   { en: 'Attendance',     id: 'Kehadiran' },
    stat_att_change:   { en: '+2% from last month', id: '+2% dari bulan lalu' },
    stat_tasks_done:   { en: 'Tasks Done',     id: 'Tugas Selesai' },
    stat_this_sprint:  { en: 'This sprint',    id: 'Sprint ini' },
    stat_active_proj:  { en: 'Active Projects', id: 'Proyek Aktif' },
    stat_cross_func:   { en: 'Cross-functional', id: 'Lintas fungsi' },
    stat_current_rank: { en: 'Current Rank',   id: 'Peringkat Saat Ini' },
    rank_senior:       { en: 'Senior Intern',  id: 'Magang Senior' },
    section_achieve:   { en: 'Latest Achievements', id: 'Pencapaian Terbaru' },
    link_view_all:     { en: 'View All',       id: 'Lihat Semua' },
    badge_speed:       { en: 'Speed Runner',   id: 'Pelari Cepat' },
    badge_speed_desc:  { en: 'Finished 5 tasks early', id: 'Menyelesaikan 5 tugas lebih awal' },
    badge_task:        { en: 'Task Master',    id: 'Ahli Tugas' },
    badge_task_desc:   { en: '100% completion rate', id: 'Tingkat penyelesaian 100%' },
    section_activity:  { en: 'Recent Activity', id: 'Aktivitas Terkini' },
    act_completed:     { en: 'Task Completed', id: 'Tugas Selesai' },
    act_completed_d:   { en: 'Submitted Q3 Marketing Assets for review.', id: 'Mengirimkan Aset Pemasaran Q3 untuk ditinjau.' },
    act_clocked_in:    { en: 'Clocked In',     id: 'Sudah Absen Masuk' },
    act_location:      { en: 'Location: Office HQ', id: 'Lokasi: Kantor Pusat' },
    shift_title:       { en: 'Current Shift',  id: 'Shift Saat Ini' },
    shift_active:      { en: 'Currently Active', id: 'Sedang Aktif' },
    shift_inactive:    { en: 'Inactive',       id: 'Tidak Aktif' },
    btn_clock_out:     { en: 'Clock Out',      id: 'Absen Keluar' },
    btn_clock_in:      { en: 'Clock In',       id: 'Absen Masuk' },
    shift_loc_label:   { en: 'Location:',      id: 'Lokasi:' },
    shift_loc_value:   { en: 'Office HQ',      id: 'Kantor Pusat' },

    /* ─── ATTENDANCE ─── */
    att_title:         { en: 'Attendance & History', id: 'Kehadiran & Riwayat' },
    att_subtitle:      { en: 'Track your daily clock-ins and monthly performance.', id: 'Pantau absensi harian dan performa bulanan Anda.' },
    btn_export:        { en: 'Export Record',  id: 'Ekspor Rekaman' },
    btn_clock_now:     { en: 'Clock In Now',   id: 'Absen Masuk Sekarang' },
    att_overview:      { en: 'Current Month Overview', id: 'Ringkasan Bulan Ini' },
    att_rate:          { en: 'Attendance Rate', id: 'Tingkat Kehadiran' },
    att_on_track:      { en: 'On track for certificate', id: 'Sesuai target sertifikat' },
    status_present:    { en: 'Present',        id: 'Hadir' },
    status_late:       { en: 'Late',           id: 'Terlambat' },
    status_absent:     { en: 'Absent',         id: 'Tidak Hadir' },
    att_days:          { en: 'days',           id: 'hari' },
    att_perfect:       { en: 'Perfect Week!',  id: 'Minggu Sempurna!' },
    att_perfect_d:     { en: 'You clocked in on time for 5 consecutive days.', id: 'Anda absen tepat waktu selama 5 hari berturut-turut.' },
    att_history:       { en: 'Detailed History', id: 'Riwayat Detail' },
    th_date:           { en: 'Date',           id: 'Tanggal' },
    th_clock_in:       { en: 'Clock In',       id: 'Absen Masuk' },
    th_clock_out:      { en: 'Clock Out',      id: 'Absen Keluar' },
    th_status:         { en: 'Status',         id: 'Status' },
    th_mentor_notes:   { en: 'Mentor Notes',   id: 'Catatan Mentor' },
    day_sun: { en: 'Sun', id: 'Min' }, day_mon: { en: 'Mon', id: 'Sen' },
    day_tue: { en: 'Tue', id: 'Sel' }, day_wed: { en: 'Wed', id: 'Rab' },
    day_thu: { en: 'Thu', id: 'Kam' }, day_fri: { en: 'Fri', id: 'Jum' },
    day_sat: { en: 'Sat', id: 'Sab' },
    att_18days:        { en: '18 days',        id: '18 hari' },
    att_2days:         { en: '2 days',         id: '2 hari' },
    att_1day:          { en: '1 day',          id: '1 hari' },

    /* ─── TASKS ─── */
    tasks_title:       { en: 'Active Sprint',  id: 'Sprint Aktif' },
    tasks_subtitle:    { en: 'Week 4: Frontend Implementation', id: 'Minggu 4: Implementasi Frontend' },
    btn_new_task:      { en: 'New Task',       id: 'Tugas Baru' },
    col_todo:          { en: 'To Do',          id: 'Harus Dikerjakan' },
    col_progress:      { en: 'In Progress',    id: 'Sedang Berjalan' },
    col_review:        { en: 'Under Review',   id: 'Sedang Ditinjau' },
    col_done:          { en: 'Done',           id: 'Selesai' },
    task_start:        { en: 'Start Task',     id: 'Mulai Tugas' },
    task_move_back:    { en: 'Move Back',      id: 'Pindah Kembali' },
    task_submit_review:{ en: 'Submit Review',  id: 'Kirim Tinjauan' },
    task_request_chg:  { en: 'Request Changes', id: 'Minta Perubahan' },
    task_approve:      { en: 'Approve & Done', id: 'Setujui & Selesai' },
    task_completed_xp: { en: 'Task completed! +15 XP Gained!', id: 'Tugas selesai! +15 XP Diperoleh!' },
    task_enter_title:  { en: 'Enter task title:', id: 'Masukkan judul tugas:' },
    task_enter_desc:   { en: 'Enter task description:', id: 'Masukkan deskripsi tugas:' },
    task_no_desc:      { en: 'No description provided.', id: 'Tidak ada deskripsi.' },
    task_master_badge: { en: 'Task Master Badge', id: 'Lencana Ahli Tugas' },
    task_xp_next:      { en: '+50 XP to Next Badge!', id: '+50 XP ke Lencana Berikutnya!' },
    task_completed_oct:{ en: 'Completed Oct 22', id: 'Selesai 22 Okt' },

    /* ─── PROFILE ─── */
    profile_title:     { en: 'Digital Intern Profile', id: 'Profil Magang Digital' },
    btn_download_cv:   { en: 'Download Resume', id: 'Unduh Resume' },
    profile_mentor:    { en: 'Assigned Mentor', id: 'Mentor yang Ditugaskan' },
    profile_this_mon:  { en: 'This Month',     id: 'Bulan Ini' },
    profile_tasks_c:   { en: 'Tasks Completed', id: 'Tugas Selesai' },
    profile_avg_time:  { en: 'Avg Time',       id: 'Waktu Rata-rata' },
    profile_per_task:  { en: 'Per Task Assignment', id: 'Per Penugasan' },
    profile_mastery:   { en: 'Overall Mastery', id: 'Penguasaan Keseluruhan' },
    profile_tech:      { en: 'Tech Stack & Tools', id: 'Stack Teknologi & Alat' },

    /* ─── APPLICATIONS ─── */
    apps_section:      { en: 'Career center',  id: 'Pusat Karir' },
    apps_title:        { en: 'My Applications', id: 'Lamaran Saya' },
    apps_subtitle:     { en: 'Follow every opportunity from application to offer.', id: 'Ikuti setiap peluang dari lamaran hingga tawaran.' },
    btn_explore:       { en: 'Explore open roles', id: 'Jelajahi posisi terbuka' },
    apps_active:       { en: 'Active',         id: 'Aktif' },
    apps_in_progress:  { en: 'Applications in progress', id: 'Lamaran sedang diproses' },
    apps_interviews:   { en: 'Interviews',     id: 'Wawancara' },
    apps_next_intv:    { en: 'Next interview this week', id: 'Wawancara berikutnya minggu ini' },
    apps_offers:       { en: 'Offers',         id: 'Tawaran' },
    apps_congrats:     { en: 'Congratulations, Alex!', id: 'Selamat, Alex!' },
    apps_pipeline:     { en: 'Application pipeline', id: 'Alur Lamaran' },
    apps_pipeline_d:   { en: 'Your latest application activity and next steps.', id: 'Aktivitas lamaran terbaru dan langkah selanjutnya.' },
    btn_all_status:    { en: 'All statuses',   id: 'Semua status' },
    tab_all:           { en: 'All applications', id: 'Semua lamaran' },
    tab_interview:     { en: 'Interviewing',   id: 'Dalam Wawancara' },
    tab_review:        { en: 'Under review',   id: 'Sedang Ditinjau' },
    tab_offer:         { en: 'Offer',          id: 'Tawaran' },
    btn_view_details:  { en: 'View details',   id: 'Lihat detail' },
    btn_review_offer:  { en: 'Review offer',   id: 'Tinjau tawaran' },
    apps_empty:        { en: 'No applications match this filter.', id: 'Tidak ada lamaran yang sesuai filter.' },
    badge_intv_sched:  { en: 'Interview scheduled', id: 'Wawancara dijadwalkan' },
    badge_under_rev:   { en: 'Under review',   id: 'Sedang ditinjau' },
    badge_offer_recv:  { en: 'Offer received', id: 'Tawaran diterima' },
    apps_next_step:    { en: 'Next step:',     id: 'Langkah selanjutnya:' },
    apps_update:       { en: 'Update:',        id: 'Pembaruan:' },
    apps_action_req:   { en: 'Action required:', id: 'Tindakan diperlukan:' },
    apps_intv_detail:  { en: 'Portfolio interview with Sarah Jenkins on Oct 25 at 10:00 AM.', id: 'Wawancara portfolio dengan Sarah Jenkins pada 25 Okt pukul 10:00.' },
    apps_review_detail:{ en: 'Your technical portfolio is being reviewed by the hiring team.', id: 'Portfolio teknis Anda sedang ditinjau oleh tim rekrutmen.' },
    apps_review_wait:  { en: 'Application received. Expect an update within 5 business days.', id: 'Lamaran diterima. Harap tunggu pembaruan dalam 5 hari kerja.' },
    apps_offer_detail: { en: 'Review and respond to your offer by Oct 27, 2024.', id: 'Tinjau dan tanggapi tawaran Anda sebelum 27 Oktober 2024.' },
    apps_applied_oct18:{ en: 'TechCorp · San Francisco, CA · Applied Oct 18, 2024', id: 'TechCorp · San Francisco, CA · Dilamar 18 Okt 2024' },
    apps_applied_oct15:{ en: 'Nova Studio · Remote · Applied Oct 15, 2024', id: 'Nova Studio · Remote · Dilamar 15 Okt 2024' },
    apps_applied_oct11:{ en: 'Atlas Collective · New York, NY · Applied Oct 11, 2024', id: 'Atlas Collective · New York, NY · Dilamar 11 Okt 2024' },
    apps_applied_sep28:{ en: 'Pixel Ventures · Hybrid · Applied Sep 28, 2024', id: 'Pixel Ventures · Hybrid · Dilamar 28 Sep 2024' },

    /* ─── VERIFICATION ─── */
    verify_title:      { en: 'Certificate Verification', id: 'Verifikasi Sertifikat' },
    verify_desc:       { en: 'Enter a certificate ID to verify its authenticity through our secure cryptographic ledger.', id: 'Masukkan ID sertifikat untuk memverifikasi keasliannya melalui buku besar kriptografi kami yang aman.' },
    btn_verify:        { en: 'Verify',         id: 'Verifikasi' },
    verify_try_demo:   { en: 'Try the demo ID:', id: 'Coba ID demo:' },
    verify_validated:  { en: 'Certificate Validated', id: 'Sertifikat Tervalidasi' },
    verify_valid_d:    { en: 'This document has been securely verified via InternSpace cryptographic ledger.', id: 'Dokumen ini telah diverifikasi secara aman melalui buku besar kriptografi InternSpace.' },
    verify_status:     { en: 'Status: Verified', id: 'Status: Terverifikasi' },
    verify_preview:    { en: 'Original Document Preview', id: 'Pratinjau Dokumen Asli' },
    verify_full_size:  { en: 'View Full Size', id: 'Lihat Ukuran Penuh' },
    verify_final:      { en: 'Final Grade',    id: 'Nilai Akhir' },
    verify_role:       { en: 'Internship Role', id: 'Peran Magang' },
    verify_perf:       { en: 'Performance Metrics', id: 'Metrik Kinerja' },
    verify_tech_prof:  { en: 'Technical Proficiency', id: 'Kemahiran Teknis' },
    verify_prof_disc:  { en: 'Professional Discipline', id: 'Disiplin Profesional' },
    verify_attitude:   { en: 'Attitude & Teamwork', id: 'Sikap & Kerja Tim' },
    verify_another:    { en: 'Verify Another Certificate', id: 'Verifikasi Sertifikat Lain' },
    verify_alert_empty:{ en: 'Please enter a certificate ID.', id: 'Silakan masukkan ID sertifikat.' },
    footer_privacy:    { en: 'Privacy Policy',  id: 'Kebijakan Privasi' },
    footer_terms:      { en: 'Terms of Service', id: 'Syarat Layanan' },
    footer_copy:       { en: '© 2024 InternSpace Platform. All rights reserved.', id: '© 2024 Platform InternSpace. Hak cipta dilindungi.' },

    /* ─── RECRUITMENT ─── */
    recruit_careers:   { en: 'Careers',        id: 'Karir' },
    recruit_title:     { en: 'Join Our Team as an Intern', id: 'Bergabunglah dengan Tim Kami sebagai Magang' },
    recruit_subtitle:  { en: 'Kickstart your career with hands-on experience, mentorship from industry leaders, and the opportunity to work on projects that shape the future of tech.', id: 'Mulai karir Anda dengan pengalaman langsung, bimbingan dari pemimpin industri, dan kesempatan bekerja pada proyek yang membentuk masa depan teknologi.' },
    recruit_positions: { en: 'Open Positions', id: 'Posisi Terbuka' },
    recruit_pos_d:     { en: 'Find the perfect role to accelerate your growth.', id: 'Temukan peran yang tepat untuk mempercepat perkembangan Anda.' },
    btn_apply_now:     { en: 'Apply Now',      id: 'Lamar Sekarang' },
    recruit_open_roles:{ en: 'Open Roles',     id: 'Posisi Terbuka' },
    recruit_apply:     { en: 'Apply',          id: 'Melamar' },
    recruit_web_dev:   { en: 'Web Developer Intern', id: 'Magang Pengembang Web' },
    recruit_web_d:     { en: 'Join our frontend team to build performant, accessible, and beautiful user interfaces using modern web technologies. You\'ll work closely with senior engineers on core product features.', id: 'Bergabunglah dengan tim frontend kami untuk membangun antarmuka pengguna yang cepat, aksesibel, dan indah menggunakan teknologi web modern. Anda akan bekerja erat dengan insinyur senior pada fitur produk utama.' },
    recruit_uiux:      { en: 'UI/UX Designer Intern', id: 'Magang Desainer UI/UX' },
    recruit_uiux_d:    { en: 'Help shape the future of our product experience. You will assist in user research, wireframing, and creating high-fidelity prototypes that define our brand aesthetic.', id: 'Bantu membentuk masa depan pengalaman produk kami. Anda akan membantu dalam riset pengguna, wireframing, dan membuat prototipe fidelitas tinggi yang mendefinisikan estetika merek kami.' },
    recruit_engineering:{ en: 'Engineering',   id: 'Teknik' },
    recruit_design:    { en: 'Design',         id: 'Desain' },
    recruit_submit:    { en: 'Submit Your Application', id: 'Kirim Lamaran Anda' },
    recruit_submit_d:  { en: 'Complete the steps below to apply for your desired internship role.', id: 'Selesaikan langkah-langkah di bawah ini untuk melamar peran magang yang Anda inginkan.' },
    recruit_step1:     { en: 'Personal Info',  id: 'Info Pribadi' },
    recruit_step1_d:   { en: 'Basic contact details', id: 'Detail kontak dasar' },
    recruit_step2:     { en: 'Education & Experience', id: 'Pendidikan & Pengalaman' },
    recruit_step2_d:   { en: 'University and background', id: 'Universitas dan latar belakang' },
    recruit_step3:     { en: 'Documents',      id: 'Dokumen' },
    recruit_step3_d:   { en: 'CV and Portfolio Link', id: 'CV dan Tautan Portfolio' },
    recruit_fname:     { en: 'First Name',     id: 'Nama Depan' },
    recruit_lname:     { en: 'Last Name',      id: 'Nama Belakang' },
    recruit_email:     { en: 'Email Address',  id: 'Alamat Email' },
    recruit_role:      { en: 'Role Applying For', id: 'Peran yang Dilamar' },
    recruit_select:    { en: 'Select a role...', id: 'Pilih peran...' },
    recruit_cv:        { en: 'Resume / CV',    id: 'Resume / CV' },
    recruit_upload:    { en: 'Click to upload or drag and drop', id: 'Klik untuk mengunggah atau seret dan lepas' },
    recruit_upload_f:  { en: 'PDF, DOCX up to 10MB', id: 'PDF, DOCX hingga 10MB' },
    recruit_portfolio: { en: 'Portfolio Link (Optional)', id: 'Tautan Portfolio (Opsional)' },
    recruit_submit_btn:{ en: 'Submit Application', id: 'Kirim Lamaran' },
    recruit_success:   { en: 'Application submitted successfully!', id: 'Lamaran berhasil dikirim!' },
    recruit_fill:      { en: 'Please fill in all required fields.', id: 'Silakan lengkapi semua kolom wajib.' },
    recruit_touch:     { en: "We'll be in touch soon!", id: 'Kami akan segera menghubungi Anda!' },
    recruit_remote:    { en: 'Remote / Hybrid', id: 'Remote / Hybrid' },

    /* ─── PROJECTS (dynamic – used by projects-ui.js) ─── */
    proj_workspace:    { en: 'Workspace',      id: 'Ruang Kerja' },
    proj_list_title:   { en: 'Project List',   id: 'Daftar Proyek' },
    proj_list_desc:    { en: 'Manage projects and team work in one place.', id: 'Kelola proyek dan pekerjaan tim dalam satu tempat.' },
    btn_add_project:   { en: 'Add Project',    id: 'Tambah Proyek' },
    btn_open_kanban:   { en: 'Open Kanban',    id: 'Buka Kanban' },
    proj_empty_title:  { en: 'No projects yet', id: 'Belum ada proyek' },
    proj_empty_desc:   { en: 'Create your first project to start Kanban.', id: 'Buat proyek pertama Anda untuk memulai Kanban.' },
    proj_created_by:   { en: 'Created by',     id: 'Dibuat oleh' },
    proj_tasks_count:  { en: 'tasks',          id: 'tugas' },
    proj_all:          { en: 'All Projects',   id: 'Semua Proyek' },
    proj_no_desc:      { en: 'No description yet.', id: 'Belum ada deskripsi.' },
    proj_mgmt:         { en: 'Project Management', id: 'Manajemen Proyek' },
    modal_add_proj:    { en: 'Add Project',    id: 'Tambah Proyek' },
    modal_edit_proj:   { en: 'Edit Project',   id: 'Edit Proyek' },
    modal_add_task:    { en: 'Add Task',       id: 'Tambah Tugas' },
    modal_task_detail: { en: 'Task Detail',    id: 'Detail Tugas' },
    label_proj_name:   { en: 'Project Name',   id: 'Nama Proyek' },
    label_description: { en: 'Description',    id: 'Deskripsi' },
    label_task_title:  { en: 'Task Title',     id: 'Judul Tugas' },
    label_priority:    { en: 'Priority',       id: 'Prioritas' },
    label_status:      { en: 'Status',         id: 'Status' },
    label_due_date:    { en: 'Due Date',       id: 'Tenggat Waktu' },
    label_assignee:    { en: 'Assignee',       id: 'Ditugaskan Kepada' },
    btn_save:          { en: 'Save',           id: 'Simpan' },
    btn_cancel:        { en: 'Cancel',         id: 'Batal' },
    btn_delete_task:   { en: 'Delete task',    id: 'Hapus tugas' },
    btn_add_task:      { en: '+ Add Task',     id: '+ Tambah Tugas' },
    kanban_todo:       { en: 'To Do',          id: 'Harus Dikerjakan' },
    kanban_progress:   { en: 'In Progress',    id: 'Sedang Berjalan' },
    kanban_review:     { en: 'Review',         id: 'Peninjauan' },
    kanban_done:       { en: 'Done',           id: 'Selesai' },
    task_unassigned:   { en: 'Unassigned',     id: 'Belum ditugaskan' },
    task_no_due:       { en: 'No due date',    id: 'Tanpa tenggat' },
    task_no_tasks:     { en: 'No tasks yet',   id: 'Belum ada tugas' },
    toast_proj_created:{ en: 'Project created successfully.', id: 'Proyek berhasil dibuat.' },
    toast_proj_updated:{ en: 'Project updated.', id: 'Proyek diperbarui.' },
    toast_proj_deleted:{ en: 'Project deleted.', id: 'Proyek dihapus.' },
    toast_task_created:{ en: 'Task created successfully.', id: 'Tugas berhasil dibuat.' },
    toast_task_updated:{ en: 'Task updated.',  id: 'Tugas diperbarui.' },
    toast_task_deleted:{ en: 'Task deleted.',  id: 'Tugas dihapus.' },
    toast_task_status: { en: 'Task status updated.', id: 'Status tugas diperbarui.' },
    confirm_del_proj:  { en: 'Delete this project? All tasks will also be deleted.', id: 'Hapus proyek ini? Semua tugas di dalamnya juga akan terhapus.' },
    confirm_del_task:  { en: 'Delete this task?', id: 'Hapus tugas ini?' },
    placeholder_opt:   { en: 'Optional',       id: 'Opsional' },
    kanban_board_proj: { en: 'Kanban board project.', id: 'Kanban board proyek.' },

    /* ─── LANGUAGE TOGGLE ─── */
    lang_label:        { en: 'Language',       id: 'Bahasa' },
  };

  /* ── Core functions ─────────────────────────────────────────── */
  function getLang() {
    return localStorage.getItem(STORAGE_KEY) || DEFAULT_LANG;
  }

  function setLang(code) {
    localStorage.setItem(STORAGE_KEY, code);
    applyLang();
  }

  function toggleLang() {
    setLang(getLang() === 'en' ? 'id' : 'en');
  }

  function t(key) {
    const entry = dict[key];
    if (!entry) return key;
    return entry[getLang()] || entry[DEFAULT_LANG] || key;
  }

  function applyLang() {
    const lang = getLang();
    document.documentElement.lang = lang;

    document.querySelectorAll('[data-i18n]').forEach(el => {
      const key = el.getAttribute('data-i18n');
      const val = t(key);
      if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
        el.placeholder = val;
      } else {
        el.textContent = val;
      }
    });

    /* Update toggle button labels */
    document.querySelectorAll('[data-lang-toggle]').forEach(btn => {
      btn.textContent = lang === 'en' ? 'ID' : 'EN';
    });
  }

  /* Auto-apply on DOM ready */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applyLang);
  } else {
    applyLang();
  }

  return { t, getLang, setLang, toggleLang, applyLang, dict };
})();
