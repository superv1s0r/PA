<?php
require_once 'crud.php';

class PacienteCrud extends Crud {
    public function __construct($conn) {
        parent::__construct($conn, 'Pacientes');
    }

    private function validateData($data) {
        if (isset($data['nombre']) && (empty($data['nombre']) || strlen($data['nombre']) > 100)) {
            throw new Exception("El campo 'nombre' no puede estar vacío y debe tener un máximo de 100 caracteres.");
        }

        if (isset($data['edad']) && (!is_numeric($data['edad']) || $data['edad'] < 0 || $data['edad'] > 150)) {
            throw new Exception("El campo 'edad' debe ser un número entre 0 y 150.");
        }

        if (isset($data['genero']) && !in_array($data['genero'], ['Masculino', 'Femenino'])) {
            throw new Exception("El campo 'genero' debe ser 'Masculino' o 'Femenino'.");
        }

        if (isset($data['fecha_registro']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha_registro'])) {
            throw new Exception("El campo 'fecha_registro' debe tener el formato YYYY-MM-DD.");
        }

        if (isset($data['telefono']) && !preg_match('/^\+?\d{1,15}$/', $data['telefono'])) {
            throw new Exception("El campo 'telefono' debe ser un número de teléfono válido (máximo 15 dígitos).");
        }

        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El campo 'email' debe ser una dirección de correo válida.");
        }

        if (isset($data['direccion']) && strlen($data['direccion']) > 500) {
            throw new Exception("El campo 'direccion' no debe exceder los 500 caracteres.");
        }
    }

    public function create($data) {
        $this->validateData($data);
        return parent::create($data);
    }

    public function update($data, $conditions) {
        $this->validateData($data);
        return parent::update($data, $conditions);
    }
    public function getById($id) {
        $query = "SELECT * FROM pacientes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            throw new Exception("Paciente no encontrado.");
        }
    }

}
?>
