<?php
require_once 'crud.php';


class CitaCrud extends Crud {
    public function __construct($conn) {
        parent::__construct($conn, 'citas');
    }
}
?>
