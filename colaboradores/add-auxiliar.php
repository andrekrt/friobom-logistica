<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario']==1 || $_SESSION['tipoUsuario'] ==99){

    $nome = filter_input(INPUT_POST, 'nome');
    $cpf = filter_input(INPUT_POST, 'cpf');
    $salario = str_replace(",",".",filter_input(INPUT_POST, 'salario'));
    $rota = filter_input(INPUT_POST, 'rota');

    $consulta = $db->prepare("SELECT * FROM auxiliares_rota WHERE cpf_auxiliar = :cpf ");
    $consulta->bindValue(':cpf', $cpf);
    $consulta->execute();

    if($consulta->rowCount()>0){
        echo "<script>alert('Esse Auxiliar já está cadastrado!');</script>";
        echo "<script>window.location.href='auxiliares.php'</script>";
    }else{
        $sql = $db->prepare("INSERT INTO auxiliares_rota (nome_auxiliar, cpf_auxiliar, salario_auxiliar, rota) VALUES (:nome, :cpf, :salario, :rota) ");
        $sql->bindValue(':nome', $nome);
        $sql->bindValue(':cpf', $cpf);
        $sql->bindValue(':salario', $salario);
        $sql->bindValue(':rota', $rota);

        if($sql->execute()){
            echo "<script>alert('Auxiliar Cadastrado!');</script>";
            echo "<script>window.location.href='auxiliares.php'</script>";
        }else{
            print_r($sql->errorInfo());
        }
    }
    //echo "$codMotorista<br> $nomeMotorista<br> $cnh <br> $validadeCnh";

}else{
    echo "<script>alert('Acesso negado!');</script>";
        echo "<script>window.location.href='colaboradores.php'</script>";
}

?>