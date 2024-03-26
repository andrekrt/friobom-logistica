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

    $db->beginTransaction();

    try{
        $peca = $db->prepare("SELECT peca_idpeca FROM saida_estoque WHERE idsaida_estoque = :idSaida");
        $peca->bindValue(':idSaida', $id);
        $peca->execute();
        $idPeca = $peca->fetch();
        $idPeca = $idPeca['peca_idpeca'];

        $delete = $db->prepare("DELETE FROM saida_estoque WHERE idsaida_estoque = :idSaida ");
        $delete->bindValue(':idSaida', $id);
        $delete->execute();

        atualizaEStoque($idPeca);

        $db->commit();

        $_SESSION['msg'] = 'Serviço e Peça Excluído com Sucesso';
        $_SESSION['icon']='success';
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Excluir Serviço e Peça';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: ordem-servico.php");
exit();
?>