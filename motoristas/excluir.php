<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario']==1 || $_SESSION['tipoUsuario'] ==99){

    $codMotorista = filter_input(INPUT_GET, 'codMotorista');
    $delete = $db->query("DELETE FROM motoristas WHERE cod_interno_motorista = '$codMotorista' ");

    if($delete){
        echo "<script> alert('Excluído com Sucesso!')</script>";
        echo "<script> window.location.href='motoristas.php' </script>";
    }else{
        echo "Erro, contatar o adminstrador!";
    }

}else{
    echo "Erro, contatar o adminstrador!";
}

?>