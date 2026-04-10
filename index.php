<?php
ob_start(); // 🔥 NUEVO
session_start();

require_once './app/controllers/UserController.php';
require_once './app/controllers/TallerController.php';
require_once './app/controllers/AdminController.php';

// ================== ROUTER ==================
$page = $_GET['page'] ?? 'login';

// ========== RUTAS AJAX (GET) ==========
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if ($page === 'talleres_json') {
        $taller = new TallerController();
        $taller->getTalleresJson();
        exit;
    }

    if ($page === 'obtenerSolicitudes') {
        $admin = new AdminController();
        $admin->getSolicitudes();
        exit;
    }
}

// ========== RUTAS AJAX (POST) ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($page === 'login') {
        (new UserController())->login();
        exit;
    }

    if ($page === 'register') {
        (new UserController())->registro();
        exit;
    }

    if ($page === 'logout') {
        (new UserController())->logout();
        exit;
    }

    if ($page === 'solicitar') {
        (new TallerController())->solicitar();
        exit;
    }

    if ($page === 'aprobarSolicitud') {
        (new AdminController())->aprobar();
        exit;
    }

    if ($page === 'rechazarSolicitud') {
        (new AdminController())->rechazar();
        exit;
    }
}

// ========== VISTAS ==========
switch ($page) {

    case "talleres":
        (new TallerController())->index();
        break;

    case "admin":
        (new AdminController())->solicitudes();
        break;

    case "registro":
        (new UserController())->showRegistro();
        break;

    case "login":
    default:
        (new UserController())->showLogin();
        break;
}

ob_end_flush(); // 🔥 NUEVO