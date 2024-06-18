<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php include "./components/dependencias.php";?>
    
</head>
<body>
    <?php
        session_start();

        if(isset($_SESSION["autor"])){
            require_once './utils/banco.php';
            logout();
            session_start();
        }
        
        include './components/navbar.php';
        $email_class = "";
        $label_email = "";
        
        $senha_class = "";
        $label_senha= "";

        if(isset($_POST["email"]) && isset($_POST["senha"])){
            $email = $_POST["email"];
            $senha = $_POST["senha"];

            require_once './utils/banco.php';
            $q = "SELECT senha FROM autores WHERE email = '$email'";
            $busca = $banco->query($q);
            
            if($busca->num_rows != 1){
                $email_class = "is-invalid";
                $label_email = "Email invalido!";
            }else{
                $senha_bd = $busca->fetch_object()->senha;
                
                if(!password_verify($senha, $senha_bd)){
                    $senha_class = "is-invalid";
                    $label_senha = "senha incorreta!";
                }else{
                    $q = "SELECT id FROM autores WHERE email = '$email'";
                    $busca = $banco->query($q);

                    $_SESSION["autor"] = $busca->fetch_object()->id;

                    header("Location: ./index.php");
                }
            }
        }
    ?>

    <form style="margin: 10% 30% 0% 30%;" action="" method="post">
        <fieldset class="form d-flex flex-column">
            <legend class="mb-3">Login de Autor de Receitas</legend>
            <div class="form-floating mb-3">
                <input type="email" class="form-control <?php echo $email_class;?>" id="email" placeholder="name@example.com" name="email">
                <label for="email">Email</label>
                <div class="text-danger"><?php echo $label_email;?></div>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control <?php echo $senha_class;?>" id="senha" name="senha" placeholder="name@example.com">
                <label for="senha">Senha</label>
                <div class="text-danger"><?php echo $label_senha;?></div>
            </div>
            
            <button button type="submit" class="btn btn-primary">Entrar</button>
        </fieldset>
    </form>
</body>
</html>