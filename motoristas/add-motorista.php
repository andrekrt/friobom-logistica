<?php

session_start();
require("../conexao.php");

$idModudulo = 4;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $codMotorista =filter_input(INPUT_POST, 'codMotorista');
    $nomeMotorista = filter_input(INPUT_POST, 'nomeMotorista');
    $salario = str_replace(",",".", filter_input(INPUT_POST, 'salario'));
    $cnh = filter_input(INPUT_POST, 'cnh')?filter_input(INPUT_POST, 'cnh'):null;
    $validadeCnh = filter_input(INPUT_POST, 'validadeCNH')?filter_input(INPUT_POST, 'validadeCNH'):null;
    $toxicoligco = filter_input(INPUT_POST,'situacaoToxicologico');
    $validadeToxicologico = filter_input(INPUT_POST, 'validadeToxicologico');
    $cidadeBase = filter_input(INPUT_POST, 'base');

    $consulta = $db->query("SELECT * FROM motoristas WHERE cod_interno_motorista = '$codMotorista' OR cnh = '$cnh' ");
    if($consulta->rowCount()>0){
        echo "<script>alert('Esse Motorista já está cadastrado!');</script>";
        echo "<script>window.location.href='form-motorista.php'</script>";
    }else{
        $sql = $db->prepare("INSERT INTO motoristas (cod_interno_motorista, nome_motorista, salario, cnh, validade_cnh, toxicologico, validade_toxicologico, cidade_base, ativo) VALUES (:codMotorista, :nomeMotorista, :salario, :cnh, :validadeCnh, :toxicologico, :validadeToxicologico, :base, :ativo) ");
        $sql->bindValue(':codMotorista', $codMotorista);
        $sql->bindValue(':nomeMotorista', $nomeMotorista);
        $sql->bindValue(':salario', $salario);
        $sql->bindValue(':cnh', $cnh);
        $sql->bindValue(':validadeCnh', $validadeCnh);
        $sql->bindValue(':toxicologico', $toxicoligco);
        $sql->bindValue(':validadeToxicologico', $validadeToxicologico);
        $sql->bindValue(':base', $cidadeBase);
        $sql->bindValue(':ativo', 1);

        if($sql->execute()){
            echo "<script>alert('Motorista Cadastrado!');</script>";
            echo "<script>window.location.href='motoristas.php'</script>";
        }else{
            print_r($sql->errorInfo());
        }
    }
    //echo "$codMotorista<br> $nomeMotorista<br> $cnh <br> $validadeCnh";

}else{
    echo "erro no cadastro contator o administrador!";
}

?>