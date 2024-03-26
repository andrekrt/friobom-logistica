<?php

session_start();
require("../conexao.php");

$idModudulo = 3;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $codRota = filter_input(INPUT_POST, 'codRota');
    $rota = filter_input(INPUT_POST, 'rota');
    $horaFechamento1 = filter_input(INPUT_POST, 'horaFechamento1');
    $horaFechamento2 = filter_input(INPUT_POST, 'horaFechamento2');
    $diaFechamento1 = filter_input(INPUT_POST, 'fechamento1');
    $diaFechamento2 = filter_input(INPUT_POST, 'fechamento2');
    $ceps = filter_input(INPUT_POST, 'ceps');
    $metaDias = str_replace(",",".", filter_input(INPUT_POST, 'metaDias'));

    $db->beginTransaction();

    try{
        $consulta = $db->prepare("SELECT * FROM rotas WHERE cod_rota = :codRota ");
        $consulta->bindValue(':codRota', $codRota);
        $consulta->execute();

        if($consulta->rowCount()>0){
            $_SESSION['msg'] = 'Essa Rota já está cadastrada!';
            $_SESSION['icon']='warning';
            header("Location: form-rota.php");
            exit(); 
        }

        $sql = $db->prepare("INSERT INTO rotas (cod_rota, nome_rota, fechamento1, fechamento2, hora_fechamento1, hora_fechamento2, ceps, meta_dias) VALUES (:codRota, :rota, :fechamento1, :fechamento2, :horaFechamento1, :horaFechamento2, :ceps, :metaDias) ");
        $sql->bindValue(':codRota', $codRota);
        $sql->bindValue(':rota', $rota);
        $sql->bindValue(':fechamento1', $diaFechamento1);
        $sql->bindValue(':fechamento2', $diaFechamento2);
        $sql->bindValue(':horaFechamento1', $horaFechamento1);
        $sql->bindValue(':horaFechamento2', $horaFechamento2);
        $sql->bindValue(':ceps', $ceps);
        $sql->bindValue(':metaDias', $metaDias);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Rota Cadastrada com Sucesso!';
        $_SESSION['icon']='success';
        

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Cadastrar Rota!';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}

header("Location: rotas.php");
exit();

?>