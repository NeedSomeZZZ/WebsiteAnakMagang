<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

function require_login(): void
{
	if (empty($_SESSION['user_logged_in'])) {
		header('Location: Login/login.php');
		exit;
	}
}

function current_user_name(): string
{
	return (string) ($_SESSION['username'] ?? $_SESSION['user_id'] ?? 'Pengguna');
}

function current_user_role(): string
{
	return (string) ($_SESSION['role'] ?? '');
}
