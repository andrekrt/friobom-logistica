<?php

session_start();
require("../conexao.php");

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idPeca = filter_input(INPUT_POST, 'idPeca');
    $descricao = filter_input(INPUT_POST, 'descricao');
    $medida = filter_input(INPUT_POST, 'medida');
    $grupo = filter_input(INPUT_POST, 'grupo');
    $estoqueMinimo = filter_input(INPUT_POST, 'estoqueMinimo');

    $atualiza = $db->prepare("UPDATE peca_estoque SET descricao_peca = :descricao, un_medida = :medida, grupo_peca = :grupo, estoque_minimo = :estoqueMinimo WHERE idpeca = :idPeca");
    $atualiza->bindValue(':descricao', $descricao);
    $atualiza->bindValue(':medida', $medida);
    $atualiza->bindValue(':grupo', $grupo);
    $atualiza->bindValue(':estoqueMinimo', $estoqueMinimo);
    $atualiza->bindValue(':idPeca', $idPeca);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='pecas.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{

}

?>