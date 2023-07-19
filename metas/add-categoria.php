<?php

session_start();
require("../conexao.php");

$idModudulo = 15;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idUsuario = $_SESSION['idUsuario'];
    $descricao = filter_input(INPUT_POST, 'descricao');

    $inserir = $db->prepare("INSERT INTO metas_tipo (descricao_tipo, usuario) VALUES (:descricao, :usuario)");
    $inserir->bindValue(':descricao', $descricao);
    $inserir->bindValue(':usuario', $idUsuario);

    if($inserir->execute()){
        echo "<script>alert('Categoria Cadastrado com Sucesso!');</script>";
        echo "<script>window.location.href='tipo-metas.php'</script>";
    }else{
        print_r($inserir->errorInfo());
    }

}

?>