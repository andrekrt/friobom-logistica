<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario']==1 || $_SESSION['tipoUsuario'] == 99){

    $codMotorista = filter_input(INPUT_POST, 'codMotorista');
    $nomeMotorista = filter_input(INPUT_POST, 'nomeMotorista');
    $cnh = filter_input(INPUT_POST, 'cnh')?filter_input(INPUT_POST, 'cnh'):null;
    $validadeCnh = filter_input(INPUT_POST, 'vencimentoCnh')?filter_input(INPUT_POST, 'vencimentoCnh'):'0000/00/00';
    $toxicologico = filter_input(INPUT_POST, 'toxicologico');
    $validadeToxicologico = filter_input(INPUT_POST, 'vencimentoToxicologico');
    $ativo = filter_input(INPUT_POST, 'ativo');
    if($ativo=='on'){
        $ativo = 0;
    }else{
        $ativo = 1;
    }
    $atualiza = $db->prepare("UPDATE motoristas SET nome_motorista = :nomeMotorista, cnh = :cnh, validade_cnh = :validadeCnh, toxicologico = :toxicologico, validade_toxicologico = :validadeToxicologico, ativo = :ativo WHERE cod_interno_motorista = :codMotorista ");
    $atualiza->bindValue(':nomeMotorista', $nomeMotorista);
    $atualiza->bindValue(':cnh', $cnh);
    $atualiza->bindValue(':validadeCnh', $validadeCnh);
    $atualiza->bindValue(':toxicologico', $toxicologico);
    $atualiza->bindValue(':validadeToxicologico', $validadeToxicologico);
    $atualiza->bindValue(':ativo', $ativo);
    $atualiza->bindValue(':codMotorista', $codMotorista);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='motoristas.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }
}else{
    header("Location:motoristas.php");
}

?>