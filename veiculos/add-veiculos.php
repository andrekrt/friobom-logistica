<?php

session_start();
require("../conexao.php");

if( isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario']==1 || $_SESSION['tipoUsuario'] ==99){

    $idUsuario = $_SESSION['idUsuario'];

    $codVeiculo = filter_input(INPUT_POST, 'codVeiculo');
    $tipoVeiculo = filter_input(INPUT_POST, 'tipoVeiculo');
    $placaVeiculo = filter_input(INPUT_POST, 'placaVeiculo');
    $categoria = filter_input(INPUT_POST, 'categoria');

    $consulta = $db->query("SELECT * FROM veiculos WHERE cod_interno_veiculo = '$codVeiculo'");

    if($consulta->rowCount()>0){
        echo "<script>alert('Esse Veículo já está cadastrado!');</script>";
        echo "<script>window.location.href='form-veiculos.php'</script>";
    }else{
        $sql= $db->query("INSERT INTO veiculos (cod_interno_veiculo, tipo_veiculo, placa_veiculo, categoria) VALUES ('$codVeiculo','$tipoVeiculo', '$placaVeiculo', '$categoria') ");
        if($sql){

            echo "<script>alert('Veículo Cadastrado!');</script>";
            echo "<script>window.location.href='veiculos.php'</script>";

        }else{
            echo "erro no cadastro contator o administrador!";
        }
    }

}

?>