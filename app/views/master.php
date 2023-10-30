<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<style>
  .bd-placeholder-img {
    font-size: 1.125rem;
    text-anchor: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
  }

  @media (min-width: 768px) {
    .bd-placeholder-img-lg {
      font-size: 3.5rem;
    }
  }

  .b-example-divider {
    width: 100%;
    height: 3rem;
    background-color: rgba(0, 0, 0, .1);
    border: solid rgba(0, 0, 0, .15);
    border-width: 1px 0;
    box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
  }

  .b-example-vr {
    flex-shrink: 0;
    width: 1.5rem;
    height: 100vh;
  }

  .bi {
    vertical-align: -.125em;
    fill: currentColor;
  }

  .nav-scroller {
    position: relative;
    z-index: 2;
    height: 2.75rem;
    overflow-y: hidden;
  }

  .nav-scroller .nav {
    display: flex;
    flex-wrap: nowrap;
    padding-bottom: 1rem;
    margin-top: -1px;
    overflow-x: auto;
    text-align: center;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
  }

  .btn-bd-primary {
    --bd-violet-bg: #712cf9;
    --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

    --bs-btn-font-weight: 600;
    --bs-btn-color: var(--bs-white);
    --bs-btn-bg: var(--bd-violet-bg);
    --bs-btn-border-color: var(--bd-violet-bg);
    --bs-btn-hover-color: var(--bs-white);
    --bs-btn-hover-bg: #6528e0;
    --bs-btn-hover-border-color: #6528e0;
    --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
    --bs-btn-active-color: var(--bs-btn-hover-color);
    --bs-btn-active-bg: #5a23c8;
    --bs-btn-active-border-color: #5a23c8;
  }

  .bd-mode-toggle {
    z-index: 1500;
  }

  .bd-mode-toggle .dropdown-menu .active .bi {
    display: block !important;
  }
</style>


<body>
    <?php include "navBar.php" ?>
    <div id="container">
        <div class="container">
            <main>
                <div class="py-5 text-center">
                    <h2>Lista de Compras</h2>

                </div>
                <div class="container">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                        <?php echo $this->section('content') ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="meuModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Criar Lista de Compras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <form method="post" action="/lista/create" id="form">
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="nomeLista" class="form-label">Titulo da Lista:</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="mb-3" id="itensLista">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Nome do Item" name="nomeProduto[]"
                                    required>
                                <input type="number" class="form-control" placeholder="Quantidade"
                                    name="quantidadeItem[]" required>
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="adicionarCampo()">Adicionar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var calcularTotalBtn = document.getElementById("calcularTotalBtn");
            calcularTotalBtn.addEventListener("click", function () {
                var checkboxes = document.querySelectorAll('input[name="listasSelecionadas[]"]:checked');
                var listasSelecionadas = Array.from(checkboxes).map(function (checkbox) {
                    return checkbox.value;
                });
                console.log(listasSelecionadas)
                document.getElementById("listasSelecionadasJSON").value = JSON.stringify(listasSelecionadas);
                document.querySelector('#calcularTotalForm').submit();
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            var formulario = document.getElementById('form')
            formulario.addEventListener('submit', function (event) {
                var nomeInputs = formulario.querySelectorAll('input[name="nomeProduto[]"]');
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

        function adicionarCampo() {
            const divItensLista = document.getElementById('itensLista');
            const inputGroup = document.createElement('div');
            inputGroup.classList.add('input-group', 'mb-3');

            const inputNomeProduto = document.createElement('input');
            inputNomeProduto.type = 'text';
            inputNomeProduto.classList.add('form-control');
            inputNomeProduto.placeholder = 'Nome do Item';
            inputNomeProduto.name = 'nomeProduto[]';
            inputNomeProduto.required = true;

            const inputQuantidadeItem = document.createElement('input');
            inputQuantidadeItem.type = 'number';
            inputQuantidadeItem.classList.add('form-control');
            inputQuantidadeItem.placeholder = 'Quantidade';
            inputQuantidadeItem.name = 'quantidadeItem[]';
            inputQuantidadeItem.required = true;

            const buttonRemover = document.createElement('button');
            buttonRemover.type = 'button';
            buttonRemover.classList.add('btn', 'btn-outline-danger');
            buttonRemover.textContent = 'Remover';
            buttonRemover.addEventListener('click', () => {
                divItensLista.removeChild(inputGroup);
            });

            inputGroup.appendChild(inputNomeProduto);
            inputGroup.appendChild(inputQuantidadeItem);
            inputGroup.appendChild(buttonRemover);
            divItensLista.appendChild(inputGroup);
        }
    </script>
</body>

</html>