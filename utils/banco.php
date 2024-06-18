<?php

$banco = new mysqli("localhost:3306", "root", "", "receita");

if ($banco->connect_error) {
    die("Conexão falhou: " . $banco->connect_error);
}

function criarAutor($nome, $email, $senha):string {
    global $banco;

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $q = "INSERT INTO autores (nome, email, senha) VALUES ('$nome', '$email', '$senhaHash')";
    $resp = $banco->prepare($q);

    if ($resp) {
        $incluir = $resp->execute();

        if ($incluir) {
            return "Novo autor criado com sucesso.";
        } else {
            $q = "SELECT email FROM autores WHERE email = '$email'";
            $emails = $banco->query($q);
            
            if($emails->num_rows){
                return "Erro! já possui um autor cadastrado com esse email!";
            }else{
                return "Erro ao cadastrar autor, tente novamente.";
            }
        }
    } else {
        return "Erro na preparação da consulta: " . $banco->error;
    }
}

function deletarAutor($id){
    global $banco;

    $q = "DELETE FROM autores WHERE id = '$id'";
    $banco->query($q);
}

function login($email, $senha):bool{
    global $banco;

    $q = "SELECT senha, id FROM autores WHERE email = '$email'";
    $resp = $banco->query($q);

    if($resp->num_rows == 1){
        $valores =  $resp->fetch_object();
        $senha_bd = $valores->senha;
        $id = $valores->id;

        if(password_verify($senha, $senha_bd)){
            session_start();
            $_SESSION["autor"] = $id;
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function logout() {
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
}

function criarReceita($id_autor, $nome, $modo_de_preparo, $tempo_de_preparo_min){
    global $banco;

    $q = "INSERT INTO receitas (id_autor, nome, modo_de_preparo, tempo_de_preparo_min) VALUES ('$id_autor', '$nome', '$modo_de_preparo', '$tempo_de_preparo_min')";
    $banco->query($q);

    $q = "SELECT id FROM receitas WHERE id_autor = '$id_autor'";
    $busca = (($banco->query($q)));
    $max = 0;

    while ($obj_receita = $busca->fetch_object()) {
        if($obj_receita->id > $max){
            $max = $obj_receita->id;
        }
    }

    return $max;    
}

function alterarReceita($id, $nome, $modo_de_preparo, $tempo_de_preparo_min){
    global $banco;

    $q = "UPDATE receitas SET nome ='$nome', modo_de_preparo = '$modo_de_preparo', tempo_de_preparo_min='$tempo_de_preparo_min'  WHERE id = '$id'";
    $banco->query($q);  
}

function deletarReceita($id){
    global $banco;

    $q = "DELETE FROM ingredientes WHERE id_receita = '$id'";
    $banco->query($q);

    $q = "DELETE FROM receitas WHERE id = '$id'";
    $banco->query($q);  
}

function criarIngrediente($nome, $medida, $quantidade, $id_receita){
    global $banco;
    $q = "INSERT INTO ingredientes (nome, medida, quantidade, id_receita)
        VALUES ('$nome', '$medida', '$quantidade', '$id_receita')";
    $teste = $banco->query($q);

    var_dump($teste);
}

function alterarIngrediente($id, $nome, $medida, $quantidade){
    global $banco;

    $q = "UPDATE ingredientes SET nome ='$nome', medida = '$medida', quantidade = '$quantidade' WHERE id = '$id'";
    $banco->query($q);  
}

function deletarIngrediente($id){
    global $banco;

    $q = "DELETE FROM ingredientes WHERE id = '$id'";
    $banco->query($q); 
}