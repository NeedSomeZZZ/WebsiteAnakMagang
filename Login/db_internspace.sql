-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 15 Sep 2026 pada 21.52
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `db_internspace`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `about_info`
--

CREATE TABLE `about_info` (
  `id` int NOT NULL DEFAULT '1',
  `kedayweb_title` varchar(255) NOT NULL,
  `kedayweb_description` text NOT NULL,
  `kedayweb_vision` text NOT NULL,
  `kedayweb_mission` text NOT NULL,
  `kedayweb_image` varchar(500) NOT NULL,
  `intern_title` varchar(255) NOT NULL,
  `intern_description` text NOT NULL,
  `intern_benefits` text NOT NULL,
  `intern_workflow` text NOT NULL,
  `intern_image` varchar(500) NOT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `applications`
--

CREATE TABLE `applications` (
  `id` int NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT '',
  `email` varchar(150) NOT NULL,
  `position` varchar(150) NOT NULL,
  `portfolio` varchar(255) DEFAULT '',
  `cv_file` varchar(255) DEFAULT '',
  `status` varchar(50) DEFAULT 'review',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `applications`
--

INSERT INTO `applications` (`id`, `first_name`, `last_name`, `email`, `position`, `portfolio`, `cv_file`, `status`, `created_at`) VALUES
(1, 'Akshay', 'Mirza', 'akshaymirza5@gmail.com', 'Magang Web Developer', 'a', '', 'offer', '2026-09-15 06:38:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `articles`
--

CREATE TABLE `articles` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'Aktivitas Harian',
  `author` varchar(100) NOT NULL,
  `excerpt` text,
  `content` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `events_history`
--

CREATE TABLE `events_history` (
  `id` int NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_type` varchar(100) NOT NULL DEFAULT 'Event Magang',
  `event_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `organizer` varchar(100) NOT NULL,
  `summary` text NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `gallery_activities`
--

CREATE TABLE `gallery_activities` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'Kegiatan Magang',
  `author` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `gallery_activities`
--

INSERT INTO `gallery_activities` (`id`, `title`, `category`, `author`, `description`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 'gf', 'Belajar & Workshop', 'INT-20242re-001', 'vwewv', 'uploads/gallery/1789461639_6aa90487c5544.jpg', '2026-09-15 08:40:39', '2026-09-15 15:40:39'),
(2, 'ss', 'Aktivitas Harian', 'shaliza', 'cssc', 'uploads/gallery/1789503873_6aa9a9814d979.jpeg', '2026-09-15 20:24:33', '2026-09-16 03:24:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `projects`
--

CREATE TABLE `projects` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','in_progress','completed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 0, 'Human Instrumentality', '', 'pending', '2026-09-14 11:52:11', '2026-09-15 08:00:44'),
(2, 0, 'cek 3', '', 'pending', '2026-09-14 12:58:14', '2026-09-14 12:58:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tasks`
--

CREATE TABLE `tasks` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `priority` enum('Low','Medium','High') COLLATE utf8mb4_unicode_ci DEFAULT 'Medium',
  `status` enum('todo','inprogress','underreview','done') COLLATE utf8mb4_unicode_ci DEFAULT 'todo',
  `assignee` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Alex Doe',
  `due_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tasks`
--

INSERT INTO `tasks` (`id`, `project_id`, `title`, `description`, `priority`, `status`, `assignee`, `due_date`, `created_at`, `updated_at`) VALUES
(2, 2, 'Make shinji ikari suffer', 'First step', 'High', 'done', 'Gendo ikari', '2008-03-12', '2026-09-15 07:49:17', '2026-09-16 01:46:43'),
(4, 1, 'Make shinji ikari suffer', '', 'Medium', 'todo', 'Alex Doe', NULL, '2026-09-15 07:54:49', '2026-09-15 08:01:04'),
(5, 2, 'makan', '', 'High', 'done', 'INT-2024-001', NULL, '2026-09-16 01:46:56', '2026-09-16 01:46:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `role` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `role`, `username`, `password`) VALUES
(1, 'intern', 'INT-2024-001', '12345678'),
(2, 'admin', 'admin@internspace.com', 'admin123'),
(3, 'superadmin', 'shaliza', 'mirza'),
(4, 'intern', 'fairuz', 'a'),
(5, 'intern', 'filbert', 'a');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `about_info`
--
ALTER TABLE `about_info`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `events_history`
--
ALTER TABLE `events_history`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `gallery_activities`
--
ALTER TABLE `gallery_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indeks untuk tabel `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_project_id` (`project_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `events_history`
--
ALTER TABLE `events_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `gallery_activities`
--
ALTER TABLE `gallery_activities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
