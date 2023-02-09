<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 4 )){
    
    $idEntrada = filter_input(INPUT_POST, 'id');
    $valorlitro = str_replace(",",".",filter_input(INPUT_POST, 'vlLitro'));
    $totalLitro =  str_replace(",",".",filter_input(INPUT_POST, 'totalLt'));
    $frete = str_replace(",",".",filter_input(INPUT_POST, 'frete'));
    $valorTotal = number_format(($valorlitro*$totalLitro)+$frete,2,".","");
    $fornecedor = filter_input(INPUT_POST, 'fornecedor') ;
    $qualidade = filter_input(INPUT_POST, 'qualidade');
    $situacao = filter_input(INPUT_POST, 'situacao');
    $nf= filter_input(INPUT_POST, 'nf');
    $dataEntrada = filter_input(INPUT_POST, 'dataEntrada');
    $usuario=$_SESSION['idUsuario'];

    //echo "$dataEntrada<br>$valorlitro<br>$totalLitro<br>$valorTotal<br>$fornecedor<br>$qualidade<br>$usuario";

    $inserir = $db->prepare("UPDATE combustivel_entrada SET total_litros = :totalLitros, valor_litro = :valorLitro, frete=:frete, valor_total = :valorTotal, nf=:nf, fornecedor = :fornecedor, qualidade = :qualidade, situacao=:situacao WHERE idcombustivel_entrada = :id");
    $inserir->bindValue(':totalLitros', $totalLitro);
    $inserir->bindValue(':valorLitro', $valorlitro);
    $inserir->bindValue(':frete', $frete);
    $inserir->bindValue(':valorTotal', $valorTotal);
    $inserir->bindValue(':nf', $nf);
    $inserir->bindValue(':fornecedor', $fornecedor);
    $inserir->bindValue(':qualidade', $qualidade);
    $inserir->bindValue(':situacao', $situacao);
    $inserir->bindValue(':id', $idEntrada);

    //CONSULTAR FORNECEDOR
    $sqlFornecedor = $db->prepare("SELECT * FROM fornecedores WHERE id=:id");
    $sqlFornecedor->bindValue(':id', $fornecedor);
    $sqlFornecedor->execute();
    $nomeFornecedor = $sqlFornecedor->fetch();
    $nomeFornecedor=$nomeFornecedor['nome_fantasia'];

    if($inserir->execute()){
        $sqlExtrato = $db->prepare("INSERT INTO combustivel_extrato (data_operacao, tipo_operacao, volume, placa, usuario) VALUES (:dataOp, :tipoOp, :volume, :placa, :usuario)");
        $sqlExtrato->bindValue(':dataOp', $dataEntrada);
        $sqlExtrato->bindValue(':tipoOp', "Entrada");
        $sqlExtrato->bindValue(':volume', $totalLitro);
        $sqlExtrato->bindValue(':placa', $nomeFornecedor);
        $sqlExtrato->bindValue(':usuario', $usuario);
        if($sqlExtrato->execute()){
            echo "<script>alert('Entrada Atualizada com Sucesso!');</script>";
            echo "<script>window.location.href='entradas.php'</script>";
        }else{
            print_r($sqlExtrato->errorInfo());
        }
            
        
    }else{
        print_r($inserir->errorInfo());
    }

}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='entradas.php'</script>"; 
}

?>