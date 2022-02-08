<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 4 ){

    $codVeiculo = filter_input(INPUT_POST, 'codVeiculo');
    $tipoVeiculo = filter_input(INPUT_POST, 'tipoVeiculo');
    $placa = filter_input(INPUT_POST, 'placa');
    $categoria = filter_input(INPUT_POST, 'categoria');
    $kmUltimaRevisao = filter_input(INPUT_POST, 'kmUltimaRevisao');
    $kmAtual = filter_input(INPUT_POST, 'kmAtual');
    $peso = filter_input(INPUT_POST, 'peso');
    $cubagem = str_replace(",", ".", filter_input(INPUT_POST, 'cubagem'));
    $dataRevisao = filter_input(INPUT_POST, 'dataRevisao');

    $atualiza = $db->prepare("UPDATE veiculos SET tipo_veiculo = :tipoVeiculo, placa_veiculo = :placa, km_ultima_revisao = :ultimaRevisao, km_atual=:kmAtual, categoria = :categoria, peso_maximo = :peso, cubagem = :cubagem, data_revisao = :dataRevisao WHERE cod_interno_veiculo = :codVeiculo ");
    $atualiza->bindValue(':tipoVeiculo', $tipoVeiculo);
    $atualiza->bindValue(':placa', $placa);
    $atualiza->bindValue(':ultimaRevisao', $kmUltimaRevisao);
    $atualiza->bindValue(':kmAtual', $kmAtual);
    $atualiza->bindValue(':categoria', $categoria);
    $atualiza->bindValue(':peso', $peso);
    $atualiza->bindValue(':cubagem', $cubagem);
    $atualiza->bindValue(':codVeiculo', $codVeiculo);
    $atualiza->bindValue(':dataRevisao', $dataRevisao);

    //echo "$codVeiculo <br>$tipoVeiculo<br>$placa";

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='veiculos.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{
    header("Location:veiculos.php");
}

?>