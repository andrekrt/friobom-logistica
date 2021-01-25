<?php

include_once '../conexao.php';

$codMotorista = filter_input(INPUT_GET, 'codMotorista', FILTER_SANITIZE_STRING);
if(!empty($codMotorista)){
    $limite =1;
    $resultado = "SELECT * FROM motoristas WHERE cod_interno_motorista  = :codMotorista LIMIT :limite";

    $resultado=$db->prepare($resultado);
    $resultado->bindParam(':codMotorista', $codMotorista, PDO::PARAM_STR);
    $resultado->bindParam(':limite', $limite, PDO::PARAM_INT);
    $resultado->execute();

    $valores=array();

    if($resultado->rowCount()!=0){
        $veiculo = $resultado->fetch(PDO::FETCH_ASSOC);
        $valores['motorista'] = $veiculo['nome_motorista'];
        
    }else{
        $valores['motorista']='Não Encontrado';
    }
    echo json_encode($valores);

}

?>