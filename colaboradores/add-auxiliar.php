<?php

session_start();
require("../conexao.php");

$idModudulo = 5;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

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