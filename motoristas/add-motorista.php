<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario']==1 || $_SESSION['tipoUsuario'] ==99){

    $codMotorista =filter_input(INPUT_POST, 'codMotorista');
    $nomeMotorista = filter_input(INPUT_POST, 'nomeMotorista');
    $cnh = filter_input(INPUT_POST, 'cnh')?filter_input(INPUT_POST, 'cnh'):null;
    $validadeCnh = filter_input(INPUT_POST, 'validadeCNH')?filter_input(INPUT_POST, 'validadeCNH'):null;

    $consulta = $db->query("SELECT * FROM motoristas WHERE cod_interno_motorista = '$codMotorista' ");
    if($consulta->rowCount()>0){
        echo "<script>alert('Esse Motorista já está cadastrado!');</script>";
        echo "<script>window.location.href='form-motorista.php'</script>";
    }else{
        $sql = $db->query("INSERT INTO motoristas (cod_interno_motorista, nome_motorista, cnh, validade_cnh, ativo) VALUES ('$codMotorista', '$nomeMotorista', '$cnh', '$validadeCnh', 1) ");

        if($sql){
            echo "<script>alert('Motorista Cadastrado!');</script>";
            echo "<script>window.location.href='motoristas.php'</script>";
        }else{
            echo "erro no cadastro contator o administrador!";
        }
    }
    //echo "$codMotorista<br> $nomeMotorista<br> $cnh <br> $validadeCnh";

}else{
    echo "erro no cadastro contator o administrador!";
}

?>