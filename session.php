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
		if (!empty($_COOKIE['cooked_role'])) {
			return (string) $_COOKIE['cooked_role'];
		}
		if (!empty($_SESSION['cooked_role'])) {
			return (string) $_SESSION['cooked_role'];
		}
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
		$role = current_user_role();
		return $role === 'superadmin' || $role === 'admin';
	}
}

if (!function_exists('cook_role')) {
	function cook_role(string $role): void
	{
		$_SESSION['cooked_role'] = $role;
		setcookie('cooked_role', $role, time() + 86400, '/');
	}
}

if (!function_exists('clear_cooked_role')) {
	function clear_cooked_role(): void
	{
		unset($_SESSION['cooked_role']);
		setcookie('cooked_role', '', time() - 3600, '/');
	}
}