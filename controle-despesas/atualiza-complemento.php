<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] != 4){

    $idComplemento = filter_input(INPUT_POST, 'id');
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $kmSaida = filter_input(INPUT_POST, 'kmSaida');
    $kmChegada = filter_input(INPUT_POST, 'kmChegada');  
    $ltAbast = str_replace(",",".",filter_input(INPUT_POST, 'ltAbast')) ;
    $vlAbast = str_replace(",",".",filter_input(INPUT_POST, 'vlAbast')) ;

    //echo "$idComplemento<br>$veiculo<br>$motorista<br>$kmSaida<br>$kmChegada<br>$ltAbast<br>$vlAbast";

    $atualiza = $db->prepare(" UPDATE complementos_combustivel SET veiculo = :veiculo, motorista = :motorista, km_saida = :kmSaida, km_chegada = :kmChegada, litros_abast = :ltAbast, valor_abast = :vlAbast WHERE id_complemento = :idComplemento");
    $atualiza->bindValue(':veiculo', $veiculo);
    $atualiza->bindValue(':motorista', $motorista);    
    $atualiza->bindValue(':kmSaida', $kmSaida);
    $atualiza->bindValue(':kmChegada', $kmChegada);
    $atualiza->bindValue(':ltAbast', $ltAbast);
    $atualiza->bindValue(':vlAbast', $vlAbast);
    $atualiza->bindValue(':idComplemento', $idComplemento);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='complementos.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>