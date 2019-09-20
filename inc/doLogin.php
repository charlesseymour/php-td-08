<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/bootstrap.php';

$user = findUserByName(request()->get('username'));
if (empty($user)) {
	$session->getFlashBag()->add('error', 'Username was not found');
	redirect('/login.php');
}

if (!password_verify(request()->get('password'), $user['password'])) {
	redirect('/login.php');
}

$expTime = time() + 3600;

$jwt = \Firebase\JWT\JWT::encode([
	'iss' => request()->getBaseUrl(),
	'sub' => "{$user['id']}",
	'exp' => $expTime,
	'iat' => time(),
	'nbf' => time()
], getenv("SECRET_KEY"), 'HS256');

$accessToken = new Symfony\Component\HttpFoundation\Cookie('access_token', $jwt, $expTime, '/', 
															getenv('COOKIE_DOMAIN'));
															
redirect('/', ['cookies' => [$accessToken]]);







