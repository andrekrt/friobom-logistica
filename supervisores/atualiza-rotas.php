<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && ($_SESSION['tipoUsuario']==2 || $_SESSION['tipoUsuario']==99)){

    $dataSaida = filter_input(INPUT_POST, 'dataSaida');
    $dataChegada = filter_input(INPUT_POST, 'dataChegada');
    $supervisor = filter_input(INPUT_POST, 'supervisor', FILTER_VALIDATE_INT);
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    $velMax = filter_input(INPUT_POST, 'velMax');
    $cidadeSaida = filter_input(INPUT_POST, 'cidadeSaida', FILTER_VALIDATE_INT);
    $cidadeChegada = filter_input(INPUT_POST, 'cidadeChegada', FILTER_VALIDATE_INT);
    $rca1 = filter_input(INPUT_POST, 'rca1');
    $rca2 = filter_input(INPUT_POST, 'rca2');
    $obs = filter_input(INPUT_POST, 'obs');
    $diarias = str_replace(",",".", filter_input(INPUT_POST, 'diarias')) ;
    $usuario = $_SESSION['idUsuario'];
    $idRota = filter_input(INPUT_POST, 'id');

    //qtd de visitas realizadas no dia
    $dataFormatada = date("Y-m-d", strtotime($dataSaida));
    $sqlCont = $db->prepare("SELECT codigo_sup, data_hora FROM localizacao WHERE codigo_sup LIKE :supervisor AND DATE(data_hora) = :dataSaida");
    $sqlCont->bindValue(':supervisor', $supervisor."%");
    $sqlCont->bindValue(':dataSaida', $dataFormatada);
    $sqlCont->execute();  
    $qtdVisitas = $sqlCont->rowCount();

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
    
    $sql = $db->prepare("UPDATE rotas_supervisores SET saida=:saida, supervisor=:supervisor, veiculo=:veiculo, chegada=:chegada, velocidade_max=:velocidade, cidade_inicio=:cidadeInicio, rca01=:rca01, rca02=:rca02, obs=:obs, cidade_final=:cidadeFinal, diarias=:diarias, qtd_visitas=:visitas, usuario=:usuario WHERE idrotas=:idrotas ");
    $sql->bindValue(':saida', $dataSaida);
    $sql->bindValue(':supervisor', $supervisor);
    $sql->bindValue(':veiculo', $veiculo);
    $sql->bindValue(':chegada', $dataChegada);
    $sql->bindValue(':velocidade', $velMax);
    $sql->bindValue(':cidadeInicio', $cidadeSaida);
    $sql->bindValue(':rca01', $rca1);
    $sql->bindValue(':rca02', $rca2);
    $sql->bindValue(':obs', $obs);
    $sql->bindValue(':cidadeFinal', $cidadeChegada);
    $sql->bindValue(':diarias', $diarias);
    $sql->bindValue(':visitas', $qtdVisitas);
    $sql->bindValue(':usuario', $usuario);
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