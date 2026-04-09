<?php
return function ($requestMethod, $params) {
    if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: ' . url('view'));
        exit;
    }

    return true;
};
