<?php
require_once __DIR__ . '/../src/config.php';
if (empty($_SESSION['auth'])) {
  header('Location: ../');
  exit;
}
$user = $_SESSION['auth'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard - UTS SQA</title>
  <link href="../css/output.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
  <main class="w-full max-w-2xl mx-4">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
      <div class="md:flex">
        <div class="hidden md:block md:w-1/3 bg-cover" style="background-image:url('../public/banner.jpg'); min-height:220px;"></div>
        <div class="w-full md:w-2/3 p-8">
          <div class="flex items-start justify-between">
            <div>
              <h1 class="text-3xl font-extrabold text-green-600">Login Berhasil 🎉</h1>
              <p class="text-gray-600 mt-2">Anda sekarang sudah masuk ke sistem.</p>
            </div>
            <div>
              <a href="../logout.php" class="inline-block text-sm px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md">Logout</a>
            </div>
          </div>

          <div class="mt-6 p-4 bg-green-50 border border-green-100 rounded-lg">
            <p class="text-sm text-green-800">
              <strong>User:</strong> <?= htmlspecialchars($user['email']) ?> &middot; <span class="text-gray-600"><?= htmlspecialchars($user['name']) ?></span>
            </p>
          </div>

          <div class="mt-6 text-right text-xs text-gray-400 space-y-1">
            <p>UTS SQA — Modul Form Login Sederhana</p>
            <p>Created by: <span class="font-semibold text-gray-600">Muhamad Ikhsan Rizqi Yanuar - 2210631170131</span></p>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>
</html>
