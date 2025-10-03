<?php
require_once __DIR__ . '/src/config.php';
app_log('info', 'logout', ['uid'=> $_SESSION['auth']['id'] ?? null]);
$_SESSION = [];
if (ini_get('session.use_cookies')) {
  $p = session_get_cookie_params();
  setcookie(session_name(), '', time()-42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();
header('Location: ./');
exit;
