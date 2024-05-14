<?php

use Mpdf\Tag\Input;

session_start();
require("../conexao.php");

$idModudulo = 22;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];
    $usuario = $_SESSION['idUsuario'];
    $mdfe = filter_input(INPUT_POST, 'mdfe');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $data = filter_input(INPUT_POST, 'dia');
    $hrInicio = filter_input(INPUT_POST, 'hrInicio');
    $hrFim = filter_input(INPUT_POST, 'hrFim');
    $timeInicio = new DateTime($hrInicio);
    $timeFim = new DateTime($hrFim);
    $timeDiferenca = $timeInicio->diff($timeFim);
    $horasTrabalhada = $timeDiferenca->format('%H:%I');
    $paradaInicio = $_POST['paradaInicio'];
    $paradaFim = $_POST['paradaFinal'];
    $tempoParado = '00:00';
    $textoParadas = '';

    for($i=0;$i<count($paradaInicio);$i++){
        // pegando o tempo de cada parada
        list($horaInicio, $minutoInicio)= explode(':', $paradaInicio[$i]);
        $formula = '-'. $horaInicio . 'hours -' . $minutoInicio . 'minutes';
        $parada = date('H:i', strtotime($formula, strtotime($paradaFim[$i])));
        
        // somando o tempo parado
        list($horaParada, $minutoParada)=explode(':', $parada);
        $formulaParada = '+'. $horaParada . 'hours +' . $minutoParada . 'minutes';
        $tempoParado = date('H:i', strtotime($formulaParada, strtotime($tempoParado)));

        $textoParadas .= 'Início: ' .$paradaInicio[$i]. ' Fim: '.$paradaFim[$i]."<br>";
    }

    list($horaLiq, $minutoLiq) = explode(':', $tempoParado);
    $formulaLiq = '-' .$horaLiq. 'hours -' .$minutoLiq. 'minutes';
    $tempoLiq = date('H:i', strtotime($formulaLiq, strtotime($horasTrabalhada)));


    $db->beginTransaction();

    try{
             
        //verificar se já existe a tag
        $sqlConsulta = $db->prepare("SELECT * FROM motoristas_ponto WHERE motorista=:motorista AND data_ponto=:dataPonto");
        $sqlConsulta->bindValue(':motorista', $motorista);
        $sqlConsulta->bindValue(':dataPonto', $data);
        $sqlConsulta->execute();
        if($sqlConsulta->rowCount()>=2){
            $_SESSION['msg'] = 'O motorista '. $motorista. ' já tem dois registros para o dia ' . $data;
            $_SESSION['icon']='warning';
            header("Location: mdfe.php");
            exit();
        }
        $sql = $db->prepare("INSERT INTO motoristas_ponto (mdfe, motorista, data_ponto, hora_inicio, hora_final, tempo_parado, hrs_trabalhada, hrs_parada, hrs_trabalhada_liq, usuario, filial) VALUES (:mdfe, :motorista, :dataPonto, :hrInicio, :hrFinal, :tempoParado, :hrTrabalhada, :hrParada, :hrLiquido, :usuario, :filial)");
        $sql->bindValue(':mdfe', $mdfe);
        $sql->bindValue(':motorista', $motorista);
        $sql->bindValue(':dataPonto', $data);
        $sql->bindValue(':hrInicio', $hrInicio);
        $sql->bindValue(':hrFinal', $hrFim);
        $sql->bindValue(':tempoParado', $textoParadas);
        $sql->bindValue(':hrTrabalhada', $horasTrabalhada);
        $sql->bindValue(':hrParada', $tempoParado);
        $sql->bindValue(':hrLiquido', $tempoLiq);
        $sql->bindValue(':usuario', $usuario);
        $sql->bindValue(':filial', $filial);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Ponto Registrado com Sucesso';
        $_SESSION['icon']='success';
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Registrar Ponto';
        $_SESSION['icon']='error';
    }    

}else{
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
}
header("Location: mdfe.php");
exit();
?>