<?php
use Slim\App;


use app\controllers\HomeController;
use app\controllers\ListaController;

return function (App $app){
    $app->get('/', [HomeController::class, 'index']);
    $app->post('/lista/create', [ListaController::class, 'create']);
    $app->post('/lista/atualizar/{id}', [ListaController::class, 'atualizarLista']);
    $app->get('/lista/delete/{id}', [ListaController::class, 'deleteLista']);
    $app->get('/lista/view/{id}', [ListaController::class, 'viewLista']);
    $app->post('/item/excluir_item/{id}', [ListaController::class, 'deleteItem']);
    $app->post('/calcular', [HomeController::class, 'calcularLista']);

};
