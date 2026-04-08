<?php
return function ($requestMethod, $params) {
    if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: ' . url('crud/view'));
        exit;
    }

    return true;
};
