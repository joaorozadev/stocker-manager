<?php
require_once __DIR__ . '/../Models/inventorymodel.php';

class InventoryController {
    private $model;

    public function __construct() {
        $this->model = new InventoryModel();
    }

    public function index() {
        return [
            'produtos'   => $this->model->getTodosProdutos(),
            'categorias' => $this->model->getCategorias()
        ];
    }

}