<?php

include_once "../conexao-oracle.php";

$carregamento = filter_input(INPUT_GET, 'carregamento');

if(!empty($carregamento)){
    $limite = 1;

    $sql = $dbora->prepare("SELECT * FROM FRIOBOM.pccarreg WHERE numcar = :nCarregamento ");
    $sql->bindParam(':nCarregamento', $carregamento);
    

    $dados = array();

    if($sql->execute()){
        $valores = $sql->fetch();
        $dados['motorista']=$valores[''];
    }
}

?>