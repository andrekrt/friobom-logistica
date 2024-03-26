<?php

session_start();
require("../conexao.php");

$idModudulo = 8;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $token = filter_input(INPUT_GET, 'token');

    $db->beginTransaction();

    try{
        $atualiza = $db->prepare("UPDATE denegadas SET situacao = :situacao WHERE token = :token");
        $atualiza->bindValue(':situacao', "Confirmado");
        $atualiza->bindValue(':token', $token);
        $atualiza->execute();

        $db->commit();

        $_SESSION['msg'] = 'NFs  Confirmadas com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Confirma NF\'s';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: denegadas.php");
exit();
?>