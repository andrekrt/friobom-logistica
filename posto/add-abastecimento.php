<?php

session_start();
require("../conexao.php");
include("funcao.php");

$idModudulo = 13;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];
    $usuario = $_SESSION['idUsuario'];
    $dataAbastecimento = date("Y-m-d");
    $litro = str_replace(",",".",filter_input(INPUT_POST, 'litro'));
    $placa =  filter_input(INPUT_POST, 'placa');
    $carregamento = filter_input(INPUT_POST, 'carregamento');
    $tipoAbastecimento = filter_input(INPUT_POST, 'tipo');
    $km = filter_input(INPUT_POST, 'km');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $rota = filter_input(INPUT_POST, 'rota');
    $estoqueAtual = contaEstoque();

    $db->beginTransaction();

    try{
        //verifica se já possui esse abastecimento
        $consultaSaidas = $db->prepare("SELECT * FROM combustivel_saida WHERE carregamento = :carregamento && tipo_abastecimento = :tipo");
        $consultaSaidas->bindValue(':carregamento', $carregamento);
        $consultaSaidas->bindValue(':tipo', $tipoAbastecimento);
        $consultaSaidas->execute();

        if($consultaSaidas->rowCount()>0){
            $_SESSION['msg'] = 'Esse abastecimento já está registrado!';
            $_SESSION['icon']='warning';
            header("Location: abastecimento.php");
            exit();
        }
        if($litro>=$estoqueAtual){
            $_SESSION['msg'] = 'Estoque Insuficiente!';
            $_SESSION['icon']='warning';
            header("Location: abastecimento.php");
            exit();
        }

        $total = $db->query("SELECT SUM(total_litros) AS  litros, SUM(valor_total) as valor FROM combustivel_entrada WHERE situacao ='Aprovado' AND filial = $filial ");
        $total = $total->fetch();
        $precoMedio = $total['valor']/$total['litros'];
        $valorTotal = $precoMedio*$litro;

        $inserir = $db->prepare("INSERT INTO combustivel_saida (data_abastecimento, litro_abastecimento, preco_medio, valor_total, carregamento, motorista, rota, km, placa_veiculo, tipo_abastecimento, usuario, filial) VALUES (:dataAbastecimento, :litros, :precoMedio, :valorTotal, :carregamento, :motorista, :rota, :km, :placa, :tipo, :usuario, :filial)");
        $inserir->bindValue(':dataAbastecimento', $dataAbastecimento);
        $inserir->bindValue(':litros', $litro);
        $inserir->bindValue(':precoMedio', $precoMedio);
        $inserir->bindValue(':valorTotal', $valorTotal);
        $inserir->bindValue(':carregamento', $carregamento);
        $inserir->bindValue(':motorista', $motorista);
        $inserir->bindValue(':rota', $rota);
        $inserir->bindValue(':km', $km);
        $inserir->bindValue(':placa', $placa);
        $inserir->bindValue(':tipo', $tipoAbastecimento);
        $inserir->bindValue(':usuario', $usuario);
        $inserir->bindValue(':filial', $filial);
        $inserir->execute();

        $sqlExtrato = $db->prepare("INSERT INTO combustivel_extrato (data_operacao, tipo_operacao, volume, carregamento, placa, usuario, filial) VALUES (:dataOp, :tipoOp, :volume,:carga, :placa, :usuario, :filial)");
        $sqlExtrato->bindValue(':dataOp', $dataAbastecimento);
        $sqlExtrato->bindValue(':tipoOp', "Abastecimento");
        $sqlExtrato->bindValue(':volume', $litro);
        $sqlExtrato->bindValue(':carga', $carregamento);
        $sqlExtrato->bindValue(':placa', $placa);
        $sqlExtrato->bindValue(':usuario', $usuario);
        $sqlExtrato->bindValue(':filial', $filial);
        $sqlExtrato->execute();

        $db->commit();

        $_SESSION['msg'] = 'Abastecimento Lançada com Sucesso!';
        $_SESSION['icon']='success';
        
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Lançar Abastecimento';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}
header("Location: abastecimento.php");
exit();
?>