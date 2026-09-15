<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

if (!function_exists('is_logged_in')) {
	function is_logged_in(): bool
	{
		return !empty($_SESSION['user_logged_in']);
	}
}

if (!function_exists('require_login')) {
	function require_login(): void
	{
		if (!is_logged_in()) {
			$login_url = file_exists(__DIR__ . '/Login/login.php') ? 'Login/login.php' : '../Login/login.php';
			header('Location: ' . $login_url);
			exit;
		}
	}
}

if (!function_exists('current_user_name')) {
	function current_user_name(): string
	{
		return (string) ($_SESSION['username'] ?? $_SESSION['user_id'] ?? 'Pengguna');
	}
}

if (!function_exists('current_user_role')) {
	function current_user_role(): string
	{
		return (string) ($_SESSION['role'] ?? '');
	}
}

if (!function_exists('original_user_role')) {
	function original_user_role(): string
	{
		return (string) ($_SESSION['role'] ?? '');
	}
}

if (!function_exists('is_superadmin')) {
	function is_superadmin(): bool
	{
		return current_user_role() === 'superadmin';
	}
}

if (!function_exists('is_admin')) {
	function is_admin(): bool
	{
		$role = current_user_role();
		return $role === 'admin' || $role === 'superadmin';
	}
}

if (!function_exists('require_admin')) {
	function require_admin(): void
	{
		require_login();
		if (!is_admin()) {
			$dashboard_url = file_exists(__DIR__ . '/dashboard.php') ? 'dashboard.php' : '../dashboard.php';
			header('Location: ' . $dashboard_url);
			exit;
		}
	}
}

if (!function_exists('require_superadmin')) {
	function require_superadmin(): void
	{
		require_login();
		if (!is_superadmin()) {
			$admin_url = file_exists(__DIR__ . '/admin/admin-dashboard.php') ? 'admin/admin-dashboard.php' : 'admin-dashboard.php';
			header('Location: ' . $admin_url);
			exit;
		}
	}
}