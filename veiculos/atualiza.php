<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 4 ){

    $codVeiculo = filter_input(INPUT_POST, 'codVeiculo');
    $tipoVeiculo = filter_input(INPUT_POST, 'tipoVeiculo');
    $placa = filter_input(INPUT_POST, 'placa');
    $categoria = filter_input(INPUT_POST, 'categoria');
    $peso = filter_input(INPUT_POST, 'peso');
    $cubagem = str_replace(",", ".", filter_input(INPUT_POST, 'cubagem'));
    $metaCombustivel = str_replace(",", ".",filter_input(INPUT_POST, 'metaCombustivel') ) ;

    $atualiza = $db->prepare("UPDATE veiculos SET tipo_veiculo = :tipoVeiculo, placa_veiculo = :placa, categoria = :categoria, peso_maximo = :peso, cubagem = :cubagem, meta_combustivel = :metaCombustivel WHERE cod_interno_veiculo = :codVeiculo ");
    $atualiza->bindValue(':tipoVeiculo', $tipoVeiculo);
    $atualiza->bindValue(':placa', $placa);
    $atualiza->bindValue(':categoria', $categoria);
    $atualiza->bindValue(':peso', $peso);
    $atualiza->bindValue(':cubagem', $cubagem);
    $atualiza->bindValue(':codVeiculo', $codVeiculo);
    $atualiza->bindValue(':metaCombustivel', $metaCombustivel);

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