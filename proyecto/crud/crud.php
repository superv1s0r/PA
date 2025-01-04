<?php
//Clase base de CRUD.
class Crud {
    protected $conn;
    protected $table;

    public function __construct($conn, $table) {
        $this->conn = $conn;
        $this->table = $table;
    }

    public function create($data) {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($data)), ...array_values($data));

        if (!$stmt->execute()) {
            throw new Exception("Error al insertar: " . $this->conn->error);
        }

        return $this->conn->insert_id;
    }

    public function read($conditions = []) {
        $sql = "SELECT * FROM {$this->table}";

        if ($conditions) {
            $where = implode(' AND ', array_map(fn($col) => "$col = ?", array_keys($conditions)));
            $sql .= " WHERE $where";
        }

        $stmt = $this->conn->prepare($sql);
        if ($conditions) {
            $stmt->bind_param(str_repeat('s', count($conditions)), ...array_values($conditions));
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($data, $conditions) {
        $set = implode(',', array_map(fn($col) => "$col = ?", array_keys($data)));
        $where = implode(' AND ', array_map(fn($col) => "$col = ?", array_keys($conditions)));

        $sql = "UPDATE {$this->table} SET $set WHERE $where";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            str_repeat('s', count($data)) . str_repeat('s', count($conditions)),
            ...array_merge(array_values($data), array_values($conditions))
        );

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar: " . $this->conn->error);
        }

        return $stmt->affected_rows;
    }

    public function delete($conditions) {
        $where = implode(' AND ', array_map(fn($col) => "$col = ?", array_keys($conditions)));

        $sql = "DELETE FROM {$this->table} WHERE $where";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($conditions)), ...array_values($conditions));

        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar: " . $this->conn->error);
        }

        return $stmt->affected_rows;
    }
}


?>
