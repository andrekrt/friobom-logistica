<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 99 ){
    
    $placa = filter_input(INPUT_POST,'placa');
    $tipoTk = filter_input(INPUT_POST, 'tipotk');
    $horimetro = filter_input(INPUT_POST, 'horaAtual');
    
    // echo "$placa<br>$tipoTk<br>$horimetro";

    $inserir = $db->prepare("INSERT INTO thermoking (veiculo, tipo_tk, hora_atual) VALUES (:veiculo, :tipoTk, :horimetro)");
    $inserir->bindValue(':veiculo', $placa);
    $inserir->bindValue(':tipoTk', $tipoTk);
    $inserir->bindValue(':horimetro', $horimetro);   

    if($inserir->execute()){
       echo "<script>alert('Thermoking Cadastrado!');</script>";
        echo "<script>window.location.href='thermoking.php'</script>";
        
    }else{
         print_r($inserir->errorInfo());
    }

}

?>