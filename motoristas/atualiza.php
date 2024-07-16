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

    $codMotorista = filter_input(INPUT_POST, 'codMotorista');
    $nomeMotorista = filter_input(INPUT_POST, 'nomeMotorista');
    $salario = str_replace(",",".", filter_input(INPUT_POST, 'salario'));
    $cnh = filter_input(INPUT_POST, 'cnh')?filter_input(INPUT_POST, 'cnh'):null;
    $validadeCnh = filter_input(INPUT_POST, 'validadeCnh')?filter_input(INPUT_POST, 'validadeCnh'):null;
    $toxicologico = filter_input(INPUT_POST, 'toxicologico');
    $validadeToxicologico = filter_input(INPUT_POST, 'validadeToxicologico');
    $cidadeBase = filter_input(INPUT_POST, 'base');
    $ativo = filter_input(INPUT_POST, 'ativo');
    $fusion = filter_input(INPUT_POST, 'fusion');
    if($ativo=='on'){
        $ativo = 0;
    }else{
        $ativo = 1;
    }

    $db->beginTransaction();

    try{
        $atualiza = $db->prepare("UPDATE motoristas SET nome_motorista = :nomeMotorista, cnh = :cnh, validade_cnh = :validadeCnh, toxicologico = :toxicologico, validade_toxicologico = :validadeToxicologico, ativo = :ativo, cidade_base=:base, salario = :salario, fusion=:fusion WHERE cod_interno_motorista = :codMotorista ");
        $atualiza->bindValue(':nomeMotorista', $nomeMotorista);
        $atualiza->bindValue('salario', $salario);
        $atualiza->bindValue(':cnh', $cnh);
        $atualiza->bindValue(':validadeCnh', $validadeCnh);
        $atualiza->bindValue(':toxicologico', $toxicologico);
        $atualiza->bindValue(':validadeToxicologico', $validadeToxicologico);
        $atualiza->bindValue(':ativo', $ativo);
        $atualiza->bindValue(':codMotorista', $codMotorista);
        $atualiza->bindValue(':base', $cidadeBase);
        $atualiza->bindValue(':fusion', $fusion);
        $atualiza->execute();

        $db->commit();

        $_SESSION['msg'] = 'Motorista Atualizado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();

        $_SESSION['msg'] = 'Erro ao Atualizar Motorista';
        $_SESSION['icon']='error';
    }

    
}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}

header("Location: motoristas.php");
exit();
?>