<?php
require("../../conexao.php");

function addExtrato($pneu, $operacao, $kmPneu, $veiculo, $kmVeiculo){
    global $db;
    $filial = $_SESSION['filial'];
    $data = date("Y-m-d");

    $sql = $db->prepare('INSERT INTO extrato_pneu (data_op, pneu, operacao, km_pneu, veiculo, km_veiculo, filila) VALUES(:data_op, :pneu, :operacao, :kmPneu, :veiculo, :kmVeiculo, :filial)');
    $sql->bindValue(':data_op', $data);
    $sql->bindValue(':pneu', $pneu);
    $sql->bindValue(':operacao', $operacao);
    $sql->bindValue(':kmPneu', $kmPneu);
    $sql->bindValue(':veiculo', $veiculo);
    $sql->bindValue(':kmVeiculo', $kmVeiculo);

    if($sql->execute()){
        return true;
    }else{
        print_r($sql->errorInfo());
    }
}


?>

