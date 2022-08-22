<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && ($_SESSION['tipoUsuario'] == 2 || $_SESSION['tipoUsuario']==99)){

    $idCheck = filter_input(INPUT_POST, 'id');
    $usuario = $_SESSION['idUsuario'];
    $qtdNf = filter_input(INPUT_POST, 'qtdNf');
    $vlCarga = str_replace(",",".",filter_input(INPUT_POST, 'vlCarga')); 
    $kmSaida = filter_input(INPUT_POST, 'kmSaida');
    $hrSaida = filter_input(INPUT_POST,'hrSaida');
    $prevChegada = filter_input(INPUT_POST, 'prevChegada');
    $horimetro = filter_input(INPUT_POST, 'horimetro');
    $rota = filter_input(INPUT_POST, 'rota');
    $peso = str_replace(",",".", filter_input(INPUT_POST, 'peso'));
    $carregamento = filter_input(INPUT_POST,'carregamento');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $triangulo = filter_input(INPUT_POST, 'triangulo');
    $ajudante = filter_input(INPUT_POST, 'ajudante');
    $dataChegada = filter_input(INPUT_POST, 'dataChegada');
    $kmRota = filter_input(INPUT_POST,'kmRota');
    $ltAbastecido = str_replace(",",".",filter_input(INPUT_POST, 'ltAbastecido'));
    $vlAbastecido = str_replace(",",".", filter_input(INPUT_POST, 'vlAbastecido')) ;
    $obs = filter_input(INPUT_POST, 'obs');
    $media = ($kmRota-$kmSaida)/$ltAbastecido;
    $media = number_format($media,2,".",",");
    $imagem = $_FILES['fotos'];

    // echo "$qtdNf<br>$vlCarga<br>$kmSaida<br>$hrSaida<br>$prevChegada<br>$horimetro<br>$rota<br>$peso<br>$carregamento<br>$motorista<br>$ajudante<br>$dataChegada<br>$kmRota<br>$ltAbastecido<br>$vlAbastecido<br>$obs<br>$media<br><br>";
    // print_r($imagem['name']);
    
    $sql = $db->prepare("UPDATE checklist SET qtdnf = :qtdnf, vl_carga=:vlCarga, hora_saida=:hrSaida, km_saida=:kmSaida, previsao_chegada=:previsaoChegada, horimetro=:horimetro, rota=:rota, peso_carga=:peso, carregamento=:carregamento, motorista=:motorista, ajudante=:ajudante, chegada=:chegada, km_rota=:kmRota, litros_abastecido=:ltAbastecido, valor_abastecido=:vlAbastecido, media_consumo=:media, obs=:obs, usuario=:usuario WHERE idchecklist= :id");
    $sql->bindValue(':id', $idCheck);
    $sql->bindValue(':qtdnf', $qtdNf);
    $sql->bindValue(':vlCarga', $vlCarga);
    $sql->bindValue(':hrSaida', $hrSaida);
    $sql->bindValue(':kmSaida', $kmSaida);
    $sql->bindValue(':previsaoChegada', $prevChegada);
    $sql->bindValue(':horimetro', $horimetro);
    $sql->bindValue(':rota', $rota);
    $sql->bindValue(':peso', $peso);
    $sql->bindValue(':carregamento', $carregamento);
    $sql->bindValue(':motorista', $motorista);
    $sql->bindValue(':ajudante', $ajudante);
    $sql->bindValue(':chegada', $dataChegada);
    $sql->bindValue(':kmRota', $kmRota);
    $sql->bindValue(':ltAbastecido', $ltAbastecido);
    $sql->bindValue(':vlAbastecido', $vlAbastecido);
    $sql->bindValue(':media', $media);
    $sql->bindValue(':obs', $obs);
    $sql->bindValue(':usuario', $usuario);
    
    if($sql->execute()){
        if(!empty($imagem['name'][0])){
            $pasta = "uploads/".$idCheck."/retorno";
            for($i=0;$i<count($imagem['name']);$i++){
                $mover = move_uploaded_file($imagem['tmp_name'][$i],$pasta."/".$imagem['name'][$i]);
            }
        }
        echo "<script> alert('Check-List Atualizado!!')</script>";
        echo "<script> window.location.href='checklists.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }

}else{

}

?>