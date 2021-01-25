<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario']==1 || $_SESSION['tipoUsuario'] ==99){
    $codVeiculo = filter_input(INPUT_GET, 'codVeiculo');

    $delete = $db->query("DELETE FROM veiculos WHERE cod_interno_veiculo = '$codVeiculo' ");

    if($delete){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='veiculos.php' </script>";
    }else{
        echo "Erro, contatar o adminstrador!";
    }

}else{
    echo "Erro, contatar o adminstrador!";
}

?>