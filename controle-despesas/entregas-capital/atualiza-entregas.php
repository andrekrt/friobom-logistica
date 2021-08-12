<?php

session_start();
require("../../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==10 || $_SESSION['tipoUsuario'] == 99){

    $idEntrega = filter_input(INPUT_POST, 'idEntrega');
    $dataAtual = filter_input(INPUT_POST, 'data');
    $carga = filter_input(INPUT_POST, 'carga');
    $sequencia = filter_input(INPUT_POST, 'sequencia');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    $carroDefeito = filter_input(INPUT_POST, 'defeito');
    $qtdEntregas = filter_input(INPUT_POST, 'nEntregas');
    $qtdEntregue = filter_input(INPUT_POST, 'nEntregue');
    $qtdRestante = $qtdEntregas-$qtdEntregue;
    $horaSaida = filter_input(INPUT_POST, 'hrSaida');
    $horaChegada = filter_input(INPUT_POST, 'hrChegada');
    //calculo de horas em rota
    $totalHoras = strtotime($horaChegada)-strtotime($horaSaida);
    $horasRota = floor($totalHoras/60/60);
    $minutosRota = round(($totalHoras - ($horasRota * 60 * 60)) / 60);
    $horas = str_pad($horasRota,2, "0", STR_PAD_LEFT);
    $minutos = str_pad($minutosRota,2, "0", STR_PAD_LEFT);
    $tempoRota = $horas.":".$minutos;

    $kmSaida = filter_input(INPUT_POST, 'kmSaida');
    $kmChegada = filter_input(INPUT_POST, 'kmChegada');
    $kmRodado = $kmChegada-$kmSaida;
    $vlAbast = str_replace(",",".", filter_input(INPUT_POST, 'vlAbast')) ;
    $ltAbast = str_replace(",",".", filter_input(INPUT_POST, 'ltAbast')) ;
    $mediaConsumo = $kmRodado/$ltAbast;
    $diariaMot = str_replace(",",".", filter_input(INPUT_POST, 'diariaMot')) ;
    $diariaAux = str_replace(",",".", filter_input(INPUT_POST, 'diariaAux')) ;
    $outrosGastos = str_replace(",",".", filter_input(INPUT_POST, 'outrosGastos')) ;
    

    //echo "$id<br>$dataAtual<br>$carga<br>$sequencia<br>$motorista<br>$veiculo<br>$carroDefeito<br>$qtdEntregas<br>$qtdEntregue<br>$qtdRestante<br>$horaSaida<br>$horaChegada<br>$tempoRota<br>$kmSaida<br>$kmChegada<br>$kmRodado<br>$vlAbast<br>$ltAbast<br> $consumo<br>$mediaConsumo<br>$diariaMot<br>$diariaAux<br>$outrosGastos";

    $atualiza = $db->prepare("UPDATE entregas_capital SET data_atual = :dataAtual, carga = :carga, sequencia = :sequencia, motorista = :motorista, veiculo = :veiculo, defeito_carro = :defeito, qtd_total = :nEntregas, qtd_entregue = :nEntregue, qtd_falta = :nFalta, hr_saida = :hrSaida, hr_chegada = :hrChegada, hr_rota = :hrRota, km_saida = :kmSaida, km_chegada = :kmChegada, km_rodado = :kmRodado, lt_abastec = :ltAbast, vl_abastec = :vlAbast, media_consumo = :mediaConsumo, diaria_motorista = :diariaMotorista, diaria_auxiliar = :diariaAuxiliar, outros_gastos = :outrosGastos WHERE identregas_capital = :id");
    $atualiza->bindValue(':dataAtual', $dataAtual);
    $atualiza->bindValue(':carga', $carga);
    $atualiza->bindValue(':sequencia', $sequencia);
    $atualiza->bindValue(':motorista', $motorista);
    $atualiza->bindValue(':veiculo', $veiculo);
    $atualiza->bindValue(':defeito', $carroDefeito);
    $atualiza->bindValue(':nEntregas', $qtdEntregas);
    $atualiza->bindValue(':nEntregue', $qtdEntregue);
    $atualiza->bindValue(':nFalta', $qtdRestante);
    $atualiza->bindValue(':hrSaida', $horaSaida);
    $atualiza->bindValue(':hrChegada', $horaChegada);
    $atualiza->bindValue(':hrRota', $tempoRota);
    $atualiza->bindValue(':kmSaida', $kmSaida);
    $atualiza->bindValue(':kmChegada', $kmChegada);
    $atualiza->bindValue(':kmRodado', $kmRodado);
    $atualiza->bindValue(':ltAbast', $ltAbast);
    $atualiza->bindValue(':vlAbast', $vlAbast);
    $atualiza->bindValue(':mediaConsumo', $mediaConsumo);
    $atualiza->bindValue(':diariaMotorista', $diariaMot);
    $atualiza->bindValue(':diariaAuxiliar', $diariaAux);
    $atualiza->bindValue(':outrosGastos', $outrosGastos);
    $atualiza->bindValue(':id', $idEntrega);

    if($atualiza->execute()){

        echo "<script>alert('Entrega Atualizada!');</script>";
        echo "<script>window.location.href='entregas.php'</script>";

    }else{
        print_r($inserir->errorInfo());
    }

}else{

    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../../entregas.php'</script>";

}

?>