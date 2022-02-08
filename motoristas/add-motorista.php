<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

    $codMotorista =filter_input(INPUT_POST, 'codMotorista');
    $nomeMotorista = filter_input(INPUT_POST, 'nomeMotorista');
    $salario = str_replace(",",".", filter_input(INPUT_POST, 'salario'));
    $cnh = filter_input(INPUT_POST, 'cnh')?filter_input(INPUT_POST, 'cnh'):null;
    $validadeCnh = filter_input(INPUT_POST, 'validadeCNH')?filter_input(INPUT_POST, 'validadeCNH'):null;
    $toxicoligco = filter_input(INPUT_POST,'situacaoToxicologico');
    $validadeToxicologico = filter_input(INPUT_POST, 'validadeToxicologico');

    $consulta = $db->query("SELECT * FROM motoristas WHERE cod_interno_motorista = '$codMotorista' ");
    if($consulta->rowCount()>0){
        echo "<script>alert('Esse Motorista já está cadastrado!');</script>";
        echo "<script>window.location.href='form-motorista.php'</script>";
    }else{
        $sql = $db->prepare("INSERT INTO motoristas (cod_interno_motorista, nome_motorista, salario, cnh, validade_cnh, toxicologico, validade_toxicologico, ativo) VALUES (:codMotorista, :nomeMotorista, :salario, :cnh, :validadeCnh, :toxicologico, :validadeToxicologico, :ativo) ");
        $sql->bindValue(':codMotorista', $codMotorista);
        $sql->bindValue(':nomeMotorista', $nomeMotorista);
        $sql->bindValue(':salario', $salario);
        $sql->bindValue(':cnh', $cnh);
        $sql->bindValue(':validadeCnh', $validadeCnh);
        $sql->bindValue(':toxicologico', $toxicoligco);
        $sql->bindValue(':validadeToxicologico', $validadeToxicologico);
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