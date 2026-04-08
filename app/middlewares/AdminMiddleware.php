<?php
return function ($requestMethod, $params) {
    if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        $error = 'Admin access required.';
        include dirname(__DIR__) . '/views/errors/403.php';
        exit;
    }

    return true;
};
