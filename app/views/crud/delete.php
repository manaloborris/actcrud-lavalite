<?php
$db = db();

$id = $id ?? ($_GET['id'] ?? null);
if (!$id) die("Invalid");

$db->table('borris_bcm_users')->where('id', $id)->delete();

// If table is now empty, reset auto-increment back to 1 (MySQL behavior)
if ($db->table('borris_bcm_users')->count() === 0) {
    $db->raw('ALTER TABLE borris_bcm_users AUTO_INCREMENT = 1');
}

header("Location: " . url('crud'));
exit;
?>
