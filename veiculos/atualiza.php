<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario']==1 || $_SESSION['tipoUsuario'] ==99){

    $codVeiculo = filter_input(INPUT_POST, 'codVeiculo');
    $tipoVeiculo = filter_input(INPUT_POST, 'tipoVeiculo');
    $placa = filter_input(INPUT_POST, 'placa');
    $categoria = filter_input(INPUT_POST, 'categoria');
    $kmUltimaRevisao = filter_input(INPUT_POST, 'kmUltimaRevisao');
    $kmAtual = filter_input(INPUT_POST, 'kmAtual');

    $atualiza = $db->query("UPDATE veiculos SET tipo_veiculo = '$tipoVeiculo', placa_veiculo = '$placa', km_ultima_revisao = '$kmUltimaRevisao', km_atual='$kmAtual', categoria = '$categoria' WHERE cod_interno_veiculo = '$codVeiculo' ");

    //echo "$codVeiculo <br>$tipoVeiculo<br>$placa";

    if($atualiza){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='veiculos.php' </script>";
    }else{
        echo "Erro, contatar o administrador";
    }

}else{
    header("Location:veiculos.php");
}

?>