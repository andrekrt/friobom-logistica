<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 5 || $_SESSION['tipoUsuario'] == 99 || $_SESSION['tipoUsuario'] == 6){

    $id = filter_input(INPUT_POST, 'id');
    $obsRota = filter_input(INPUT_POST, 'obsRota');
    $situacao = filter_input(INPUT_POST, 'situacao');

    $finaliza = $db->prepare("UPDATE carregamentos SET obs_rota = :obsRota, situacao = :situacao WHERE id = :id");
    $finaliza->bindValue(':id', $id);
    $finaliza->bindValue(':obsRota', $obsRota);
    $finaliza->bindValue(':situacao', $situacao);
    
    if($finaliza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='carregamentos.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{
    header("Location:carregamentos.php");
}

?>
