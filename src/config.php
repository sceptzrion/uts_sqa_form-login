<?php
// session config (tetap seperti sebelumnya) ...
if (session_status() === PHP_SESSION_NONE) session_start();

define('APP_NAME', 'UTS SQA - Form Login');

// MySQL (Laragon)
define('DB_HOST', '127.0.0.1');       // atau 'localhost'
define('DB_PORT', '3306');            // port default MySQL/MariaDB
define('DB_NAME', 'uts_sqa_db');      // nama database yang kamu buat
define('DB_USER', 'root');            // default Laragon
define('DB_PASS', '');                // default Laragon biasanya kosong

define('LOG_PATH', __DIR__ . '/../storage/app.log');

function app_log(string $level, string $message, array $ctx = []) {
  $line = sprintf("[%s] [%s] %s %s\n", date('Y-m-d H:i:s'), strtoupper($level), $message, $ctx ? json_encode($ctx) : '');
  @file_put_contents(LOG_PATH, $line, FILE_APPEND);
}
