<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 4 ){
    $codVeiculo = filter_input(INPUT_GET, 'codVeiculo');

    $delete = $db->prepare("DELETE FROM veiculos WHERE cod_interno_veiculo = :codVeiculo ");
    $delete->bindValue(':codVeiculo', $codVeiculo);

    if($delete->execute()){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='veiculos.php' </script>";
    }else{
        print_r($delete->errorInfo());
    }

}else{
    echo "Erro, contatar o adminstrador!";
}

?>