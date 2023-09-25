<?php

session_start();
require("../conexao.php");
include('funcoes.php');

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $id = filter_input(INPUT_GET, 'idEntrada');

    //pegar a peça
    $sqlPeca= $db->prepare("SELECT peca_idpeca FROM entrada_estoque WHERE identrada_estoque=:idEntrada");
    $sqlPeca->bindValue(':idEntrada', $id);
    $sqlPeca->execute();
    $peca=$sqlPeca->fetch();
    $peca=$peca['peca_idpeca'];

    $delete = $db->prepare("DELETE FROM entrada_estoque WHERE identrada_estoque = :idEntrada ");
    $delete->bindValue(':idEntrada', $id);

    if($delete->execute()){
        atualizaEStoque($peca);
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='entradas.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>