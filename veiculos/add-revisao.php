<?php

session_start();
require("../conexao.php");
date_default_timezone_set('America/Sao_Paulo');

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99){
    
    $placa = filter_input(INPUT_POST,'placa');
    $kmRevisao = filter_input(INPUT_POST, 'kmRevisao');
    $dataRevisao = filter_input(INPUT_POST, 'dataRevisao');
    
    //echo "$placa<br>$kmRevisao<br>$dataRevisao";

   $inserir = $db->prepare("INSERT INTO revisao_veiculos (placa_veiculo, km_revisao, data_revisao) VALUES (:placa, :kmRevisao, :dataRevisao)");
    $inserir->bindValue(':placa', $placa);
    $inserir->bindValue(':kmRevisao', $kmRevisao);
    $inserir->bindValue(':dataRevisao', $dataRevisao);

    if($inserir->execute()){
        echo "<script>alert('Revisão Lançada!');</script>";
        echo "<script>window.location.href='revisao.php'</script>";
        
    }else{
        print_r($inserir->errorInfo());
    }

}

?>