<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{$titulo}</title>

  <!-- Bootstrap CSS -->
<link 
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
  rel="stylesheet"
  integrity="sha384-Gnb38rZCEiS8KjV+H+0+CFYwLg2T8FoylF47CIhtjHekH1rCh6KltK+M6X9F+n3N" 
  crossorigin="anonymous">

</head>
<body>

  <!-- Navbar de ejemplo -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">{$titulo}</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        {if count($secciones) == 0}
            <h3>No hay items</h3>
        {else}
        <ul class="navbar-nav ms-auto">
            {foreach from=$secciones item=item key=key name=name}
                <li class="nav-item">
                    <a class="nav-link active" href="#">{$item}</a>
                </li>                
            {/foreach}
        </ul>
        {/if}
      </div>
    </div>
  </nav>

  <!-- Contenido principal -->
  <div class="container mt-5">
    <h1>Bienvenido a mi página</h1>
    <p class="lead">Esta es una plantilla básica usando Bootstrap 5.</p>
  </div>

  <!-- Bootstrap JS (con Popper) -->
<script 
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
  integrity="sha384-6Bpi7TnjVHXsIq5Az9zEppEJQYk/HEJeVZp6oU96TIkD9zX9eK+7gx3jVPZ5W7CM" 
  crossorigin="anonymous">
</script>

</body>
</html>
