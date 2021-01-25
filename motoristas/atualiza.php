<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario']==1 || $_SESSION['tipoUsuario'] == 99){

    $codMotorista = filter_input(INPUT_POST, 'codMotorista');
    $nomeMotorista = filter_input(INPUT_POST, 'nomeMotorista');
    $cnh = filter_input(INPUT_POST, 'cnh')?filter_input(INPUT_POST, 'cnh'):null;
    $validadeCnh = filter_input(INPUT_POST, 'vencimentoCnh')?filter_input(INPUT_POST, 'vencimentoCnh'):'0000/00/00';
    $ativo = filter_input(INPUT_POST, 'ativo');
    if($ativo=='on'){
        $ativo = 0;
    }else{
        $ativo = 1;
    }
    $atualiza = $db->query("UPDATE motoristas SET nome_motorista = '$nomeMotorista', cnh = '$cnh', validade_cnh = '$validadeCnh', ativo = '$ativo' WHERE cod_interno_motorista = '$codMotorista' ");

    if($atualiza){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='motoristas.php' </script>";
    }else{
        echo "Erro, contatar o administrador";
    }
}else{
    header("Location:motoristas.php");
}

?>