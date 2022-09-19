<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario']==99 ){

    $mesAno = filter_input(INPUT_POST, 'mesAno');
    $funcionarios = filter_input(INPUT_POST, 'funcionarios');
    $pagamento = str_replace(",",".",filter_input(INPUT_POST, 'pagamento'));
    $usuario = $_SESSION['idUsuario'];

    $consulta = $db->prepare("SELECT * FROM folha_pagamento WHERE mes_ano = :mesAno AND tipo_funcionarios = :funcionarios");
    $consulta->bindValue(':mesAno', $mesAno);
    $consulta->bindValue(':funcionarios', $funcionarios);
    $consulta->execute();

    // echo "$mesAno<br>$funcionarios<br>$pagamento<br>$usuario";

    if($consulta->rowCount()>0){
        echo "<script>alert('Esse mês já foi lançado!');</script>";
        echo "<script>window.location.href='pagamentos.php'</script>";
    }else{
        $sql = $db->prepare("INSERT INTO folha_pagamento (mes_ano, pagamento, tipo_funcionarios, usuario) VALUES (:mesAno, :pagamento, :funcionarios, :usuario) ");
        $sql->bindValue(':mesAno', $mesAno);
        $sql->bindValue(':pagamento', $pagamento);
        $sql->bindValue(':funcionarios', $funcionarios);
        $sql->bindValue(':usuario', $usuario);

        if($sql->execute()){
            echo "<script>alert('Pagamento Lançado!');</script>";
            echo "<script>window.location.href='pagamentos.php'</script>";
        }else{
            print_r($sql->errorInfo());
        }
    }

}else{
    echo "<script>alert('Acesso negado!');</script>";
        echo "<script>window.location.href='colaboradores.php'</script>";
}

?>