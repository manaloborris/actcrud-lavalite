<?php
$db = db();
$allowedGenders = ['Male', 'Female', 'Other'];
$warning = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'bcm_username'   => trim($_POST['username'] ?? ''),
        'bcm_password'   => trim($_POST['password'] ?? ''),
        'bcm_last_name'  => trim($_POST['lastname'] ?? ''),
        'bcm_first_name' => trim($_POST['firstname'] ?? ''),
        'bcm_email'      => trim($_POST['email'] ?? ''),
        'bcm_gender'     => trim($_POST['gender'] ?? ''),
        'bcm_address'    => trim($_POST['address'] ?? ''),
    ];

    if (in_array('', $data, true)) {
        $warning = "Please fill all fields!";
    } elseif (!in_array($data['bcm_gender'], $allowedGenders, true)) {
      $warning = "Please select a valid gender.";
    } else {
        // Hash the password before storing
        $data['bcm_password'] = password_hash($data['bcm_password'], PASSWORD_BCRYPT);
        $db->table('borris_bcm_users')->insert($data);
        header("Location: " . url('crud'));
        exit;
    }
}


$rows = $db->table('borris_bcm_users')->order_by('id', 'DESC')->get_all();
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?= url('public/css/style.css?v=20260331b') ?>">

<div class="min-h-screen flex items-center justify-center py-20">
  <div class="max-w-6xl w-full px-4">
    <div class="flex justify-center">
      <div class="w-full">
        <div class="glass fade-in p-6 mb-6">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
              <div class="text-white/50 uppercase text-xs tracking-widest">Admin</div>
              <div class="text-white/80 mt-1">Welcome <?= esc($_SESSION['user_name'] ?? 'Borris') ?>!</div>
            </div>
            <div class="flex gap-2 justify-end">
              <a class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-white/10 hover:bg-white/20 rounded" href="<?= url('view') ?>">View users</a>
              <a class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-white/10 hover:bg-white/20 rounded" href="<?= url('logout') ?>">Logout</a>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
          <div class="lg:col-span-4">
            <div class="glass p-6 h-full">
              <p class="text-xs uppercase tracking-[0.2em] text-white/50 mb-1">Add New User</p>
              <h2 class="text-2xl font-semibold tracking-tight text-white mb-3"></h2>
              

              <?php if ($warning): ?>
                <div class="rounded bg-rose-500/30 border border-rose-500/40 text-rose-100 p-3 mb-4">
                  <?= esc($warning) ?>
                </div>
              <?php endif; ?>

              <form method="POST" action="<?= url('crud') ?>" class="space-y-3">
                <input class="w-full rounded bg-white/10 border border-white/15 px-3 py-2 text-white placeholder-white/60 focus:outline-none" name="username" placeholder="Username">
                <input type="password" class="w-full rounded bg-white/10 border border-white/15 px-3 py-2 text-white placeholder-white/60 focus:outline-none" name="password" placeholder="Password">
                <input class="w-full rounded bg-white/10 border border-white/15 px-3 py-2 text-white placeholder-white/60 focus:outline-none" name="lastname" placeholder="Last Name">
                <input class="w-full rounded bg-white/10 border border-white/15 px-3 py-2 text-white placeholder-white/60 focus:outline-none" name="firstname" placeholder="First Name">
                <input class="w-full rounded bg-white/10 border border-white/15 px-3 py-2 text-white placeholder-white/60 focus:outline-none" name="email" placeholder="Email">
                <select class="native-light w-full rounded bg-white/10 border border-white/15 px-3 py-2 text-white focus:outline-none" name="gender" required>
                  <option value="" class="text-slate-900">Select Gender</option>
                  <option value="Male" class="text-slate-900" <?= (($_POST['gender'] ?? '') === 'Male') ? 'selected' : '' ?>>Male</option>
                  <option value="Female" class="text-slate-900" <?= (($_POST['gender'] ?? '') === 'Female') ? 'selected' : '' ?>>Female</option>
                  <option value="Other" class="text-slate-900" <?= (($_POST['gender'] ?? '') === 'Other') ? 'selected' : '' ?>>Other</option>
                </select>
                <input class="w-full rounded bg-white/10 border border-white/15 px-3 py-2 text-white placeholder-white/60 focus:outline-none" name="address" placeholder="Address">

                <button class="w-full rounded bg-sky-500/90 hover:bg-sky-500 py-2 text-white font-medium" type="submit">Add User</button>
              </form>
            </div>
          </div>

          <div class="lg:col-span-8">
            <div class="glass p-6 h-full">
              <div class="flex items-center justify-between mb-4">
                <div>
                  <p class="text-xs uppercase tracking-[0.2em] text-white/50 mb-1">User</p>
                  <h2 class="text-2xl font-semibold tracking-tight text-white"></h2>
                </div>
              </div>

              <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                  <thead class="bg-white/10 text-white/70">
                    <tr>
                      <th class="px-3 py-2">Last</th>
                      <th class="px-3 py-2">First</th>
                      <th class="px-3 py-2">Email</th>
                      <th class="px-3 py-2">Gender</th>
                      <th class="px-3 py-2">Address</th>
                      <th class="px-3 py-2">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($rows)): ?>
                      <tr>
                        <td colspan="6" class="px-3 py-10 text-center text-white/50">No users found.</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($rows as $row): ?>
                        <tr class="odd:bg-white/5 even:bg-white/2">
                          <td class="px-3 py-2"><?= esc($row['bcm_last_name']) ?></td>
                          <td class="px-3 py-2"><?= esc($row['bcm_first_name']) ?></td>
                          <td class="px-3 py-2"><?= esc($row['bcm_email']) ?></td>
                          <td class="px-3 py-2"><?= esc($row['bcm_gender']) ?></td>
                          <td class="px-3 py-2"><?= esc($row['bcm_address']) ?></td>
                          <td class="px-3 py-2">
                            <div class="flex flex-wrap gap-2">
                              <a class="w-full sm:w-auto inline-flex justify-center items-center px-3 py-1.5 text-sm font-medium text-white bg-amber-500/90 hover:bg-amber-500 rounded" href="<?= url('crud/update/' . $row['id']) ?>">Edit</a>
                              <a class="w-full sm:w-auto inline-flex justify-center items-center px-3 py-1.5 text-sm font-medium text-white bg-rose-500/90 hover:bg-rose-500 rounded" onclick="return confirm('Are you sure to delete this record?')" href="<?= url('crud/delete/' . $row['id']) ?>">Delete</a>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
