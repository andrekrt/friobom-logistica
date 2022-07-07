<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 8 || $_SESSION['tipoUsuario'] == 99)){
    
    $usuario = $_SESSION['idUsuario'];
    $dataInventario = date("Y-m-d");
    $litros = str_replace(",",".",filter_input(INPUT_POST, 'litros'));

    //echo "$dataEntrada<br>$valorlitro<br>$totalLitro<br>$valorTotal<br>$fornecedor<br>$qualidade<br>$usuario";

    $inserir = $db->prepare("INSERT INTO combustivel_inventario (data_inventario, qtd_encontrada, usuario) VALUES (:dataInventario, :litros, :usuario)");
    $inserir->bindValue(':dataInventario', $dataInventario);
    $inserir->bindValue(':litros', $litros);
    $inserir->bindValue(':usuario', $usuario);

    if($inserir->execute()){
        echo "<script>alert('Inventário Registrado!');</script>";
        echo "<script>window.location.href='inventario.php'</script>";    
        
    }else{
        print_r($inserir->errorInfo());
    }

}

?>