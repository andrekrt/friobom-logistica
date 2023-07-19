<?php

session_start();
require("../conexao.php");

$idModudulo = 9;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $descricao = filter_input(INPUT_POST, 'descricao');
    $categoria = filter_input(INPUT_POST, 'categoria');
    $medida = filter_input(INPUT_POST, 'medida');

    $inserir = $db->prepare("INSERT INTO peca_reparo (descricao, categoria, un_medida) VALUES (:descricao, :categoria, :medida)" );
    $inserir->bindValue(':descricao', $descricao);
    $inserir->bindValue(':categoria', $categoria);
    $inserir->bindValue(':medida', $medida);
    if($inserir->execute()){
        echo "<script> alert('Cadastrado com Sucesso!')</script>";
        echo "<script> window.location.href='pecas.php' </script>";
    }else{
        print_r($inserir->errorInfo());
    }
   
}else{

    echo "<script> alert('Acesso não permitido!')</script>";
    echo "<script> window.location.href='pecas.php' </script>";

}

?>