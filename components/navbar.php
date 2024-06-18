<?php
if(!isset($_SESSION)){
  session_start();
}
?>
<nav class="navbar navbar-expand-lg mb-5" style="background-color: #353940;">
  <div class="container-fluid">
    <div class="d-flex">
      <a class="nav-link active me-4" aria-current="page" href="./index.php" style="color: #C5D0D9;">
        Home
      </a>
      <?php
        if(isset($_SESSION["autor"])){
      
          ?><a class="nav-link active" aria-current="page" href="./minhas-receitas.php" style="color: #C5D0D9;">
            Minhas Receitas
          </a><?php
        }
      ?>
    </div>

            
    <form class="d-flex" role="search" method="get" action="./index.php">
      <input class="form-control mr-1 me-2" type="search" placeholder="Pesquisa" aria-label="Search" name="q">
      <button class="btn btn-outline-success" type="submit">Pesquisar</button>
    </form>
    
    <?php
      if(isset($_SESSION["autor"])){
        ?><a href="./login.php">
          <button type="button" class="btn btn-outline-danger">Logout</button>
        </a><?php
      }else{
        ?><a href="./login.php">
          <button type="button" class="btn btn-outline-primary">Login</button>
        </a><?php
      }
    ?>
      </div>
</nav>