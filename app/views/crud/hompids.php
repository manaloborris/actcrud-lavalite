<?php
if (!empty($_SESSION['authenticated'])) {
  if (($_SESSION['role'] ?? '') === 'admin') {
    header('Location: ' . url('crud'));
  } else {
    header('Location: ' . url('crud/view'));
  }
    exit;
}
?>

<link rel="stylesheet" href="<?= url('public/css/style.css?v=20260331b') ?>">

<div class="min-h-screen flex items-center justify-center py-20">
  <div class="max-w-md w-full px-4">
    <div class="glass fade-in p-6 text-center">
      <h1 class="text-3xl font-semibold mb-3 text-white">Welcome</h1>
      <p class="text-white/70 mb-8">Log-In to Continue</p>
      <a href="<?= url('login') ?>" class="inline-flex items-center justify-center w-full rounded bg-sky-500/90 hover:bg-sky-500 py-2 text-white font-medium">Login</a>
    </div>
  </div>
</div>