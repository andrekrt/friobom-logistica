<?php

session_start();
require("../conexao.php");

$idModudulo = 5;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $mesAno = filter_input(INPUT_POST, 'mesAno');
    $funcionarios = filter_input(INPUT_POST, 'funcionarios');
    $pagamento = str_replace(",",".",filter_input(INPUT_POST, 'pagamento'));
    $usuario = $_SESSION['idUsuario'];
    $idPagamento = filter_input(INPUT_POST, 'id');

    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE folha_pagamento SET mes_ano=:mesAno, pagamento=:pagamento, tipo_funcionarios=:funcionarios, usuario=:usuario WHERE idpagamento = :id ");
        $sql->bindValue(':mesAno', $mesAno);
        $sql->bindValue(':pagamento', $pagamento);
        $sql->bindValue(':funcionarios', $funcionarios);
        $sql->bindValue(':usuario', $usuario);
        $sql->bindValue(':id', $idPagamento);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Pagamento Atualizado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();

        $_SESSION['msg'] = 'Erro ao Atualizar Pagamento';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: pagamentos.php");
exit();
?>