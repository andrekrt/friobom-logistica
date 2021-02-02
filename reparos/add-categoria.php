<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==3 || $_SESSION['tipoUsuario'] == 99){

    $idUsuario = $_SESSION['idUsuario'];

    $nomeCategoria = filter_input(INPUT_POST, 'categoria');

    $consulta = $db->prepare("SELECT * FROM categoria WHERE nome_categoira = :nomeCategoria");
    $consulta->bindValue(':nomeCategoria', $nomeCategoria);
    $consulta->execute();

    if($consulta->rowCount()>0){

        echo "<script>alert('Essa Categoria já está cadastrado!');</script>";
        echo "<script>window.location.href='form-solicitacao.php'</script>";

    }else{

        $sql = $db->prepare("INSERT INTO categoria_peca (nome_categoria) VALUES (:nomeCategoria)");
        $sql->bindValue(':nomeCategoria', $nomeCategoria);
        $sql->execute();

        if($sql){
            echo "<script>alert('Categoria Cadastrada!');</script>";
            echo "<script>window.location.href='form-solicitacao.php'</script>";
        }else{
            echo "erro no cadastro contator o administrador!";
        }

    }

}

?>