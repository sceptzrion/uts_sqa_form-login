<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/config.php';

$pdo = db();

// Buat tabel users jika belum ada
$pdo->exec("
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

$email = 'ikhsan.ry@example.com';
$plain = '123456';
$hash = password_hash($plain, PASSWORD_DEFAULT);

try {
  $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, full_name) VALUES (?, ?, ?)");
  $stmt->execute([$email, $hash, 'Ikhsan RY']);
  echo "Seed OK: {$email} / {$plain}\n";
  app_log('info', 'DB seeded (mysql)', ['email'=>$email]);
} catch (PDOException $e) {
  // Jika sudah ada user, abaikan
  echo "Seed skip (kemungkinan sudah ada): " . $e->getMessage() . "\n";
}
