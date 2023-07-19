<?php

session_start();
require("../conexao.php");

$idModudulo = 5;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $id = filter_input(INPUT_POST,'id');
    $nome = filter_input(INPUT_POST, 'nome');
    $cpf = filter_input(INPUT_POST, 'cpf');
    $salario = str_replace(",",".",filter_input(INPUT_POST, 'salario')) ;
    $rota = filter_input(INPUT_POST, 'rota'); 

    $atualiza = $db->prepare(" UPDATE auxiliares_rota SET nome_auxiliar = :nome, cpf_auxiliar = :cpf, salario_auxiliar = :salario, rota = :rota WHERE idauxiliares = :idauxiliares");
    $atualiza->bindValue(':nome', $nome);
    $atualiza->bindValue(':cpf', $cpf);
    $atualiza->bindValue(':salario', $salario);
    $atualiza->bindValue(':rota', $rota);
    $atualiza->bindValue(':idauxiliares', $id);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='auxiliares.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{
    echo "Erro";
}

?>