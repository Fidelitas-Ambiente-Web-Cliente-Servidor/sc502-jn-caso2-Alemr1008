<?php
class Solicitud
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Verificar si ya existe solicitud (pendiente o aprobada)
    public function existeSolicitud($usuarioId, $tallerId)
    {
        $query = "SELECT * FROM solicitudes 
                  WHERE usuario_id = ? 
                  AND taller_id = ? 
                  AND estado IN ('pendiente', 'aprobada')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $usuarioId, $tallerId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    // Crear solicitud
    public function crearSolicitud($usuarioId, $tallerId)
    {
        $query = "INSERT INTO solicitudes (usuario_id, taller_id, estado) 
                  VALUES (?, ?, 'pendiente')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $usuarioId, $tallerId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Obtener solicitudes del usuario
    public function getByUsuario($usuarioId)
    {
        $query = "SELECT s.*, t.nombre 
                  FROM solicitudes s
                  JOIN talleres t ON s.taller_id = t.id
                  WHERE s.usuario_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener solicitudes pendientes (ADMIN)
    public function getPendientes()
    {
        $query = "SELECT s.*, u.username, t.nombre 
                  FROM solicitudes s
                  JOIN usuarios u ON s.usuario_id = u.id
                  JOIN talleres t ON s.taller_id = t.id
                  WHERE s.estado = 'pendiente'";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Aprobar solicitud
    public function aprobar($id)
    {
        $query = "UPDATE solicitudes SET estado = 'aprobada' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Rechazar solicitud
    public function rechazar($id)
    {
        $query = "UPDATE solicitudes SET estado = 'rechazada' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Obtener solicitud por ID
    public function getById($id)
    {
        $query = "SELECT * FROM solicitudes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
}