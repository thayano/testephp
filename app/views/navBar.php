<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Menu</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="modal" data-bs-target="#meuModal">Cadastrar Lista</a>
        </li>
        <li class="nav-item">
        <form id="calcularTotalForm" action="/calcular" method="post">
        <input type="hidden" name="listasSelecionadasJSON" id="listasSelecionadasJSON" value="">

          <a class="nav-link" id="calcularTotalBtn" type="submit">Calcular Total</a>
        </form>
        </li>
      </ul>
    </div>
  </div>
</nav>