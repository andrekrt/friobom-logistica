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
    $filial = $_SESSION['filial'];
    $codMotorista =filter_input(INPUT_POST, 'codMotorista');
    $nomeMotorista = filter_input(INPUT_POST, 'nomeMotorista');
    $salario = str_replace(",",".", filter_input(INPUT_POST, 'salario'));
    $cnh = filter_input(INPUT_POST, 'cnh')?filter_input(INPUT_POST, 'cnh'):null;
    $validadeCnh = filter_input(INPUT_POST, 'validadeCNH')?filter_input(INPUT_POST, 'validadeCNH'):null;
    $toxicoligco = filter_input(INPUT_POST,'situacaoToxicologico');
    $validadeToxicologico = filter_input(INPUT_POST, 'validadeToxicologico');
    $cidadeBase = filter_input(INPUT_POST, 'base');

    $db->beginTransaction();

    try{
        $consulta = $db->prepare("SELECT * FROM motoristas WHERE cod_interno_motorista = :codMot OR cnh = :cnh ");
        $consulta->bindValue(':codMot', $codMotorista);
        $consulta->bindValue(':cnh', $cnh);
        $consulta->execute();       

        if($consulta->rowCount()>0){
            
            $_SESSION['msg'] = 'Esse Motorista já está cadastrado!';
            $_SESSION['icon']='warning';
            header("Location: form-motorista.php");
            exit();
        }

        $sql = $db->prepare("INSERT INTO motoristas (cod_interno_motorista, nome_motorista, salario, cnh, validade_cnh, toxicologico, validade_toxicologico, cidade_base, ativo, filial) VALUES (:codMotorista, :nomeMotorista, :salario, :cnh, :validadeCnh, :toxicologico, :validadeToxicologico, :base, :ativo, :filial) ");
        $sql->bindValue(':codMotorista', $codMotorista);
        $sql->bindValue(':nomeMotorista', $nomeMotorista);
        $sql->bindValue(':salario', $salario);
        $sql->bindValue(':cnh', $cnh);
        $sql->bindValue(':validadeCnh', $validadeCnh);
        $sql->bindValue(':toxicologico', $toxicoligco);
        $sql->bindValue(':validadeToxicologico', $validadeToxicologico);
        $sql->bindValue(':base', $cidadeBase);
        $sql->bindValue(':ativo', 1);
        $sql->bindValue(':filial', $filial);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Motorista Cadastrada com Sucesso';
        $_SESSION['icon']='success';
        
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Cadastrar Motorista';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}

header("Location: motoristas.php");
exit();

?>