<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Receita</title>
    <?php include "./components/dependencias.php";?>
</head>
<body>
    <?php
        include "./components/navbar.php";

        if(!isset($_SESSION["autor"])){
            header("Location: ./login.php");
        }
        else{
            $id_autor = $_SESSION["autor"];

            if(!isset($_GET["receita"])){
                header("Location: ./minhas-receitas.php");
            }
            else{
                
                $id_receita = $_GET["receita"];
                $q = "SELECT * FROM receitas WHERE id = '$id_receita' AND id_autor = '$id_autor'";
                require_once "./utils/banco.php";
                $buscar = $banco->query($q);

                if($buscar->num_rows == 0){
                    header("Location: ./minhas-receitas.php");
                }else{
                    $receita = $buscar->fetch_object(); 
                }
            }

            if(isset($_POST["quant"]) && isset($_POST["nome"]) && isset($_POST["medida"])){
                require_once "./utils/banco.php";
                criarIngrediente($_POST["nome"], $_POST["medida"], $_POST["quant"], $receita->id);

                $_POST["nome"] = null;
                $_POST["medida"] = null;
                $_POST["quantidade"] = null;
                
                header("Location: ./receita.php?id_receita=$id_receita");
            }
        }
    ?>

    <div>
        <form action="" method="post" style="margin: 3% 20% 3% 20%">
            <fieldset class="d-flex flex-column">
                <div class="d-flex flex-row align-items-center">
                    <a href="./receita.php?id_receita=<?php echo $id_receita;?>"><button type="button">Voltar</button></a>
                    <legend    class="text-center"><h1>Adicionar Ingrediente</h1></legend>
                </div>
                <div class="input-group mb-3">
                <span class="input-group-text"><label for="quant">Quantidade</label></span>
                    <div class="me-5">
                        <input type="number" class="form-control rounded-end " placeholder="quantidade" name="quant" id="quant">
                    </div>
                    <span class="input-group-text"><label for="nome">Nome do ingrediente</label></span>
                    <input type="text" class="form-control rounded-end" placeholder="Nome" name="nome" id="nome">
                    
                </div>

                <select class="form-select mb-3" aria-label="Default select example" name="medida">
                    <option selected>Selecione uma Medida</option>
                    <option value="grama">grama (g)</option>
                    <option value="unidade">unidade (u)</option>
                    <option value="colher-cha">colher de chá</option>
                    <option value="ml">miliLitro (ml)</option>
                </select>

                
                <button type="submit" class="btn btn-primary ">Adicionar</button>

            </fieldset>
        </form>
    </div>
</body>
</html>