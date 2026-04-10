<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Solicitud.php';
require_once __DIR__ . '/../models/Taller.php';

class AdminController
{
    private $solicitudModel;
    private $tallerModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->solicitudModel = new Solicitud($db);
        $this->tallerModel = new Taller($db);
    }

    public function solicitudes()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: index.php?page=login');
            return;
        }
        require __DIR__ . '/../views/admin/solicitudes.php';
    }
    
    //  Aprobar solicitud
    public function aprobar()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }

        $solicitudId = $_POST['id_solicitud'] ?? 0;

        if (!$solicitudId) {
            echo json_encode(['success' => false, 'error' => 'Solicitud inválida']);
            return;
        }

        $solicitud = $this->solicitudModel->getById($solicitudId);

        if (!$solicitud) {
            echo json_encode(['success' => false, 'error' => 'Solicitud no encontrada']);
            return;
        }

        $taller = $this->tallerModel->getById($solicitud['taller_id']);

        if (!$taller || $taller['cupo_disponible'] <= 0) {
            echo json_encode(['success' => false, 'error' => 'No hay cupo disponible']);
            return;
        }

        $descontado = $this->tallerModel->descontarCupo($solicitud['taller_id']);

        if (!$descontado) {
            echo json_encode(['success' => false, 'error' => 'No se pudo descontar el cupo']);
            return;
        }

        $aprobado = $this->solicitudModel->aprobar($solicitudId);

        if ($aprobado) {
            echo json_encode(['success' => true, 'message' => 'Solicitud aprobada']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al aprobar solicitud']);
        }
    }

    //  Rechazar solicitud
    public function rechazar()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        $solicitudId = $_POST['id_solicitud'] ?? 0;
        
        if ($this->solicitudModel->rechazar($solicitudId)) {
            echo json_encode(['success' => true, 'message' => 'Solicitud rechazada']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al rechazar']);
        }
    }

    //  Obtener solicitudes pendientes
    public function getSolicitudes()
    {
        header('Content-Type: application/json');
        echo json_encode($this->solicitudModel->getPendientes());
    }
}