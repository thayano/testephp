<?php

namespace app\models;

class ItemLista {
    private $id;
    private $idLista;
    private $idProduto;
    private $quantidade;

    public function getId() {
        return $this->id;
    }

    public function getIdLista() {
        return $this->idLista;
    }

    public function setIdLista($idLista) {
        $this->idLista = $idLista;
    }

    public function getIdProduto() {
        return $this->idProduto;
    }

    public function setIdProduto($idProduto) {
        $this->idProduto = $idProduto;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }

    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }
}
