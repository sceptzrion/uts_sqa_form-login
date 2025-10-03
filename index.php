<?php
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/csrf.php';

// Rate limit sederhana
$MAX_ATTEMPT = 5;
$LOCK_MIN = 10;
if (!isset($_SESSION['login'])) $_SESSION['login'] = ['fail'=>0,'lock_until'=>0];

$errors = [];
$old = ['email' => ''];

// Jika sudah login, langsung ke dashboard
if (!empty($_SESSION['auth'])) {
  header('Location: dashboard/');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (time() < ($_SESSION['login']['lock_until'] ?? 0)) {
    $errors[] = "Terlalu banyak percobaan. Coba lagi nanti";
  } else {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $token    = $_POST['csrf_token'] ?? '';
    $old['email'] = $email;

    if (!csrf_verify($token)) {
      $errors[] = "Sesi tidak valid. Refresh halaman lalu coba lagi.";
      app_log('warn', 'csrf_fail', ['ip'=>$_SERVER['REMOTE_ADDR']??'']);
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Email tidak valid.";
    }
    if ($password === '' || strlen($password) < 6) {
      $errors[] = "Password minimal 6 karakter.";
    }

    if (!$errors) {
      try {
        $pdo = db();
        $stmt = $pdo->prepare("SELECT id, email, password_hash, full_name FROM users WHERE email = ?");
        $stmt->execute([$email]); // prepared → aman SQLi
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
          session_regenerate_id(true);
          $_SESSION['auth'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['full_name'],
            'login_at' => time()
          ];
          $_SESSION['login'] = ['fail'=>0,'lock_until'=>0];
          app_log('info', 'login_ok', ['uid'=>$user['id']]);
          header('Location: dashboard/');
          exit;
        } else {
          $_SESSION['login']['fail'] = ($_SESSION['login']['fail'] ?? 0) + 1;
          $remain = $MAX_ATTEMPT - $_SESSION['login']['fail'];
          app_log('warn', 'login_fail', ['email'=>$email, 'fail'=>$_SESSION['login']['fail']]);
          if ($remain <= 0) {
            $_SESSION['login']['lock_until'] = time() + ($LOCK_MIN * 60);
            $errors[] = "Terkunci $LOCK_MIN menit karena terlalu banyak percobaan";
          } else {
            $errors[] = "Email atau password salah. Sisa percobaan: $remain";
          }
        }
      } catch (Throwable $e) {
        app_log('error', 'auth_exception', ['msg'=>$e->getMessage()]);
        $errors[] = "Terjadi kesalahan server. Coba lagi";
      }
    }
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - UTS SQA</title>
  <link href="./css/output.css" rel="stylesheet">
</head>
<body>
  <div class="w-screen min-h-screen items-center justify-center flex bg-[#CFCFCF]">
    <div class="flex flex-row justify-between bg-white rounded-xl shadow-xl overflow-hidden">
        <div class="hidden md:flex items-center justify-center">
            <img src="./public/banner.jpg" alt="Login Image" class="h-150 w-auto rounded-l-xl">
        </div>
        <div class="flex items-center justify-center h-auto w-auto px-24 py-10">
            <div class="flex flex-col gap-3">
                <div class="flex flex-col gap-3">
                    <h1 class="text-4xl font-bold text-black">Login</h1>
                    <p class="text-base text-[#818181]">Please login to your account</p>
                </div>

                <?php if (!empty($errors)): ?>
                  <div class="rounded-md border border-red-200 bg-red-50 text-red-700 p-3 text-sm">
                    <?php foreach ($errors as $e): ?>
                      <div><?= htmlspecialchars($e) ?></div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>

                <form method="POST" class="mt-2">
                  <?= csrf_field() ?>
                  <fieldset class="fieldset w-xs mt-2">
                      <input type="email" name="email" value="<?= htmlspecialchars($old['email']) ?>"
                        class="input bg-white text-[#818181] border-[#D1D1D1] rounded-box mb-4"
                        placeholder="Email Address" required />

                      <input type="password" name="password" minlength="6"
                        class="input bg-white text-[#818181] border-[#D1D1D1] rounded-box"
                        placeholder="Password" required />

                      <a href="#" class="text-[#7754F6] font-normal text-end mt-1.5">Forgot password?</a>

                      <button type="submit"
                        class="btn bg-[#7754F6] text-white font-semibold border-0 shadow-none mt-3 rounded-lg w-full">
                        Login
                      </button>
                  </fieldset>
                </form>

                <p class="text-[13px] text-[#818181] mt-1 text-center">
                  Don't have an account? <a href="#" class="text-[#7754F6] font-normal">Sign up</a>
                </p>

                <!-- Kredit -->
                <div class="mt-6 text-center text-xs text-gray-500">
                  <p>UTS SQA — Modul Form Login Sederhana</p>
                  <p>Created by: <span class="font-semibold text-gray-700">Muhamad Ikhsan Rizqi Yanuar - 2210631170131</span></p>
                </div>
            </div>
        </div>
    </div>
  </div>
</body>
</html>
