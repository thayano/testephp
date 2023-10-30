<?php $this->layout('master', ['title' => $title]) ?>
<?php if (!empty($listas)): ?>
    <?php foreach ($listas as $lista): ?>
        <div class="col">
        <input type="checkbox" name="listasSelecionadas[]" value="<?php echo $lista['listaId']; ?>">

            <div class="card shadow-sm">
                <h1 class="display-8 fw-bold text-center bg-dark text-light">
                    <span class="d-flex justify-start align-items-center">
                    </span>

                    <?php echo $this->e($lista['lista_titulo']) ?>
                </h1>
                <div class="card-body">
                    <?php if (!empty($lista['itens'])): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr class="d-flex justify-content-between">
                                    <th scope="col">Item</th>
                                    <th scope="col">Quantidade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lista['itens'] as $item): ?>
                                    <tr>
                                        <td>
                                            <?php echo $this->e($item['produto_nome']); ?>
                                        </td>
                                        <td>
                                            <?php echo $this->e($item['quantidade']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Nenhum item nesta lista.</p>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="btn-group">
                            <a href="/lista/view/<?php echo $this->e($lista['listaId']); ?>"
                                class="btn btn-sm btn-outline-secondary">Editar</a>
                            <a href="/lista/delete/<?php echo $this->e($lista['listaId']); ?>"
                                class="btn btn-sm btn-outline-danger">Excluir</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Nenhum resultado encontrado.</p>
<?php endif; ?>