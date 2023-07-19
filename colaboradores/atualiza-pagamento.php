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
    $idPagamento = filter_input(INPUT_POST, 'id');


    // echo "$mesAno<br>$funcionarios<br>$pagamento<br>$usuario";

    $sql = $db->prepare("UPDATE folha_pagamento SET mes_ano=:mesAno, pagamento=:pagamento, tipo_funcionarios=:funcionarios, usuario=:usuario WHERE idpagamento = :id ");
    $sql->bindValue(':mesAno', $mesAno);
    $sql->bindValue(':pagamento', $pagamento);
    $sql->bindValue(':funcionarios', $funcionarios);
    $sql->bindValue(':usuario', $usuario);
    $sql->bindValue(':id', $idPagamento);

    if($sql->execute()){
        echo "<script>alert('Pagamento Atualizado!');</script>";
        echo "<script>window.location.href='pagamentos.php'</script>";
    }else{
        print_r($sql->errorInfo());
    }


}else{
    echo "<script>alert('Acesso negado!');</script>";
        echo "<script>window.location.href='colaboradores.php'</script>";
}

?>