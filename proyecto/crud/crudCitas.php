<?php
require_once 'crud.php';
class CitaCrud extends Crud {
    public function __construct($conn) {
        parent::__construct($conn, 'citas');
    }

    private function validateData($data) {
        if (isset($data['fecha']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha'])) {
            throw new Exception("El campo 'fecha' debe tener el formato YYYY-MM-DD.");
        }

        if (isset($data['hora']) && !preg_match('/^\d{2}:\d{2}:\d{2}$/', $data['hora'])) {
            throw new Exception("El campo 'hora' debe tener el formato HH:MM:SS.");
        }

        if (isset($data['paciente_id']) && (!is_numeric($data['paciente_id']) || $data['paciente_id'] <= 0)) {
            throw new Exception("El campo 'paciente_id' debe ser un número positivo.");
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
}
?> 
