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

    $atualizar = $db->prepare("UPDATE combustivel_inventario SET qtd_encontrada = :volume, volume_anterior=:volumeAnterior, volume_divergente=:volumeDivergente, usuario=:usuario WHERE idinventario = :id");
    $atualizar->bindValue(':volume', $litros);
    $atualizar->bindValue(':volumeAnterior', $volumeAnterior);
    $atualizar->bindValue(':volumeDivergente', $volumeDivergente);
    $atualizar->bindValue(':usuario', $usuario);
    $atualizar->bindValue(':id', $idinventario);

    if($atualizar->execute()){
        echo "<script>alert('Inventario Atualizada com Sucesso!');</script>";
        echo "<script>window.location.href='inventario.php'</script>";    
        
    }else{
        print_r($atualizar->errorInfo());
    }

}else{
    echo "<script>alert('Sem permissão');</script>";
    echo "<script>window.location.href='inventario.php'</script>";   
}

?>