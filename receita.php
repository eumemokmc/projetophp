<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receita</title>
    <?php include "./components/dependencias.php";?>
</head>
<body>
    
    <?php
        include "./components/navbar.php";

        $receita;

        if(isset($_GET['id_receita'])){
            require_once "./utils/banco.php";

            $id_receita = $_GET['id_receita'];
            $q = "SELECT * FROM receitas WHERE id = '$id_receita'";
            $receita = $banco->query($q)->fetch_object();

            $q = "SELECT * FROM ingredientes WHERE id_receita = '$id_receita'";
            $busca = $banco->query($q);
        }else{
            header("Location: ./index.php");
        }
    ?>
    
        <div class="d-flex flex-row justify-content-between align-items-center mb-5" style="margin: 3% 27% 0% 27%;">
            <div class='d-flex align-items-center justify-content-end' style="text-align: center;"><?php
                if(isset($_SESSION["autor"])){
                    $id_autor = $_SESSION["autor"];
                    $id_receita = $receita->id;

                    $q = "SELECT id FROM receitas WHERE id_autor = '$id_autor' AND id = '$id_receita'";
                    
                    $busca2 = $banco->query($q);

                    if($busca2->num_rows == 1){
                    echo "<a href='alterar-receita.php?alterar=$id_receita' class='me-3'><button class='btn btn-warning'>Alterar</button></a>";

                    echo "<h1 class='me-3'>$receita->nome</h1>";

                    echo "<a href='minhas-receitas.php?excluir=$id_receita'><button  class='btn btn-danger'>Remover</button></a>";
                    }else{
                        echo "<h1>$receita->nome</h1>";
                    }

                    
                }else{
                    echo "<h1>$receita->nome</h1>";
                }
            ?></div>
        </div>

        <div style="margin: 0% 30% 5% 30%;">
        <div class="d-flex flex-row justify-content-between align-items-center mb-3">
            <?php
                $horas_preparo = 0;
                $horas_convertidas = $receita->tempo_de_preparo_min/60;

                for($i=0; $horas_convertidas >= 1; $i++) { 
                    $horas_convertidas--;
                    $horas_preparo++;
                }

                $min_preparo = $receita->tempo_de_preparo_min;

                if($horas_preparo >= 1){
                    $min_preparo -= ($horas_preparo*60);
                }
            ?>
            <p><strong>Tempo de preparo da receita:</strong> <?php
                 if($horas_preparo != 0){
                    echo "$horas_preparo h $min_preparo min";
                }else{
                    echo "$min_preparo min";
                }
                ?></p>
            <p><strong>Criação:</strong> <?php echo  $receita->hora_criacao;?></p>

        </div>

        <div style="margin-bottom: 30px;">
            <div class="d-flex flex-row justify-content-between align-items-center mb-3">
                <h2>Ingredientes</h2>
                <?php
                    $icon = "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-plus-circle-fill' viewBox='0 0 16 16'>
                        <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z'/>
                    </svg>";
                        if(isset($_SESSION["autor"])){
                            $id = $receita->id;
                            echo "
                                <a href='./adicionar-ingrediente.php?receita=$id'><button class='btn btn-primary'>$icon Adicionar Ingrediente</button></a>
                            ";
                        }
                        
                ?>
            </div>
            <div>

                
                <ul>
                    <?php
                        if($busca->num_rows == 0){
                            echo "<li>Nenhum ingrediente cadastrado</li>";
                        }else{
                            while($obj = $busca->fetch_object()) {
                                $frase = "$obj->quantidade     $obj->medida de $obj->nome";
                                echo "<li>$frase</li>";
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>

        <h2>Modo de Preparo</h2>
        <div>
            <p><?php echo $receita->modo_de_preparo; ?></p>
        </div>
    </div>
    </div>
    
</body>
</html>