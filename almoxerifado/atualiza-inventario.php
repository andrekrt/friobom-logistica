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

    $db->beginTransaction();

    try{
        $atualiza = $db->prepare("UPDATE inventario_almoxarifado SET peca = :peca, qtd = :qtd WHERE idinventario = :id");
        $atualiza->bindValue(':peca', $peca);
        $atualiza->bindValue(':qtd', $qtd);
        $atualiza->bindValue(':id', $idinventario);
        $atualiza->execute();

        $atualizaEstoque = $db->prepare("UPDATE peca_reparo SET qtd_inv = :qtd WHERE id_peca_reparo = :idpeca ");
        $atualizaEstoque->bindValue(':idpeca', $peca);
        $atualizaEstoque->bindValue(':qtd', $qtd);
        $atualizaEstoque->execute();

        $db->commit();

        $_SESSION['msg'] = 'Inventário Atualizado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Realizar Inventário ';
        $_SESSION['icon']='error';
    }

    header("Location: inventario.php");
    exit();
}else{

}

?>