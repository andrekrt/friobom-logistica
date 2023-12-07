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
    $visitas = filter_input(INPUT_POST, 'visitas', FILTER_VALIDATE_INT);
    $cidades = filter_input(INPUT_POST, 'cidades');
    $rca1 = filter_input(INPUT_POST, 'rca1');
    $rca2 = filter_input(INPUT_POST, 'rca2');
    $obs = filter_input(INPUT_POST, 'obs');
    $horaAlmoco = str_replace(",",".", filter_input(INPUT_POST, 'horaAlmoco')) ;
    $usuario = $_SESSION['idUsuario'];

    // echo "$dataSaida<br>$dataChegada<br>$supervisor<br>$velMax<br>$visitas<br>$cidades<br>$rca1<br>$rca2<br>$obs<br>$horaAlmoco<br> $usuario";
    
    $sql = $db->prepare("INSERT INTO rotas_supervisores (saida, supervisor, chegada, velocidade_max, rca01, rca02, obs, qtd_visitas, usuario, cidades, hora_almoco) VALUES (:saida, :supervisor, :chegada, :velocidade_max, :rca01, :rca02, :obs, :qtd_visitas, :usuario, :cidades, :horaAlmoco)");
    $sql->bindValue(':saida', $dataSaida);
    $sql->bindValue(':supervisor', $supervisor);
    $sql->bindValue(':chegada', $dataChegada);
    $sql->bindValue(':velocidade_max', $velMax);
    $sql->bindValue(':rca01', $rca1);
    $sql->bindValue(':rca02', $rca2);
    $sql->bindValue(':obs', $obs);
    $sql->bindValue(':qtd_visitas', $visitas);
    $sql->bindValue(':usuario', $usuario);
    $sql->bindValue(':cidades',$cidades);
    $sql->bindValue(':horaAlmoco', $horaAlmoco);
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