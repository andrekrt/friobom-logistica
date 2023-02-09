<?php

// require("../conexao-on.php");

// $sqlSaidas = $db->query("SELECT * FROM combustivel_saida");
// $saidas = $sqlSaidas->fetchAll();

// foreach($saidas as $saida){
//     $sqlInsert = $db->prepare("INSERT INTO combustivel_extrato (data_operacao, tipo_operacao, volume, carregamento, placa, usuario) VALUES(:dataOp, :tipoOp, :volume, :carregamento, :placa, :usuario)");
//     $sqlInsert->bindValue(':dataOp', $saida['data_abastecimento']);
//     $sqlInsert->bindValue(':tipoOp', "Abastecimento");
//     $sqlInsert->bindValue(':volume', $saida['valor_total']);
//     $sqlInsert->bindValue(':carregamento', $saida['carregamento']);
//     $sqlInsert->bindValue(':placa', $saida['placa_veiculo']);
//     $sqlInsert->bindValue(':usuario', $saida['usuario']);
//     if($sqlInsert->execute()){
//         echo $saida['idcombustivel_saida'] . " - Certo<br>";
//     }else{
//         print_r($sqlInsert->errorInfo());
//     }
// }

// $sqlEntradas = $db->query("SELECT * FROM combustivel_entrada");
// $entradas = $sqlEntradas->fetchAll();

// foreach($entradas as $entrada){
//     $sqlInsert = $db->prepare("INSERT INTO combustivel_extrato (data_operacao, tipo_operacao, volume,  usuario) VALUES(:dataOp, :tipoOp, :volume, :usuario)");
//     $sqlInsert->bindValue(':dataOp', $entrada['data_entrada']);
//     $sqlInsert->bindValue(':tipoOp',"Entrada");
//     $sqlInsert->bindValue(':volume', $entrada['total_litros']);
//     $sqlInsert->bindValue(':usuario', $entrada['usuario']);
//     if($sqlInsert->execute()){
//         echo $entrada['idcombustivel_entrada'] . " - Certo<br>";
//     }else{
//         print_r($sqlInsert->errorInfo());
//     }
// }
?>