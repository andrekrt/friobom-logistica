<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario']==1 || $_SESSION['tipoUsuario'] ==99){

    $codRota = filter_input(INPUT_POST, 'codRota');
    $rota = filter_input(INPUT_POST, 'rota');

    $consulta = $db->query("SELECT * FROM rotas WHERE cod_rota = $codRota ");

    if($consulta->rowCount()>0){
        echo "<script>alert('Essa Rota já está cadastrada!');</script>";
        echo "<script>window.location.href='form-rota.php'</script>";
    }else{
        $sql = $db->query("INSERT INTO rotas (cod_rota, nome_rota) VALUES ('$codRota', '$rota') ");
        if($sql){

            echo "<script>alert('Rota Cadastrada!');</script>";
            echo "<script>window.location.href='rotas.php'</script>";

        }else{

            echo "erro no cadastro contator o administrador!";

        }
    }

}else{
    echo "erro no cadastro contator o administrador!";
}

?>