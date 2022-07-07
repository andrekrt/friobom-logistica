<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 8 || $_SESSION['tipoUsuario'] == 99)){
    
    $usuario = $_SESSION['idUsuario'];
    $dataEntrada = date("Y-m-d");
    $valorlitro = str_replace(",",".",filter_input(INPUT_POST, 'vlLitro'));
    $totalLitro =  str_replace(",",".",filter_input(INPUT_POST, 'totalLt'));
    $valorTotal = number_format($valorlitro*$totalLitro,2,".","");
    $fornecedor = filter_input(INPUT_POST, 'fornecedor') ;
    $qualidade = filter_input(INPUT_POST, 'qualidade');

    //echo "$dataEntrada<br>$valorlitro<br>$totalLitro<br>$valorTotal<br>$fornecedor<br>$qualidade<br>$usuario";

    $inserir = $db->prepare("INSERT INTO combustivel_entrada (data_entrada, total_litros, valor_litro, valor_total, fornecedor, qualidade, usuario) VALUES (:dataEntrada, :totalLitros, :valorLitros, :valorTotal, :fornecedor, :qualidade, :usuario)");
    $inserir->bindValue(':dataEntrada', $dataEntrada);
    $inserir->bindValue(':totalLitros', $totalLitro);
    $inserir->bindValue(':valorLitros', $valorlitro);
    $inserir->bindValue(':valorTotal', $valorTotal);
    $inserir->bindValue(':fornecedor', $fornecedor);
    $inserir->bindValue(':qualidade', $qualidade);
    $inserir->bindValue(':usuario', $usuario);

    if($inserir->execute()){
        echo "<script>alert('Entrada Lançada com Sucesso!');</script>";
        echo "<script>window.location.href='entradas.php'</script>";    
        
    }else{
        print_r($inserir->errorInfo());
    }

}

?>