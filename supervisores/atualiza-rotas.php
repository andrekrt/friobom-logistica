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
    $idRota = filter_input(INPUT_POST, 'id');
    $kmRodado = filter_input(INPUT_POST, 'kmRodado');
    $usuario = $_SESSION['idUsuario'];

    // echo "Data de Saída: $dataSaida<br>" ;
    // echo "Data de Saída: $dataChegada<br>" ;
    // echo "Supervisor: $supervisor<br>" ;
    // echo "Veiculo: $veiculo<br>" ;
    // echo "Velocidade Máxima: $velMax<br>" ;
    // echo "Cidade Saída: $cidadeSaida<br>" ;
    // echo "Cidade chegada: $cidadeChegada<br>" ;
    // echo "RCA1: $rca1<br>" ;
    // echo "RCA2: $rca2<br>" ;
    // echo "OBS: $obs<br>" ;
    // echo "Residencia: $residencia<br>" ;
    // echo "Diarias: $diarias<br>" ;
    // echo "Usuário: $usuario<br>" ;
    // echo "ID: $idRota<br>" ;
    
    $sql = $db->prepare("UPDATE rotas_supervisores SET saida=:saida, supervisor=:supervisor, chegada=:chegada, velocidade_max=:velocidade, rca01=:rca01, rca02=:rca02, obs=:obs, cidades=:cidades, qtd_visitas=:visitas, hora_almoco=:horaAlmoco, km_rodado=:km, usuario=:usuario WHERE idrotas=:idrotas ");
    $sql->bindValue(':saida', $dataSaida);
    $sql->bindValue(':supervisor', $supervisor);
    $sql->bindValue(':chegada', $dataChegada);
    $sql->bindValue(':velocidade', $velMax);
    $sql->bindValue(':rca01', $rca1);
    $sql->bindValue(':rca02', $rca2);
    $sql->bindValue(':obs', $obs);
    $sql->bindValue(':cidades', $cidades);
    $sql->bindValue(':visitas', $visitas);
    $sql->bindValue(':horaAlmoco', $horaAlmoco);
    $sql->bindValue(':usuario', $usuario);
    $sql->bindValue(':km',$kmRodado);
    $sql->bindValue(':idrotas', $idRota);
    if($sql->execute()){
        echo "<script> alert('Rota Atualizada!!')</script>";
        echo "<script> window.location.href='rotas-supervisores.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }    

}else{
    echo "<script> alert('Acesso não permitido!!!')</script>";
    echo "<script> window.location.href='../index.php' </script>";
}

?>