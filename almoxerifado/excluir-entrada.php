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

    $db->beginTransaction();

    try{
        $sqlPeca= $db->prepare("SELECT peca_idpeca FROM entrada_estoque WHERE identrada_estoque=:idEntrada");
        $sqlPeca->bindValue(':idEntrada', $id);
        $sqlPeca->execute();
        $peca=$sqlPeca->fetch();
        $peca=$peca['peca_idpeca'];

        $delete = $db->prepare("DELETE FROM entrada_estoque WHERE identrada_estoque = :idEntrada ");
        $delete->bindValue(':idEntrada', $id);
        $delete->execute();

        atualizaEStoque($peca);

        $db->commit();

        $_SESSION['msg'] = 'Entrada Excluída com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Excluir Entrada';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: entradas.php");
exit();
?>