<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/bootstrap.php';

$password = request()->get('password');
$confirmPassword = request()->get('confirm_password');
$username = request()->get('username');

$user = findUserByName($username);
if (!empty($user)) {
	redirect('/register.php');
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$user = createUser($username, $hashed);

redirect('/');