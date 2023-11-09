<?php
require("../../conexao.php");

function addExtrato($pneu, $operacao, $kmPneu, $veiculo, $kmVeiculo){
    global $db;

    $data = date("Y-m-d");

    $sql = $db->prepare('INSERT INTO extrato_pneu (data_op, pneu, operacao, km_pneu, veiculo, km_veiculo) VALUES(:data_op, :pneu, :operacao, :kmPneu, :veiculo, :kmVeiculo)');
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

