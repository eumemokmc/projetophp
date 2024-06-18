<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indice</title>
    <?php include "./components/dependencias.php";?>
</head>
<body>
    <div>
        <?php
            include "./components/navbar.php";

            if(isset($_SESSION["autor"])){
                $id_autor = $_SESSION["autor"];

                if(isset($_GET["excluir"])){
                    $id_excluir = $_GET["excluir"];

                    require_once "./utils/banco.php";
                    deletarReceita($id_excluir);
                    header("Location: ./minhas-receitas.php");
                }
            }else{
                header("Location: ./login.php");
            }
        ?>
    </div>

    <div >    
        <div class="d-flex justify-content-between align-items-end">
            <h1 class="ml-3 mb-3">Minhas Receitas</h1>
            <a href="./criar-receita.php" class="me-3 mb-1">
                <button class="btn btn-primary d-flex flex-row justify-content-between align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                    </svg>
                    Adicionar Receita
                </button>
            </a>
        </div>

        <?php 
            $pesquisa = "";
            if(isset($_GET["q"])){
                $pesquisa = $_GET["q"];
                echo "<p class='ml-3'>Pesquisando por: <em>\"".$_GET["q"]."\"</em></p>";
            }
            require_once "./utils/banco.php";

            $q = "SELECT * FROM receitas WHERE id_autor = '$id_autor'";
            if($pesquisa != ""){
                $q = "SELECT * FROM receitas WHERE nome  LIKE '%$pesquisa%'";
            }
            
            $busca = $banco->query($q);
        ?>


        <div class="container-table">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Tempo de preparo</th>
                        <th>Crianção do post</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($busca->num_rows > 0) {
                        while ($obj_receita = $busca->fetch_object()) {

                            $horas_preparo = 0;
                            $horas_convertidas = $obj_receita->tempo_de_preparo_min/60;

                            for($i=0; $horas_convertidas >= 1; $i++) { 
                                $horas_convertidas--;
                                $horas_preparo++;
                            }

                            $min_preparo = $obj_receita->tempo_de_preparo_min;

                            if($horas_preparo >= 1){
                                $min_preparo -= ($horas_preparo*60);
                            }

                            date_default_timezone_set('America/Sao_Paulo'); 

                            $interval = (new DateTime())->diff(new DateTime($obj_receita->hora_criacao));

                            
                            $anos = $interval->format('%y');
                            $meses = $interval->format('%m');
                            $dias = $interval->format('%d');
                            $horas = $interval->format('%h');
                            $minutos = $interval->format('%i');

                            $data_criacao = ""; 

                            if($anos != 0){
                                $data_criacao .= "$anos ano";

                                if($anos > 1){
                                    $data_criacao .= "s";
                                }

                                $data_criacao .= "atrás";
                            }else if($meses != 0){
                                $data_criacao .= "$meses mês"; 

                                if($meses > 1){
                                    $data_criacao .= "es";
                                }

                                $data_criacao .= "atrás";
                            }else if($dias != 0){
                                $data_criacao .= "$dias dia"; 

                                if($meses > 1){
                                    $data_criacao .= "s";
                                }

                                $data_criacao .= "atrás";
                            }else if($horas != 0){
                                $data_criacao .= "$horas h atrás"; 

                            }else if($minutos != 0){
                                $data_criacao .= "$minutos min atrás"; 
                            }else{
                                 $data_criacao .= "Agora";
                            }
                        
                            echo "<tr onClick='redirect($obj_receita->id)'>";
                                echo "<td>$obj_receita->id</td>";
                                echo "<td>$obj_receita->nome</td>";

                                if($horas_preparo != 0){
                                    echo "<td>$horas_preparo h $min_preparo min";
                                }else{
                                    echo "<td>$min_preparo min</td>";
                                }
                                echo "<td>$data_criacao</td>";


                                echo "<td class='d-flex justify-content-end'>";
                                    echo "<a href='alterar-receita.php?alterar=$obj_receita->id' class='me-3'><button class='btn btn-warning'>Alterar</button></a>";
                                    echo "<a href='minhas-receitas.php?excluir=$obj_receita->id' ><button class='btn btn-danger'>Remover</button></a>";
                                echo "</td>";
                            echo "</tr>";
                        }
                    }else {
                    echo "<tr><td colspan='5' class='text-center'>Nenhuma receita encontrada</td></tr>";
                    
                    }
            ?>
                </tbody>
        </div>
        
    </div>   
</body>

<script>
    function redirect(id) {
        window.location = `./receita.php?id_receita=${id}`;
    }
</script>
</html>