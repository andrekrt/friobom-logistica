<?php

session_start();
require("../conexao.php");
include("../thermoking/funcao.php");

$idModudulo = 7;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $idUsuario = $_SESSION['idUsuario'];
    $idDespesa = filter_input(INPUT_GET, 'id');
    $status = "Confirmado";    
    $dataAprovaca = date("Y-m-d H:i");

    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE viagem SET situacao=:situacao, data_aprovacao=:dataAprovacao WHERE iddespesas = :idDespesa");
        $sql->bindValue(':situacao', $status);
        $sql->bindValue(':dataAprovacao', $dataAprovaca);
        $sql->bindValue(':idDespesa', $idDespesa );
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Despesa Confirmada com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Confirmar Despesa';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: despesas.php");
exit();
?>