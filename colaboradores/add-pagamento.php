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

    $mesAno = filter_input(INPUT_POST, 'mesAno');
    $funcionarios = filter_input(INPUT_POST, 'funcionarios');
    $pagamento = str_replace(",",".",filter_input(INPUT_POST, 'pagamento'));
    $usuario = $_SESSION['idUsuario'];

    $db->beginTransaction();

    try{
        $consulta = $db->prepare("SELECT * FROM folha_pagamento WHERE mes_ano = :mesAno AND tipo_funcionarios = :funcionarios");
        $consulta->bindValue(':mesAno', $mesAno);
        $consulta->bindValue(':funcionarios', $funcionarios);
        $consulta->execute();

        if($consulta->rowCount()>0){
            $_SESSION['msg'] = 'Esse mês já foi lançado!';
            $_SESSION['icon']='warning';
            header("Location: pagamentos.php");
            exit();
        }
        $sql = $db->prepare("INSERT INTO folha_pagamento (mes_ano, pagamento, tipo_funcionarios, usuario) VALUES (:mesAno, :pagamento, :funcionarios, :usuario) ");
        $sql->bindValue(':mesAno', $mesAno);
        $sql->bindValue(':pagamento', $pagamento);
        $sql->bindValue(':funcionarios', $funcionarios);
        $sql->bindValue(':usuario', $usuario);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Pagamento Lançado com Sucesso';
        $_SESSION['icon']='success';        

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Lançar Pagamento';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: pagamentos.php");
exit();
?>