<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 8 || $_SESSION['tipoUsuario'] == 99)){
    
    $idEntrada = filter_input(INPUT_POST, 'id');
    $valorlitro = str_replace(",",".",filter_input(INPUT_POST, 'vlLitro'));
    $totalLitro =  str_replace(",",".",filter_input(INPUT_POST, 'totalLt'));
    $valorTotal = number_format($valorlitro*$totalLitro,2);
    $fornecedor = filter_input(INPUT_POST, 'fornecedor') ;
    $qualidade = filter_input(INPUT_POST, 'qualidade');

    //echo "$dataEntrada<br>$valorlitro<br>$totalLitro<br>$valorTotal<br>$fornecedor<br>$qualidade<br>$usuario";

    $inserir = $db->prepare("UPDATE combustivel_entrada SET total_litros = :totalLitros, valor_litro = :valorLitro, valor_total = :valorTotal, fornecedor = :fornecedor, qualidade = :qualidade WHERE idcombustivel_entrada = :id");
    $inserir->bindValue(':totalLitros', $totalLitro);
    $inserir->bindValue(':valorLitro', $valorlitro);
    $inserir->bindValue(':valorTotal', $valorTotal);
    $inserir->bindValue(':fornecedor', $fornecedor);
    $inserir->bindValue(':qualidade', $qualidade);
    $inserir->bindValue(':id', $idEntrada);

    if($inserir->execute()){
        echo "<script>alert('Entrada Atualizada com Sucesso!');</script>";
        echo "<script>window.location.href='entradas.php'</script>";    
        
    }else{
        print_r($inserir->errorInfo());
    }

}

?>