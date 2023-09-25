<?php

session_start();
require("../conexao.php");
include 'funcoes.php';

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $id = filter_input(INPUT_GET, 'idordemServico');

    //pegar saida vinculada a essa os, pegar a peça e excluir a saida e contabilizar o novo estoque
    $sqlSaida = $db->prepare("SELECT peca_idpeca FROM saida_estoque WHERE os=:idos");
    $sqlSaida->bindValue(':idos', $id);
    $sqlSaida->execute();
    $peca = $sqlSaida->fetchColumn();

    $delete = $db->prepare("DELETE FROM ordem_servico WHERE idordem_servico = :idordemServico ");
    $delete->bindValue(':idordemServico', $id);

    if($delete->execute()){
        atualizaEStoque($peca);
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='ordem-servico.php' </script>";
        
    }else{
        print_r($delete->errorInfo());
    }

}else{
    echo "Erro";
}

?>