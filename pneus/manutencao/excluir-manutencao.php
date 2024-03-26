<?php

session_start();
require("../../conexao.php");

$idModudulo = 12;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idManutencao = filter_input(INPUT_GET, 'idmanutencao');

    $db->beginTransaction();

    try{
        $sql = $db->prepare("DELETE FROM manutencao_pneu WHERE idmanutencao_pneu = :idManutencao");
        $sql->bindValue(':idManutencao', $idManutencao);
        $sql->execute();

        
        $db->commit();

        $_SESSION['msg'] = 'Manutenção Excluída com Sucesso';
        $_SESSION['icon']='success';
        
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Excluir Manutenção';
        $_SESSION['icon']='error';
    }

    header("Location: manutencoes.php");
    exit();    

}else{

}

?>