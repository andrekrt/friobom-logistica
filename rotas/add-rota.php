<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] != 4 ){

    $codRota = filter_input(INPUT_POST, 'codRota');
    $rota = filter_input(INPUT_POST, 'rota');
    $horaFechamento1 = filter_input(INPUT_POST, 'horaFechamento1');
    $horaFechamento2 = filter_input(INPUT_POST, 'horaFechamento2');
    $diaFechamento1 = filter_input(INPUT_POST, 'fechamento1');
    $diaFechamento2 = filter_input(INPUT_POST, 'fechamento2');
    $ceps = filter_input(INPUT_POST, 'ceps');
    $metaDias = str_replace(",",".", filter_input(INPUT_POST, 'metaDias'));

    $consulta = $db->query("SELECT * FROM rotas WHERE cod_rota = $codRota ");

    if($consulta->rowCount()>0){
        echo "<script>alert('Essa Rota já está cadastrada!');</script>";
        echo "<script>window.location.href='form-rota.php'</script>";
    }else{
        $sql = $db->prepare("INSERT INTO rotas (cod_rota, nome_rota, fechamento1, fechamento2, hora_fechamento1, hora_fechamento2, ceps, meta_dias) VALUES (:codRota, :rota, :fechamento1, :fechamento2, :horaFechamento1, :horaFechamento2, :ceps, :metaDias) ");
        $sql->bindValue(':codRota', $codRota);
        $sql->bindValue(':rota', $rota);
        $sql->bindValue(':fechamento1', $diaFechamento1);
        $sql->bindValue(':fechamento2', $diaFechamento2);
        $sql->bindValue(':horaFechamento1', $horaFechamento1);
        $sql->bindValue(':horaFechamento2', $horaFechamento2);
        $sql->bindValue(':ceps', $ceps);
        $sql->bindValue(':metaDias', $metaDias);
        
        if($sql->execute()){

            echo "<script>alert('Rota Cadastrada!');</script>";
            echo "<script>window.location.href='rotas.php'</script>";

        }else{

           print_r($sql->errorInfo());

        }
    }

}else{
    echo "erro no cadastro contator o administrador!";
}

?>