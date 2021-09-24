<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false){

    $dataCadastro = date("Y-m-d H:i:s");
    $nFogo = filter_input(INPUT_POST, 'nFogo');
    $medida = strtoupper(filter_input(INPUT_POST, 'medida'));
    $calibMax = filter_input(INPUT_POST, 'calibMax');
    $marca = strtoupper(filter_input(INPUT_POST, 'marca'));
    $modelo = strtoupper(filter_input(INPUT_POST, 'modelo'));
    $serie = strtoupper(filter_input(INPUT_POST, 'nSerie'));
    $vida = filter_input(INPUT_POST, 'vida');
    $posicao = strtoupper(filter_input(INPUT_POST, 'posicao'));
    $situacao = filter_input(INPUT_POST, 'situacao');
    $localizacao = filter_input(INPUT_POST, 'localizacao');
    $veiculo = strtoupper(filter_input(INPUT_POST, 'veiculo'));
    $kmVeiculo = filter_input(INPUT_POST, 'kmVeiculo');
    $suco01 = filter_input(INPUT_POST, 'suco01');
    $suco02 = filter_input(INPUT_POST, 'suco02');
    $suco03 = filter_input(INPUT_POST, 'suco03');
    $suco04 = filter_input(INPUT_POST, 'suco04');
    $usuario = $_SESSION['idUsuario'];
    $ativoVeiculo = 1;
    $uso = 1;

    //echo "$dataCadastro<br>$nFogo<br>$medida<br>$calibMax<br>$marca<br>$modelo<br>$serie<br>$vida<br>$posicao<br>$situacao<br>$localizacao<br>$veiculo<br>$kmVeiculo<br>$suco01<br>$suco02<br>$suco03<br>$suco04<br>$ativoVeiculo<br>$uso";

    $consultaPneu = $db->prepare("SELECT * FROM pneus WHERE num_fogo = :nFogo");
    $consultaPneu->bindValue(':nFogo', $nFogo);
    $consultaPneu->execute();
    if($consultaPneu->rowCount()>0){
        echo "<script> alert('Esse pneu já está cadastrado')</script>";
        echo "<script> window.location.href='form-pneus.php' </script>";
    }else{

        $sql = $db->prepare("INSERT INTO pneus (data_cadastro, num_fogo, medida, calibragem_maxima, marca, modelo, num_serie, vida, posicao_inicio, situacao, localizacao, veiculo, km_inicial, suco01, suco02, suco03, suco04, usuario, uso) VALUES (:dataCadastro, :nFogo, :medida, :calibMax, :marca, :modelo, :nSerie, :vida, :posicao, :situacao, :localizacao, :veiculo, :kmVeiculo, :suco01, :suco02, :suco03, :suco04, :usuario, :uso)");
        $sql->bindValue(':dataCadastro', $dataCadastro);
        $sql->bindValue(':nFogo', $nFogo);
        $sql->bindValue(':medida', $medida);
        $sql->bindValue(':calibMax', $calibMax);
        $sql->bindValue(':marca', $marca);
        $sql->bindValue(':modelo', $modelo);
        $sql->bindValue(':nSerie', $serie);
        $sql->bindValue(':vida', $vida);
        $sql->bindValue(':posicao', $posicao);
        $sql->bindValue(':situacao', $situacao);
        $sql->bindValue(':localizacao', $localizacao);
        $sql->bindValue(':veiculo', $veiculo);
        $sql->bindValue(':kmVeiculo', $kmVeiculo);
        $sql->bindValue(':suco01', $suco01);
        $sql->bindValue(':suco02', $suco02);
        $sql->bindValue(':suco03', $suco03);
        $sql->bindValue(':suco04', $suco04);
        $sql->bindValue(':usuario', $usuario);
        $sql->bindValue(':uso', $uso);
        
        if($sql->execute()){
            echo "<script> alert('Pneu Cadastrado')</script>";
            echo "<script> window.location.href='pneus.php' </script>";
        }else{
            print_r($sql->errorInfo());
        }
    }

}else{

}

?>