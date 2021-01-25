<?php

include_once '../conexao.php';

$codRota = filter_input(INPUT_GET, 'codRota', FILTER_SANITIZE_STRING);
if(!empty($codRota)){
    $limite =1;
    $resultado = "SELECT * FROM rotas WHERE cod_rota  = :codRota LIMIT :limite";

    $resultado=$db->prepare($resultado);
    $resultado->bindParam(':codRota', $codRota, PDO::PARAM_STR);
    $resultado->bindParam(':limite', $limite, PDO::PARAM_INT);
    $resultado->execute();

    $valores=array();

    if($resultado->rowCount()!=0){
        $rota = $resultado->fetch(PDO::FETCH_ASSOC);
        $valores['rota'] = $rota['nome_rota'];
        
    }else{
        $valores['rota']='Não Encontrado';
    }
    echo json_encode($valores);

}

?>