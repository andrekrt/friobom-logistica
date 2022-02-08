<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] != 4){

    $idUsuario = $_SESSION['idUsuario'];
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    $motorista = filter_input(INPUT_POST,'motorista');
    $kmSaida = filter_input(INPUT_POST,'kmSaida');
    $kmChegada = filter_input(INPUT_POST, 'kmChegada');
    $ltAbast = str_replace(",",".",filter_input(INPUT_POST, 'ltAbast')) ;
    $vlAbast = str_replace(",",".", filter_input(INPUT_POST, 'vlAbast')) ;

    //echo "$veiculo<br>$motorista<br>$kmSaida<br>$kmChegada<br>$ltAbast<br>$vlAbast";

    $inserir = $db->prepare("INSERT INTO complementos_combustivel (veiculo, motorista, km_saida, km_chegada, litros_abast, valor_abast, id_usuario) VALUES (:veiculo, :motorista, :kmSaida, :kmChegada, :ltAbast, :vlAbast, :idUsuario)");
    $inserir->bindValue(':veiculo', $veiculo);
    $inserir->bindValue(':motorista', $motorista);
    $inserir->bindValue(':kmSaida', $kmSaida);
    $inserir->bindValue(':kmChegada', $kmChegada);
    $inserir->bindValue(':ltAbast', $ltAbast);
    $inserir->bindValue(':vlAbast', $vlAbast);
    $inserir->bindValue(':idUsuario', $idUsuario);

    if($inserir->execute()){
        echo "<script> alert('Complemento Lançado!')</script>";
        echo "<script> window.location.href='complementos.php' </script>";
    }else{
        print_r($inserir->errorInfo());
    }
    
}else{
    header("Location: complementos.php");
}

?>