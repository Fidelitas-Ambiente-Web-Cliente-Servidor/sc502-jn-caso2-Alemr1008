<?php
class Taller
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //  Todos los talleres
    public function getAll()
    {
        $result = $this->conn->query("SELECT * FROM talleres ORDER BY nombre");
        $talleres = [];
        while ($row = $result->fetch_assoc()) {
            $talleres[] = $row;
        }
        return $talleres;
    }

    //  SOLO talleres con cupo disponible
    public function getAllDisponibles()
    {
        $query = "SELECT * FROM talleres 
                  WHERE cupo_disponible > 0 
                  ORDER BY nombre";

        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //  Obtener taller por ID
    public function getById($id)
    {
        $query = "SELECT * FROM talleres WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    //  Descontar cupo (CRÍTICO)
    public function descontarCupo($tallerId)
    {
        // Validar que haya cupo
        $taller = $this->getById($tallerId);

        if (!$taller || $taller['cupo_disponible'] <= 0) {
            return false; // ❌ no hay cupo
        }

        $query = "UPDATE talleres 
                  SET cupo_disponible = cupo_disponible - 1 
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $tallerId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    //  Sumar cupo (por si se necesita)
    public function sumarCupo($tallerId)
    {
        $query = "UPDATE talleres 
                  SET cupo_disponible = cupo_disponible + 1 
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $tallerId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
}