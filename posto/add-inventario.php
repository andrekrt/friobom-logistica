<?php

session_start();
require("../conexao.php");
include "funcao.php";

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99)){
    
    $usuario = $_SESSION['idUsuario'];
    $dataInventario = date("Y-m-d");
    $litros = str_replace(",",".",filter_input(INPUT_POST, 'litros'));
    $volumeAnterior = contaEstoque();
    $volumeDivergente = $volumeAnterior-$litros;

    //echo "$dataEntrada<br>$valorlitro<br>$totalLitro<br>$valorTotal<br>$fornecedor<br>$qualidade<br>$usuario";

    $inserir = $db->prepare("INSERT INTO combustivel_inventario (data_inventario, volume_anterior, qtd_encontrada, volume_divergente, usuario) VALUES (:dataInventario, :volumeAnterior, :litros, :volumeDivergente, :usuario)");
    $inserir->bindValue(':dataInventario', $dataInventario);
    $inserir->bindValue(':volumeAnterior', $volumeAnterior);
    $inserir->bindValue(':litros', $litros);
    $inserir->bindValue(':volumeDivergente', $volumeDivergente);
    $inserir->bindValue(':usuario', $usuario);

    if($inserir->execute()){
        echo "<script>alert('Inventário Registrado!');</script>";
        echo "<script>window.location.href='inventario.php'</script>";    
        
    }else{
        print_r($inserir->errorInfo());
    }

}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='inventario.php'</script>"; 
}

?>