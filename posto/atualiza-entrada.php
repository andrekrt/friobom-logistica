<?php

session_start();
require("../conexao.php");

$idModudulo = 13;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    
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
    $dataSituacao = date('Y-m-d');

    $db->beginTransaction();

    try{
        $inserir = $db->prepare("UPDATE combustivel_entrada SET total_litros = :totalLitros, valor_litro = :valorLitro, frete=:frete, valor_total = :valorTotal, nf=:nf, fornecedor = :fornecedor, qualidade = :qualidade, situacao=:situacao, data_aprovacao=:dataAprovacao WHERE idcombustivel_entrada = :id");
        $inserir->bindValue(':totalLitros', $totalLitro);
        $inserir->bindValue(':valorLitro', $valorlitro);
        $inserir->bindValue(':frete', $frete);
        $inserir->bindValue(':valorTotal', $valorTotal);
        $inserir->bindValue(':nf', $nf);
        $inserir->bindValue(':fornecedor', $fornecedor);
        $inserir->bindValue(':qualidade', $qualidade);
        $inserir->bindValue(':situacao', $situacao);
        $inserir->bindValue(':dataAprovacao', $dataSituacao);
        $inserir->bindValue(':id', $idEntrada);
        $inserir->execute();

        //CONSULTAR FORNECEDOR
        $sqlFornecedor = $db->prepare("SELECT * FROM fornecedores WHERE id=:id");
        $sqlFornecedor->bindValue(':id', $fornecedor);
        $sqlFornecedor->execute();
        $nomeFornecedor = $sqlFornecedor->fetch();
        $nomeFornecedor=$nomeFornecedor['nome_fantasia'];

        $sqlExtrato = $db->prepare("INSERT INTO combustivel_extrato (data_operacao, tipo_operacao, volume, placa, usuario) VALUES (:dataOp, :tipoOp, :volume, :placa, :usuario)");
        $sqlExtrato->bindValue(':dataOp', $dataEntrada);
        $sqlExtrato->bindValue(':tipoOp', "Entrada");
        $sqlExtrato->bindValue(':volume', $totalLitro);
        $sqlExtrato->bindValue(':placa', $nomeFornecedor);
        $sqlExtrato->bindValue(':usuario', $usuario);
        $sqlExtrato->execute();

        $db->commit();

        $_SESSION['msg'] = 'Entrada Atualizada com Sucesso!';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Atualizar Entrada';
        $_SESSION['icon']='error';
    }

    

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}

header("Location: entradas.php");
exit();
?>