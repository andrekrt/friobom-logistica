<?php

include_once '../conexao.php';

$codVeiculo = filter_input(INPUT_GET, 'codVeiculo', FILTER_SANITIZE_STRING);
if(!empty($codVeiculo)){
    $limite =1;
    $resultado = "SELECT * FROM veiculos WHERE cod_interno_veiculo = :codVeiculo LIMIT :limite";

    $resultado=$db->prepare($resultado);
    $resultado->bindParam(':codVeiculo', $codVeiculo, PDO::PARAM_STR);
    $resultado->bindParam(':limite', $limite, PDO::PARAM_INT);
    $resultado->execute();

    $valores=array();

    if($resultado->rowCount()!=0){
        $veiculo = $resultado->fetch(PDO::FETCH_ASSOC);
        $valores['tipoVeiculo'] = $veiculo['tipo_veiculo'];
        $valores['placaVeiculo'] = $veiculo['placa_veiculo'];
    }else{
        $valores['tipoVeiculo']='Não Encontrado';
    }
    echo json_encode($valores);

}

?>