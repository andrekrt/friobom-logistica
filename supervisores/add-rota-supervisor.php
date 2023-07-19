<?php

session_start();
require("../conexao.php");

$idModudulo = 14;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $dataSaida = filter_input(INPUT_POST, 'dataSaida');
    $dataChegada = filter_input(INPUT_POST, 'dataChegada');
    $supervisor = filter_input(INPUT_POST, 'supervisor', FILTER_VALIDATE_INT);
    $velMax = filter_input(INPUT_POST, 'velMax');
    $cidadeSaida = filter_input(INPUT_POST, 'cidadeSaida', FILTER_VALIDATE_INT);
    $cidadeChegada = filter_input(INPUT_POST, 'cidadeChegada', FILTER_VALIDATE_INT);
    $rca1 = filter_input(INPUT_POST, 'rca1');
    $rca2 = filter_input(INPUT_POST, 'rca2');
    $obs = filter_input(INPUT_POST, 'obs');
    $diarias = str_replace(",",".", filter_input(INPUT_POST, 'diarias')) ;
    $usuario = $_SESSION['idUsuario'];

    //qtd de visitas realizadas no dia
    $dataFormatada = date("Y-m-d", strtotime($dataSaida));
    $sqlCont = $db->prepare("SELECT codigo_sup, data_hora FROM localizacao WHERE codigo_sup LIKE :supervisor AND DATE(data_hora) = :dataSaida");
    $sqlCont->bindValue(':supervisor', $supervisor."%");
    $sqlCont->bindValue(':dataSaida', $dataFormatada);
    $sqlCont->execute();  
    $qtdVisitas = $sqlCont->rowCount();
    
    $sql = $db->prepare("INSERT INTO rotas_supervisores (saida, supervisor, chegada, velocidade_max, cidade_inicio, rca01, rca02, obs, cidade_final, diarias, qtd_visitas, usuario) VALUES (:saida, :supervisor, :chegada, :velocidade_max, :cidade_inicio, :rca01, :rca02, :obs, :cidade_final, :diarias, :qtd_visitas, :usuario)");
    $sql->bindValue(':saida', $dataSaida);
    $sql->bindValue(':supervisor', $supervisor);
    $sql->bindValue(':chegada', $dataChegada);
    $sql->bindValue(':velocidade_max', $velMax);
    $sql->bindValue(':cidade_inicio', $cidadeSaida);
    $sql->bindValue(':rca01', $rca1);
    $sql->bindValue(':rca02', $rca2);
    $sql->bindValue(':obs', $obs);
    $sql->bindValue(':cidade_final', $cidadeChegada);
    $sql->bindValue(':diarias', $diarias);
    $sql->bindValue(':qtd_visitas', $qtdVisitas);
    $sql->bindValue(':usuario', $usuario);
    if($sql->execute()){
        echo "<script> alert('Rota Registrada!!')</script>";
        echo "<script> window.location.href='rotas-supervisores.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }    

}else{
    echo "<script> alert('Acesso não permitido!!!')</script>";
    echo "<script> window.location.href='../index.php' </script>";
}

?>