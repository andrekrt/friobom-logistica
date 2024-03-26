<?php

session_start();
require("../conexao.php");

$idModudulo = 13;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $id = filter_input(INPUT_GET, 'idEntrada');

    $db->beginTransaction();

    try{
        $delete = $db->prepare("DELETE FROM combustivel_entrada WHERE idcombustivel_entrada = :idEntrada ");
        $delete->bindValue(':idEntrada', $id);
        $delete->execute();

        $db->commit();

        $_SESSION['msg'] = 'Entrada Excluída com Sucesso!';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Excluir Entrada';
        $_SESSION['icon']='error';
    }

    

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';

}
header("Location: entradas.php");
exit();
?>