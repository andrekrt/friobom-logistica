<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 4 ){
    $codVeiculo = filter_input(INPUT_GET, 'codVeiculo');
    $ativo = 0;

    $atualiza = $db->prepare("UPDATE veiculos SET ativo = :ativo WHERE cod_interno_veiculo = :codigo");
    $atualiza->bindValue(':codigo', $codVeiculo);
    $atualiza->bindValue(':ativo', $ativo);
    $atualiza->execute();

    if($atualiza->execute()){
        echo "<script> alert('Desativado com Sucesso!')</script>";
        echo "<script> window.location.href='veiculos.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{
    echo "Erro, contatar o adminstrador!";
}

?>