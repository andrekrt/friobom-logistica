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
    
    $usuario = $_SESSION['idUsuario'];
    $dataInventario = date("Y-m-d");
    $litros = str_replace(",",".",filter_input(INPUT_POST, 'litros'));
    $volumeAnterior = contaEstoque();
    $volumeDivergente = $volumeAnterior-$litros;

    //echo "$dataEntrada<br>$valorlitro<br>$totalLitro<br>$valorTotal<br>$fornecedor<br>$qualidade<br>$usuario";

    $inserir = $db->prepare("INSERT INTO combustivel_inventario (data_inventario , qtd_encontrada, usuario) VALUES (:dataInventario, :litros, :usuario)");
    $inserir->bindValue(':dataInventario', $dataInventario);
    $inserir->bindValue(':litros', $litros);
    $inserir->bindValue(':usuario', $usuario);

    if($inserir->execute()){
        $sqlExtrato = $db->prepare("INSERT INTO combustivel_extrato (data_operacao, tipo_operacao, volume, usuario) VALUES (:dataOp, :tipoOp, :volume, :usuario)");
        $sqlExtrato->bindValue(':dataOp', $dataInventario);
        $sqlExtrato->bindValue(':tipoOp', "Inventário");
        $sqlExtrato->bindValue(':volume', $litros);
        $sqlExtrato->bindValue(':usuario', $usuario);
        if($sqlExtrato->execute()){
            echo "<script>alert('Inventário Registrado!');</script>";
            echo "<script>window.location.href='inventario.php'</script>"; 
        }else{
            print_r($sqlExtrato->errorInfo());
        }
           
        
    }else{
        print_r($inserir->errorInfo());
    }

}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='inventario.php'</script>"; 
}

?>