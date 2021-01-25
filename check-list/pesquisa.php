<?php

include_once '../conexao.php';

$placaVeiculo = filter_input(INPUT_GET, 'placaVeiculo', FILTER_SANITIZE_STRING);
if(!empty($placaVeiculo)){
    $limite =1;
    $resultado = "SELECT * FROM veiculos WHERE placa_veiculo = :placaVeiculo LIMIT :limite";

    $resultado=$db->prepare($resultado);
    $resultado->bindParam(':placaVeiculo', $placaVeiculo, PDO::PARAM_STR);
    $resultado->bindParam(':limite', $limite, PDO::PARAM_INT);
    $resultado->execute();

    $valores=array();

    if($resultado->rowCount()!=0){
        $veiculo = $resultado->fetch(PDO::FETCH_ASSOC);
        $valores['tipoVeiculo'] = $veiculo['tipo_veiculo'];
    }else{
        $valores['tipoVeiculo']='Não Encontrado';
    }
    echo json_encode($valores);

}

?>