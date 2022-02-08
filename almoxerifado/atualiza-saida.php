<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false&& $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $idSaida = filter_input(INPUT_POST, 'id');
    $dataSaida = filter_input(INPUT_POST, 'data');
    $qtd = str_replace(",", ".", filter_input(INPUT_POST, 'qtd')) ;
    $solicitante = filter_input(INPUT_POST, 'solicitante');
    $peca = filter_input(INPUT_POST, 'peca');
    $placa = filter_input(INPUT_POST, 'placa');
    $obs = filter_input(INPUT_POST, 'obs');  

    //echo "$idSaida<br>$dataSaida<br>$qtd<br>$solicitante<br>$peca<br>$placa<br>$obs";

    $atualiza = $db->prepare("UPDATE saida_estoque SET data_saida = :dataSaida, qtd = :qtd, peca_idpeca = :peca, solicitante = :solicitante, placa = :placa, obs = :obs WHERE idsaida_estoque =:idSaida");
    $atualiza->bindValue(':dataSaida', $dataSaida);
    $atualiza->bindValue(':qtd', $qtd);
    $atualiza->bindValue(':peca', $peca);
    $atualiza->bindValue(':solicitante', $solicitante);
    $atualiza->bindValue(':placa', $placa);
    $atualiza->bindValue(':obs', $obs);
    $atualiza->bindValue(':idSaida', $idSaida);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='saidas.php' </script>";
    }else{
        print_r($db->errorInfo());
    }

}else{
    echo "Erro";
}

?>