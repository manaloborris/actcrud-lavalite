<?php
$db = db();

$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? 'id';
$order = strtoupper($_GET['order'] ?? 'DESC');

$allowedSorts = ['id', 'bcm_last_name', 'bcm_first_name', 'bcm_email', 'bcm_gender', 'bcm_address'];
if (!in_array($sort, $allowedSorts, true)) {
    $sort = 'id';
}
if (!in_array($order, ['ASC', 'DESC'], true)) {
    $order = 'DESC';
}

$query = $db->table('borris_bcm_users');
$isAdmin = (($_SESSION['role'] ?? '') === 'admin');

if ($search !== '') {
    $query->like('bcm_last_name', "%{$search}%")
          ->or_like('bcm_first_name', "%{$search}%")
          ->or_like('bcm_email', "%{$search}%");
}

$rows = $query->order_by($sort, $order)->get_all();
?>

<link rel="stylesheet" href="<?= url('public/css/style.css?v=20260331b') ?>">

<div class="min-h-screen flex items-center justify-center py-20">
  <div class="max-w-6xl w-full px-4">
    <div class="flex justify-center">
      <div class="w-full">
        <div class="glass fade-in p-6 mb-6">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
              <h2 class="text-2xl font-semibold mb-1">User Directory</h2>
              <p class="text-white/70 mb-0">Search, sort and manage users.</p>
            </div>
            <div class="text-right">
              <a class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-white/10 hover:bg-white/20 rounded" href="<?= $isAdmin ? url('crud') : url('view') ?>"><?= $isAdmin ? 'Back to dashboard' : 'Refresh' ?></a>
              <a class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-white/10 hover:bg-white/20 rounded ml-2" href="<?= url('logout') ?>">Logout</a>
            </div>
          </div>

          <?php if (!$isAdmin): ?>
            <div class="mt-4 rounded bg-sky-500/20 border border-sky-400/30 text-sky-100 p-3 text-sm">
              You are logged in as a viewer. Add, edit, and delete actions are disabled.
            </div>
          <?php endif; ?>

          <form class="grid grid-cols-1 md:grid-cols-12 gap-3 mt-4" method="GET" action="<?= url('view') ?>">
            <div class="md:col-span-5">
              <label class="sr-only" for="search">Search</label>
              <div class="flex items-center rounded bg-white/10 border border-white/15">
                <span class="px-3 text-white/70">Search</span>
                <input id="search" type="text" name="search" class="flex-1 bg-transparent p-2 text-white placeholder-white/50 focus:outline-none" value="<?= esc($search) ?>" placeholder="Name or email">
              </div>
            </div>
            <div class="md:col-span-4">
              <label class="sr-only" for="sort">Sort</label>
              <div class="flex items-center rounded bg-white/10 border border-white/15">
                <span class="px-3 text-white/70">Sort</span>
                <select id="sort" name="sort" class="flex-1 bg-transparent p-2 text-white focus:outline-none">
                  <option value="id" <?= $sort === 'id' ? 'selected' : '' ?>>ID</option>
                  <option value="bcm_last_name" <?= $sort === 'bcm_last_name' ? 'selected' : '' ?>>Last</option>
                  <option value="bcm_first_name" <?= $sort === 'bcm_first_name' ? 'selected' : '' ?>>First</option>
                  <option value="bcm_email" <?= $sort === 'bcm_email' ? 'selected' : '' ?>>Email</option>
                  <option value="bcm_gender" <?= $sort === 'bcm_gender' ? 'selected' : '' ?>>Gender</option>
                </select>
              </div>
            </div>
            <div class="md:col-span-3">
              <label class="sr-only" for="order">Order</label>
              <div class="flex items-center rounded bg-white/10 border border-white/15">
                <span class="px-3 text-white/70">Order</span>
                <select id="order" name="order" class="flex-1 bg-transparent p-2 text-white focus:outline-none">
                  <option value="DESC" <?= $order === 'DESC' ? 'selected' : '' ?>>Desc</option>
                  <option value="ASC" <?= $order === 'ASC' ? 'selected' : '' ?>>Asc</option>
                </select>
              </div>
            </div>
          </form>
        </div>

        <div class="glass p-4">
          <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
              <thead class="bg-white/10 text-white/70">
                <tr>
                  <th class="px-3 py-2">Last</th>
                  <th class="px-3 py-2">First</th>
                  <th class="px-3 py-2">Email</th>
                  <th class="px-3 py-2">Gender</th>
                  <th class="px-3 py-2">Address</th>
                  <?php if ($isAdmin): ?>
                    <th class="px-3 py-2">Action</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($rows)): ?>
                  <tr>
                    <td colspan="<?= $isAdmin ? '6' : '5' ?>" class="px-3 py-10 text-center text-white/50">No users found.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($rows as $row): ?>
                    <tr class="odd:bg-white/5 even:bg-white/2">
                      <td class="px-3 py-2"><?= esc($row['bcm_last_name']) ?></td>
                      <td class="px-3 py-2"><?= esc($row['bcm_first_name']) ?></td>
                      <td class="px-3 py-2"><?= esc($row['bcm_email']) ?></td>
                      <td class="px-3 py-2"><?= esc($row['bcm_gender']) ?></td>
                      <td class="px-3 py-2"><?= esc($row['bcm_address']) ?></td>
                      <?php if ($isAdmin): ?>
                        <td class="px-3 py-2">
                          <a class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-amber-500/90 hover:bg-amber-500 rounded mr-2" href="<?= url('crud/update/' . $row['id']) ?>">Edit</a>
                          <a class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-rose-500/90 hover:bg-rose-500 rounded" onclick="return confirm('Are you sure to delete this record?')" href="<?= url('crud/delete/' . $row['id']) ?>">Delete</a>
                        </td>
                      <?php endif; ?>
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
