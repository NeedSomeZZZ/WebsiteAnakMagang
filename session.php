<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

if (!function_exists('require_login')) {
	function require_login(): void
	{
		if (empty($_SESSION['user_logged_in'])) {
			header('Location: Login/login.php');
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