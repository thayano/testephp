<?php
namespace app\models;

use app\database\Connection;

class ListaCompra {
    private $id;
    private $titulo;
    private $data;

    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }

}
