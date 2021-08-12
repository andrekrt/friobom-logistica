<?php

use Mpdf\Tag\S;

session_start();
require("../../conexao.php");

if((isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==10 || $_SESSION['tipoUsuario'] == 99)){

    $idUsuario = $_SESSION['idUsuario'];

    $dataAtual =filter_input(INPUT_POST, 'dataEntrega'); 
    $carga = filter_input(INPUT_POST, 'carga');
    $sequencia = filter_input(INPUT_POST, 'sequencia');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    $defeitoCarro = filter_input(INPUT_POST, 'veiculoDefeito');
    $qtdEntregas = filter_input(INPUT_POST, 'nEntregas');
    $qtdEntregue = filter_input(INPUT_POST, 'nEntregue');
    $qtdFalta = $qtdEntregas-$qtdEntregue;
    $horaSaida = filter_input(INPUT_POST, 'hrSaida');
    $horaChegada = filter_input(INPUT_POST, 'hrChegada');
    $totalHoras = strtotime($horaChegada)-strtotime($horaSaida);
    $horasRota = floor($totalHoras/60/60);
    $minutosRota = round(($totalHoras - ($horasRota * 60 * 60)) / 60);
    $horas = str_pad($horasRota,2, "0", STR_PAD_LEFT);
    $minutos = str_pad($minutosRota,2, "0", STR_PAD_LEFT);
    $tempoRota = $horas.":".$minutos;
    $kmSaida = filter_input(INPUT_POST, 'kmSaida');
    $kmChegada = filter_input(INPUT_POST, 'kmChegada');
    $kmRodado = $kmChegada-$kmSaida;
    $vlAbast = str_replace(",",".",filter_input(INPUT_POST, 'vlAbastec')) ;
    $ltAbast = str_replace(",",".",filter_input(INPUT_POST, 'ltAbastec')) ;
    $mediaConsumo = $kmRodado/$ltAbast;
    $diariaMot = str_replace(",",".", filter_input(INPUT_POST, 'vlDiariaMot')) ;
    $diariaAux = str_replace(",",".", filter_input(INPUT_POST, 'vlDiariaAux')) ;
    $outrosGastos = str_replace(",",".",filter_input(INPUT_POST, 'vlOutrosGastos')) ;

    //echo "$dataAtual<br>$carga<br>$sequencia<br>$motorista<br>$veiculo<br>$defeitoCarro<br>$qtdEntregas<br>$qtdEntregue<br>$qtdFalta<br>$horaSaida<br>$horaChegada<br>$tempoRota<br>$kmSaida<br>$kmChegada<br>$kmRodado<br>$vlAbast<br>$ltAbast<br>$consumo<br>$mediaConsumo<br>$diariaMot<br>$diariaAux<br>$outrosGastos";

    $inserir = $db->prepare("INSERT INTO entregas_capital (data_atual, carga, sequencia, motorista, veiculo, defeito_carro, qtd_total, qtd_entregue, qtd_falta, hr_saida, hr_chegada, hr_rota, km_saida, km_chegada, km_rodado, lt_abastec, vl_abastec, media_consumo, diaria_motorista, diaria_auxiliar, outros_gastos, usuario) VALUES (:dataAtual, :carga, :sequencia, :motorista, :veiculo, :defeito, :qtdTotal, :qtdEntregue, :qtdFalta, :hrSaida, :hrChegada, :hrRota, :kmSaida, :kmChegada, :kmRodado, :ltAbast, :vlAbast, :mediaConsumo, :diariaMot, :diariaAux, :outrosGastos, :usuario)");
    $inserir->bindValue(':dataAtual', $dataAtual);
    $inserir->bindValue(':carga', $carga);
    $inserir->bindValue(':sequencia', $sequencia);
    $inserir->bindValue(':motorista', $motorista);
    $inserir->bindValue(':veiculo', $veiculo);
    $inserir->bindValue(':defeito', $defeitoCarro);
    $inserir->bindValue(':qtdTotal', $qtdEntregas);
    $inserir->bindValue(':qtdEntregue', $qtdEntregue);
    $inserir->bindValue(':qtdFalta', $qtdFalta);
    $inserir->bindValue(':hrSaida', $horaSaida);
    $inserir->bindValue(':hrChegada', $horaChegada);
    $inserir->bindValue(':hrRota', $tempoRota);
    $inserir->bindValue(':kmSaida', $kmSaida);
    $inserir->bindValue(':kmChegada', $kmChegada);
    $inserir->bindValue(':kmRodado', $kmRodado);
    $inserir->bindValue(':ltAbast', $ltAbast);
    $inserir->bindValue(':vlAbast', $vlAbast);
    $inserir->bindValue(':mediaConsumo', $mediaConsumo);
    $inserir->bindValue(':diariaMot', $diariaMot);
    $inserir->bindValue(':diariaAux', $diariaAux);
    $inserir->bindValue(':outrosGastos', $outrosGastos);
    $inserir->bindValue(':usuario', $idUsuario);
    
    if($inserir->execute()){

        echo "<script>alert('Entrega Lançada!');</script>";
        echo "<script>window.location.href='form-entregas.php'</script>";

    }else{
        print_r($inserir->errorInfo());
    }

}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../../index.php'</script>";
}

?>