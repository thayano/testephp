<?php 
namespace app\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use app\database\Connection; 

class HomeController{
    public function index(Request $request, Response $response){
        $db = Connection::getConnection();
        $sql = "SELECT lc.id AS lista_id, lc.titulo AS lista_titulo, il.id AS item_id, il.quantidade, p.nome AS produto_nome
                FROM listas_compras lc
                LEFT JOIN itens_lista il ON lc.id = il.id_lista
                LEFT JOIN produtos p ON il.id_produto = p.id";
    
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $listas = [];
        foreach ($results as $result) {
            $listaId = $result->lista_id;
            if (!isset($listas[$listaId])) {
                $listas[$listaId] = [
                    'listaId' => $listaId,
                    'lista_titulo' => $result->lista_titulo,
                    'itens' => []
                ];
            }
            if ($result->item_id) {
                $listas[$listaId]['itens'][] = [
                    'item_id' => $result->item_id,
                    'quantidade' => $result->quantidade,
                    'produto_nome' => $result->produto_nome
                ];
            }
        }
    
        view('home', ['title'=>'Home', 'listas' => $listas]);
        return $response;
    }
    
    public function calcularLista(Request $request, Response $response, $listaIds = []) {
        $listasSelecionadas = $request->getParsedBody('listasSelecionadas', []);
        
        $totalItens = 0;
        $db = Connection::getConnection();
        $produtoQuantidades = [];

        if (!empty($listaIds)) {
            $placeholders = implode(',', array_fill(0, count($listaIds), '?'));

            $sql = "SELECT lc.id AS lista_id, lc.titulo AS lista_titulo, il.id AS item_id, il.quantidade, p.nome AS produto_nome
                    FROM listas_compras lc
                    LEFT JOIN itens_lista il ON lc.id = il.id_lista
                    LEFT JOIN produtos p ON il.id_produto = p.id
                    WHERE lc.id IN ($placeholders)";

            $stmt = $db->prepare($sql);
            foreach ($listaIds as $index => $listaId) {
                $stmt->bindValue($index + 1, $listaId, \PDO::PARAM_INT);
            }
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $results = array();
        }
         var_dump($results);

        view('calcular_lista', ['title' => 'Total de Itens', 'data' => $results]);
        return $response;
    }
    
    
}
