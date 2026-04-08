<?php
/**
 * All routes here
 */

$router->get('/', 'app/views/crud/hompids');

// Login (simple session-based greeting)
$router->get('login', 'app/views/crud/login');
$router->post('login', 'app/views/crud/login');

// CRUD routes (views located in app/views/crud)
$router->get('crud', 'app/views/crud/index')->middleware('AuthMiddleware')->middleware('AdminMiddleware');
$router->post('crud', 'app/views/crud/index')->middleware('AuthMiddleware')->middleware('AdminMiddleware');

$router->get('view', 'app/views/crud/view')->middleware('AuthMiddleware');
$router->get('crud/view', 'app/views/crud/view')->middleware('AuthMiddleware');

$router->get('crud/update/{id}', 'app/views/crud/update')->middleware('AuthMiddleware')->middleware('AdminMiddleware');
$router->post('crud/update/{id}', 'app/views/crud/update')->middleware('AuthMiddleware')->middleware('AdminMiddleware');

$router->get('crud/delete/{id}', 'app/views/crud/delete')->middleware('AuthMiddleware')->middleware('AdminMiddleware');

// Logout
$router->get('logout', function() {
    session_destroy();
    header('Location: ' . url('login'));
    exit;
});
