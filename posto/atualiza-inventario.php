<?php

session_start();
require("../conexao.php");
include "funcao.php";

$idModudulo = 13;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    
    $idinventario = filter_input(INPUT_POST, 'id');
    $litros = str_replace(",",".",filter_input(INPUT_POST, 'litros'));
    $volumeAnterior = contaEstoque();
    $volumeDivergente = $volumeAnterior-$litros;
    $usuario = $_SESSION['idUsuario'];

    $db->beginTransaction();

    try{
        $atualizar = $db->prepare("UPDATE combustivel_inventario SET qtd_encontrada = :volume,  usuario=:usuario WHERE idinventario = :id");
        $atualizar->bindValue(':volume', $litros);
        $atualizar->bindValue(':usuario', $usuario);
        $atualizar->bindValue(':id', $idinventario);
        $atualizar->execute();

        $db->commit();

        $_SESSION['msg'] = 'Inventário Atualizar com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Atualizar Inventário';
        $_SESSION['icon']='error';
    }

    

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}
header("Location: inventario.php");
exit();
?>