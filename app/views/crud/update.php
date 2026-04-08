<?php
$db = db();
$allowedGenders = ['Male', 'Female', 'Other'];
$id = $id ?? ($_GET['id'] ?? null);
if (!$id) die("Invalid");

$row = $db->table('borris_bcm_users')->where('id', $id)->get();

$warning = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'bcm_last_name'  => trim($_POST['lastname'] ?? ''),
        'bcm_first_name' => trim($_POST['firstname'] ?? ''),
        'bcm_email'      => trim($_POST['email'] ?? ''),
        'bcm_gender'     => trim($_POST['gender'] ?? ''),
        'bcm_address'    => trim($_POST['address'] ?? ''),
    ];

    if (in_array('', $data, true)) {
        $warning = "Fill all fields!";
    } elseif (!in_array($data['bcm_gender'], $allowedGenders, true)) {
      $warning = "Please select a valid gender.";
    } else {
        $db->table('borris_bcm_users')->where('id', $id)->update($data);
        header("Location: " . url('crud'));
        exit;
    }
}
?>

<link rel="stylesheet" href="<?= url('public/css/style.css?v=20260331b') ?>">

<div class="min-h-screen flex items-center justify-center py-20">
  <div class="max-w-2xl w-full px-4">
    <div class="glass fade-in p-6">
      <h2 class="text-2xl font-semibold mb-4">Update User</h2>

      <?php if ($warning): ?>
        <div class="rounded bg-rose-500/30 border border-rose-500/40 text-rose-100 p-4 mb-4">
          <?= esc($warning) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?= url('crud/update/' . $id) ?>" class="space-y-3">
        <input class="w-full rounded bg-white/10 border border-white/15 px-3 py-2 text-white placeholder-white/60 focus:outline-none" name="lastname" value="<?= esc($row['bcm_last_name'] ?? '') ?>" placeholder="Last Name">
        <input class="w-full rounded bg-white/10 border border-white/15 px-3 py-2 text-white placeholder-white/60 focus:outline-none" name="firstname" value="<?= esc($row['bcm_first_name'] ?? '') ?>" placeholder="First Name">
        <input class="w-full rounded bg-white/10 border border-white/15 px-3 py-2 text-white placeholder-white/60 focus:outline-none" name="email" value="<?= esc($row['bcm_email'] ?? '') ?>" placeholder="Email">
        <?php $selectedGender = $_POST['gender'] ?? ($row['bcm_gender'] ?? ''); ?>
        <select class="native-light w-full rounded bg-white/10 border border-white/15 px-3 py-2 text-white focus:outline-none" name="gender" required>
          <option value="" class="text-slate-900">Select Gender</option>
          <option value="Male" class="text-slate-900" <?= $selectedGender === 'Male' ? 'selected' : '' ?>>Male</option>
          <option value="Female" class="text-slate-900" <?= $selectedGender === 'Female' ? 'selected' : '' ?>>Female</option>
          <option value="Other" class="text-slate-900" <?= $selectedGender === 'Other' ? 'selected' : '' ?>>Other</option>
        </select>
        <input class="w-full rounded bg-white/10 border border-white/15 px-3 py-2 text-white placeholder-white/60 focus:outline-none" name="address" value="<?= esc($row['bcm_address'] ?? '') ?>" placeholder="Address">

        <div class="flex flex-col sm:flex-row gap-2">
          <button class="flex-1 rounded bg-emerald-500/90 hover:bg-emerald-500 py-2 text-white font-medium" type="submit">Update</button>
          <a class="flex-1 rounded bg-white/10 hover:bg-white/20 py-2 text-center text-white font-medium" href="<?= url('crud') ?>">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>
