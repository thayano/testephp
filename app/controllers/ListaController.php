<?php
namespace app\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use app\database\Connection;
use app\models\ListaCompra;

class ListaController
{
    public function index(Request $request, Response $response)
    {
        view('lista_create', ['title' => 'lista create']);
        return $response;
    }

    public function create(Request $request, Response $response)
    {
        $nomeProdutos = $_POST['nomeProduto'];
        $titulo = $_POST['titulo'];
        $quantidades = $_POST['quantidadeItem'];
        $listaId = $this->criarNovaLista($titulo);

        if ($listaId) {
            foreach ($nomeProdutos as $index => $nomeProduto) {
                $quantidadeItem = $quantidades[$index];
                $produtoId = $this->criarNovoProduto($nomeProduto);
                if ($produtoId) {
                    $this->adicionarItemALista($listaId, $produtoId, $quantidadeItem);
                } else {
                    return $response->withRedirect('/erro'); //**fazer pagina de erro
                }
            }
            return $response->withHeader('Location', '/')->withStatus(302);
        } else {
            return $response->withRedirect('/erro');
        }
    }

    private function criarNovaLista($titulo)
    {
        $db = Connection::getConnection();
        $sql = "INSERT INTO listas_compras (titulo, data) VALUES (:titulo, NOW())";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':titulo', $titulo);

        if ($stmt->execute()) {
            return $db->lastInsertId();
        } else {
            return false;
        }
    }

    private function criarNovoProduto($nomeProduto)
    {
        $db = Connection::getConnection();
        $sql = "SELECT id FROM produtos WHERE nome = :nome";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':nome', $nomeProduto);
        $stmt->execute();
        $produtoExistente = $stmt->fetch();

        if ($produtoExistente && isset($produtoExistente->id)) {
            return $produtoExistente->id;
        } else {
            $sql = "INSERT INTO produtos (nome) VALUES (:nome)";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':nome', $nomeProduto);
            $stmt->execute();
            return $db->lastInsertId();
        }
    }
    private function adicionarItemALista($listaId, $produtoId, $quantidadeItem)
    {
        $db = Connection::getConnection();

        $sql = "INSERT INTO itens_lista (id_lista, id_produto, quantidade) VALUES (:listaId, :produtoId, :quantidade)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':listaId', $listaId);
        $stmt->bindValue(':produtoId', $produtoId);
        $stmt->bindValue(':quantidade', $quantidadeItem);

        if ($stmt->execute()) {
            return $db->lastInsertId();
        } else {
            return false;
        }
    }

    public function deleteLista(Request $request, Response $response, $args)
    {
        $listaId = $args['id'];
        $lista = $this->getListaById($listaId);

        if (!$lista) {
            return $response->withRedirect('/erro');
        }
        $db = Connection::getConnection();
        $sqlDeleteItens = "DELETE FROM itens_lista WHERE id_lista = :listaId";
        $stmtDeleteItens = $db->prepare($sqlDeleteItens);
        $stmtDeleteItens->bindValue(':listaId', $listaId);
        $stmtDeleteItens->execute();
        $sqlDeleteLista = "DELETE FROM listas_compras WHERE id = :listaId";
        $stmtDeleteLista = $db->prepare($sqlDeleteLista);
        $stmtDeleteLista->bindValue(':listaId', $listaId);
        $stmtDeleteLista->execute();

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function deleteItem(Request $request, Response $response, $args)
    {
        $itemId = $args['id'];
        $db = Connection::getConnection();

        $sql = "DELETE FROM itens_lista WHERE id = :item_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':item_id', $itemId);
        $stmt->execute();

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function viewLista(Request $request, Response $response, $args)
    {
        $listaId = $args['id'];
        $lista = $this->obterListaPorId($listaId);
        view('editar_lista', ['title' => 'Editar Lista', 'lista' => $lista]);
        return $response;
    }

    private function obterListaPorId($listaId)
    {
        $db = Connection::getConnection();
        $sql = "SELECT lc.titulo AS lista_titulo, il.id AS item_id, il.quantidade, p.nome AS produto_nome
                FROM listas_compras lc
                LEFT JOIN itens_lista il ON lc.id = il.id_lista
                LEFT JOIN produtos p ON il.id_produto = p.id
                WHERE lc.id = :lista_id";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':lista_id', $listaId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $lista = [
            'id' => $listaId,
            'titulo' => null,
            'itens' => []
        ];

        foreach ($result as $row) {
            if ($lista['titulo'] === null) {
                $lista['titulo'] = $row['lista_titulo'];
            }

            $lista['itens'][] = [
                'item_id' => $row['item_id'],
                'quantidade' => $row['quantidade'],
                'produto_nome' => $row['produto_nome']
            ];
        }

        return $lista;
    }
    public function atualizarLista(Request $request, Response $response, $args)
    {
        $listaId = $args['id'];
        $tituloLista = $_POST['tituloLista'];
        $itensExistente = $_POST['itens'];

        $db = Connection::getConnection();

        $sqlAtualizarTitulo = "UPDATE listas_compras SET titulo = :titulo WHERE id = :lista_id";
        $stmtAtualizarTitulo = $db->prepare($sqlAtualizarTitulo);
        $stmtAtualizarTitulo->bindValue(':titulo', $tituloLista);
        $stmtAtualizarTitulo->bindValue(':lista_id', $listaId);
        $stmtAtualizarTitulo->execute();

        foreach ($itensExistente as $itemId => $item) {
            $quantidadeItem = $item['quantidade'];
            $nomeItem = $item['nome'];

            $sqlVerificarProduto = "SELECT id FROM produtos WHERE nome = :nomeItem";
            $stmtVerificarProduto = $db->prepare($sqlVerificarProduto);
            $stmtVerificarProduto->bindValue(':nomeItem', $nomeItem);
            $stmtVerificarProduto->execute();
            $produtoId = $stmtVerificarProduto->fetchColumn();

            if (!$produtoId) {
                $sqlAdicionarProduto = "INSERT INTO produtos (nome) VALUES (:nomeItem)";
                $stmtAdicionarProduto = $db->prepare($sqlAdicionarProduto);
                $stmtAdicionarProduto->bindValue(':nomeItem', $nomeItem);
                $stmtAdicionarProduto->execute();
                $produtoId = $db->lastInsertId();
            }

            $sqlAtualizarItem = "UPDATE itens_lista SET id_produto = :id_produto, quantidade = :quantidade WHERE id = :item_id";
            $stmtAtualizarItem = $db->prepare($sqlAtualizarItem);
            $stmtAtualizarItem->bindValue(':id_produto', $produtoId);
            $stmtAtualizarItem->bindValue(':quantidade', $quantidadeItem);
            $stmtAtualizarItem->bindValue(':item_id', $itemId);
            $stmtAtualizarItem->execute();
        }

        $novosItensNomes = isset($_POST['novosItensNome']) ? $_POST['novosItensNome'] : [];
        $novosItensQuantidades = isset($_POST['novosItensQuantidade']) ? $_POST['novosItensQuantidade'] : [];

        for ($i = 0; $i < count($novosItensNomes); $i++) {
            $nomeNovoItem = $novosItensNomes[$i];
            $quantidadeNovoItem = $novosItensQuantidades[$i];
            $produtoId = $this->criarNovoProduto($nomeNovoItem);
            $sqlAdicionarItemLista = "INSERT INTO itens_lista (id_lista, id_produto, quantidade) VALUES (:id_lista, :id_produto, :quantidade)";
            $stmtAdicionarItemLista = $db->prepare($sqlAdicionarItemLista);
            $stmtAdicionarItemLista->bindValue(':id_lista', $listaId);
            $stmtAdicionarItemLista->bindValue(':id_produto', $produtoId);
            $stmtAdicionarItemLista->bindValue(':quantidade', $quantidadeNovoItem);
            $stmtAdicionarItemLista->execute();
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    private function getListaById($listaId)
    {
        $db = Connection::getConnection();
        $sql = "SELECT * FROM listas_compras WHERE id = :listaId";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':listaId', $listaId);
        $stmt->execute();
        return $stmt->fetch();
    }
}