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
            if(isset($_POST["nome"]) && isset($_POST["tempo"]) && isset($_POST["modo"])){
                require_once "./utils/banco.php";
                $id_receita = criarReceita($id_autor, $_POST["nome"], $_POST["modo"], $_POST["tempo"]);

                $_POST["nome"] = null;
                $_POST["tempo"] = null;
                $_POST["modo"] = null;
                
                header("Location: ./receita.php?id_receita=$id_receita");
                
                
            }
        }
    ?>

    <div>
        <form action="" method="post" style="margin: 3% 2% 3% 2%">
            <legend class="text-center"><h1>Criação de uma receita</h1></legend>
            <fieldset class="d-flex flex-column">

                
                <div class="input-group mb-3">
                <span class="input-group-text"><label for="tempo">Tempo mínimo de preparo (minutos)</label></span>
                    <div class="me-5">
                        <input type="number" class="form-control rounded-end " placeholder="Tempo de Preparo" name="tempo" id="tempo">
                    </div>
                    <span class="input-group-text"><label for="nome">Nome da Receita</label></span>
                    <input type="text" class="form-control rounded-end" placeholder="Nome" name="nome" id="nome">
                    
                </div>

                <div class="mb-3">
                    <span class="input-group-text"><label for="modo" >Modo de Preparo</label></span>
                    <textarea class="form-control rounded-end" aria-label="With textarea" rows="5" name="modo" id="modo-preparo" maxlength="5000"></textarea>
                </div>

                
                <button type="submit" class="btn btn-primary ">Criar</button>

            </fieldset>
        </form>
    </div>
</body>
</html>