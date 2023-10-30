<?php $this->layout('master', ['title' => $title]) ?>
<div class="mx-auto">
    <form method="post" action="/lista/atualizar/<?php echo $lista['id']; ?>" id="form">
        <div class="mb-3">
            <label for="tituloLista" class="form-label">Título da Lista:</label>
            <input type="text" class="form-control" id="tituloLista" name="tituloLista"
                value="<?php echo $this->e($lista['titulo']); ?>" required>
        </div>
        <?php foreach ($lista['itens'] as $item): ?>
            <div class="d-flex" id="<?php echo $item['item_id']; ?>">
                <div class="mb-3 col-8">
                    <label for="item_<?php echo $item['item_id']; ?>_nome" class="form-label">Nome do Item:</label>
                    <input type="text" class="form-control" id="item_<?php echo $item['item_id']; ?>_nome"
                        name="itens[<?php echo $item['item_id']; ?>][nome]"
                        value="<?php echo $this->e($item['produto_nome']); ?>" required>
                </div>
                <div class="mb-3 ms-2 col-2">
                    <label for="item_<?php echo $item['item_id']; ?>_quantidade" class="form-label">Quant:</label>
                    <input type="number" class="form-control" id="item_<?php echo $item['item_id']; ?>_quantidade"
                        name="itens[<?php echo $item['item_id']; ?>][quantidade]"
                        value="<?php echo $this->e($item['quantidade']); ?>" required>
                </div>
                <div class="mb-3 ms-2">
                    <label class="form-label"></label>
                    <button class="btn btn-outline-danger mt-2" type="button"
                        onclick="excluirItem(<?php echo $item['item_id']; ?>)">Exluir</button>
                </div>
            </div>
        <?php endforeach; ?>
        <h4 class="mt-4">Adicionar Novos Itens:</h4>
        <div class="mb-3" id="novosItensContainer">
        </div>
        <button type="button" class="btn btn-secondary" id="adicionarNovoItemBtn">Adicionar Novo Item</button>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>

    <script>
         document.addEventListener('DOMContentLoaded', function () {
            var formulario = document.getElementById('form')
            formulario.addEventListener('submit', function (event) {
                var nomeInputs = formulario.querySelectorAll('input[name="novosItensNome[]"]');
                var nomesLista = {}
                var duplucado = false
                nomeInputs.forEach(function (input) {
                    var nome = input.value.trim()
                    if (nomesLista[nome]) {
                        duplucado = true
                        console.log('Valor duplicado: ' + nome)
                    } else {
                        nomesLista[nome] = true
                    }
                });
                if (duplucado) {
                    event.preventDefault()
                    alert('Existem itens duplicados na Lista. Corrija por favor.')
                }
            });
        });

        function excluirItem(itemId) {
            var itemElement = document.getElementById(itemId);
            itemElement.parentNode.removeChild(itemElement);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/item/excluir_item/" + itemId, true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status == 200) {
                    console.log("Item excluido com sucesso");
                } else {
                    console.error("Erro ao excluir o item");
                }
            };
            xhr.send();
        }

        document.getElementById('adicionarNovoItemBtn').addEventListener('click', function () {
            var container = document.getElementById('novosItensContainer');
            var newItemDiv = document.createElement('div');
            newItemDiv.classList.add('d-flex', 'mb-3');

            var nomeInput = document.createElement('input');
            nomeInput.type = 'text';
            nomeInput.classList.add('form-control', 'me-2');
            nomeInput.name = 'novosItensNome[]';
            nomeInput.placeholder = 'Nome';
            nomeInput.required = true;

            var quantidadeInput = document.createElement('input');
            quantidadeInput.type = 'number';
            quantidadeInput.classList.add('form-control', 'me-2');
            quantidadeInput.name = 'novosItensQuantidade[]';
            quantidadeInput.placeholder = 'Quantidade';
            quantidadeInput.required = true;

            var removerBtn = document.createElement('button');
            removerBtn.type = 'button';
            removerBtn.classList.add('btn', 'btn-danger');
            removerBtn.textContent = 'Remover';
            removerBtn.addEventListener('click', function () {
                container.removeChild(newItemDiv);
            });

            newItemDiv.appendChild(nomeInput);
            newItemDiv.appendChild(quantidadeInput);
            newItemDiv.appendChild(removerBtn);
            container.appendChild(newItemDiv);
        });
    </script>

</div>