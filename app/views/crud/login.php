<?php

// Redirect if already logged in
if (!empty($_SESSION['authenticated'])) {
  if (($_SESSION['role'] ?? '') === 'admin') {
    header('Location: ' . url('crud'));
  } else {
    header('Location: ' . url('view'));
  }
    exit;
}

$warning = '';
$name = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name === '' || $password === '') {
        $warning = 'Please enter both username and password.';
    } elseif ($name === 'Borris' && $password === 'admin123') {
        // Admin login
        $_SESSION['authenticated'] = true;
        $_SESSION['user_name'] = $name;
        $_SESSION['role'] = 'admin';
        header('Location: ' . url('crud'));
        exit;
    } else {
        // Check database for regular users
        $db = db();
        $user = $db->table('borris_bcm_users')->where('bcm_username', $name)->get();
        
        if ($user && password_verify($password, $user['bcm_password'])) {
            $_SESSION['authenticated'] = true;
            $_SESSION['user_name'] = $user['bcm_first_name'] . ' ' . $user['bcm_last_name'];
            $_SESSION['role'] = 'user';
            header('Location: ' . url('view'));
            exit;
        } else {
            $warning = 'Invalid username or password.';
        }
    }
}
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?= url('public/css/style.css?v=20260331b') ?>">

<div class="min-h-screen flex items-center justify-center py-20">
  <div class="max-w-md w-full px-4">
    <div class="glass fade-in p-6">
      <h2 class="text-2xl font-semibold mb-4 text-center">Welcome <?= esc($_SESSION['user_name'] ?? 'Admin') ?>!</h2>

      <?php if ($warning): ?>
        <div class="rounded bg-rose-500/30 border border-rose-500/40 text-rose-100 p-4 mb-4">
          <?= esc($warning) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?= url('login') ?>" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-white/75 mb-2">Username</label>
          <input type="text" name="name" class="w-full rounded bg-white/10 border border-white/15 px-3 py-2 text-white placeholder-white/60 focus:outline-none" placeholder="Username" value="<?= esc($name) ?>" autofocus>
        </div>
        <div>
          <label class="block text-sm font-medium text-white/75 mb-2">Password</label>
          <input type="password" name="password" class="w-full rounded bg-white/10 border border-white/15 px-3 py-2 text-white placeholder-white/60 focus:outline-none" placeholder="Password">
        </div>

        <button type="submit" class="w-full rounded bg-sky-500/90 hover:bg-sky-500 py-2 text-white font-medium">Log in</button>
      </form>

      <div class="mt-3">
        <a href="<?= url('') ?>" class="inline-flex items-center justify-center w-full rounded bg-white/10 hover:bg-white/20 py-2 text-white font-medium">Back</a>
      </div>

      <div class="mt-4 rounded bg-white/10 border border-white/15 p-3 text-xs text-white/80">
        <div class="font-semibold mb-1">Demo Account (Admin)</div>
        <div>Username: Borris</div>
        <div>Password: admin123</div>
        <div class="mt-2 text-white/60">Regular users are added by admin</div>
      </div>
    </div>
  </div>
</div>
