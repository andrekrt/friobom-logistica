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

    $idinventario = filter_input(INPUT_POST, 'id');
    $peca = filter_input(INPUT_POST, 'peca');
    $qtd = filter_input(INPUT_POST, 'qtd');

    $atualiza = $db->prepare("UPDATE inventario_almoxarifado SET peca = :peca, qtd = :qtd WHERE idinventario = :id");
    $atualiza->bindValue(':peca', $peca);
    $atualiza->bindValue(':qtd', $qtd);
    $atualiza->bindValue(':id', $idinventario);

    if($atualiza->execute()){
        $atualizaEstoque = $db->prepare("UPDATE peca_estoque SET qtd_inv = :qtd WHERE idpeca = :idpeca ");
        $atualizaEstoque->bindValue(':idpeca', $peca);
        $atualizaEstoque->bindValue(':qtd', $qtd);
        if($atualizaEstoque->execute()){
            echo "<script> alert('Atualizado com Sucesso!')</script>";
            echo "<script> window.location.href='inventario.php' </script>";
        }
        
    }else{
        print_r($atualiza->errorInfo());
    }

}else{

}

?>