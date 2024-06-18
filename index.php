<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <div>
        <?php
            include "./components/navbar.php";
        ?>
    </div>

    <div >    
        <h1 class="mb-3" style="margin-left: 2%;">Lista de Receitas</h1>

        <?php 
            $pesquisa = "";
            if(isset($_GET["q"])){
                $pesquisa = $_GET["q"];
                echo "<p class='ml-3'>Pesquisando por: <em>\"".$_GET["q"]."\"</em></p>";
            }
            require_once "./utils/banco.php";

            $q = "SELECT * FROM receitas";
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
                        <th>Autor</th>
                        <th>Tempo de preparo</th>
                        <th>Crianção do post</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($busca->num_rows > 0) {
                        while ($obj_receita = $busca->fetch_object()) { 
                            $id_autor = $obj_receita->id_autor;
                            $q = "SELECT nome FROM autores WHERE id ='$id_autor'";
                            $nomeAutor = $banco->query($q)->fetch_object()->nome;

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
                                echo "<td>$nomeAutor</td>";

                                if($horas_preparo != 0){
                                    echo "<td>$horas_preparo h $min_preparo min";
                                }else{
                                    echo "<td>$min_preparo min</td>";
                                }
                                echo "<td>$data_criacao</td>";
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