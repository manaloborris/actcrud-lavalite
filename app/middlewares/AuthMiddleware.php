<?php
return function ($requestMethod, $params) {
    // Do not start session here; Router already started it.
    if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        header('Location: ' . url('login'));
        exit;
    }

    // Default old sessions to least privilege.
    if (empty($_SESSION['role'])) {
        $_SESSION['role'] = 'user';
    }

    return true;
};
