<?php

session_start();
require("../conexao.php");
require("funcoes.php");

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $id = filter_input(INPUT_GET, 'idSaida');
    $peca = $db->prepare("SELECT peca_idpeca FROM saida_estoque WHERE idsaida_estoque = :idSaida");
    $peca->bindValue(':idSaida', $id);
    $peca->execute();
    $idPeca = $peca->fetch();
    $idPeca = $idPeca['peca_idpeca'];

    $delete = $db->prepare("DELETE FROM saida_estoque WHERE idsaida_estoque = :idSaida ");
    $delete->bindValue(':idSaida', $id);

    if($delete->execute()){
        atualizaEStoque($idPeca);
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='ordem-servico.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>