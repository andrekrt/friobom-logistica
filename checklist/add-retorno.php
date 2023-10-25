<?php

session_start();
require("../conexao.php");

$idModudulo = 10;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idCheckSaida=filter_input(INPUT_POST,'id');
    $carregamento = filter_input(INPUT_POST, 'carregamento');
    $usuario = $_SESSION['idUsuario'];
    $veiculo = filter_input(INPUT_POST, 'veiculo');
    $hrTk = filter_input(INPUT_POST, 'hrTk');
    $saida = date('Y-m-d');
    $tipo = "Check-Out";
    $cabine = filter_input(INPUT_POST, 'cabine');
    $retrovisores = filter_input(INPUT_POST,'retrovisores');
    $parabrisa = filter_input(INPUT_POST, 'parabrisa');
    $quebasol = filter_input(INPUT_POST, 'quebasol');
    $bordo = filter_input(INPUT_POST, 'bordo');
    $buzina = filter_input(INPUT_POST, 'buzina');
    $cinto = filter_input(INPUT_POST,'cinto');
    $extintor = filter_input(INPUT_POST, 'extintor');
    $triangulo = filter_input(INPUT_POST, 'triangulo');
    $macaco = filter_input(INPUT_POST, 'macaco');
    $tanque = filter_input(INPUT_POST, 'tanque');
    $janela = filter_input(INPUT_POST,'janela');
    $banco = filter_input(INPUT_POST, 'banco');
    $porta = filter_input(INPUT_POST, 'porta');
    $cambio = filter_input(INPUT_POST, 'cambio');
    $seta = filter_input(INPUT_POST, 'seta');
    $luzFreio = filter_input(INPUT_POST,'luzFreio');
    $luzRe = filter_input(INPUT_POST, 'luzRe');
    $alerta = filter_input(INPUT_POST, 'alerta');
    $luzTeto =filter_input(INPUT_POST, 'luzTeto');
    $faixas = filter_input(INPUT_POST, 'faixas');
    $pneus = filter_input(INPUT_POST, 'pneus');
    $rodas = filter_input(INPUT_POST, 'rodas');
    $estepe = filter_input(INPUT_POST,'estepe');
    $molas = filter_input(INPUT_POST, 'molas');
    $cabo = filter_input(INPUT_POST, 'cabo');
    $refrigeracao = filter_input(INPUT_POST, 'refrigeracao');
    $ventilador = filter_input(INPUT_POST, 'ventilador');
    $farolDianteiro = filter_input(INPUT_POST,'farolDianteiro');
    $farolTraseiro = filter_input(INPUT_POST, 'farolTraseiro');
    $farolNeblina = filter_input(INPUT_POST, 'farolNeblina');
    $farolAlto = filter_input(INPUT_POST, 'farolAlto');
    $luzPainel = filter_input(INPUT_POST, 'luzPainel');
    $obs = filter_input(INPUT_POST, 'obs');

    //echo count($imagem['name']);

    //  echo "$veiculo<br>$saida<br>$cabine<br>$retrovisores<br>$parabrisa<br>$quebasol<br>$bordo<br>$buzina<br>$cinto<br>$extintor<br>$triangulo<br>$macaco<br>$tanque<br>$janela<br>$banco<br>$porta<br>$cambio<br>$seta<br>$luzFreio<br>$luzRe<br>$alerta<br>$luzTeto<br>$faixas<br>$pneus<br>$rodas<br>$estepe<br>$molas<br>$cabo<br>$refrigeracao<br>$ventilador<br><br>";
    // print_r($imagem);

    $sql = $db->prepare("INSERT INTO checklist_apps_retorno02 (veiculo_ret, hr_tk_ret, carregamento_ret, tipo_checklist_ret, data_ret, cabine_ret, retrovisores_ret, parabrisa_ret, quebra_sol_ret, bordo_ret, buzina_ret, cinto_ret, extintor_ret, triangulo_ret, macaco_ret, tanque_ret, janelas_ret, banco_ret, porta_ret, cambio_ret, seta_ret, luz_freio_ret, luz_re_ret, alerta_ret, luz_teto_ret, faixas_ret, farol_dianteiro_ret, farol_traseiro_ret, farol_neblina_ret, farol_alto_ret, painel_ret, pneus_ret, rodas_ret, estepe_ret, molas_ret, cabo_forca_ret, refrigeracao_ret, ventilador_ret, obs_ret, checksaida) VALUES (:veiculo, :hrTk, :carregamento, :tipo, :dataSaida, :cabine, :retrovisores, :parabrisa, :quebraSol, :bordo, :buzina, :cinto, :extintor, :triangulo, :macaco, :tanque, :janelas, :banco, :porta, :cambio, :seta, :luzFreio, :luzRe, :alerta, :luz_teto, :faixas, :farolDianteiro, :farolTraseiro, :farolNeblina, :farolAlto, :painel, :pneus, :rodas, :estepe, :molas, :cabo_forca, :refrigeracao, :ventilador, :obs, :checksaida)");
    $sql->bindValue(':veiculo', $veiculo);
    $sql->bindValue(':hrTk', $hrTk);
    $sql->bindValue(':tipo', $tipo);
    $sql->bindValue(':carregamento', $carregamento);
    $sql->bindValue(':dataSaida', $saida);
    $sql->bindValue(':cabine', $cabine);
    $sql->bindValue(':retrovisores', $retrovisores);
    $sql->bindValue(':parabrisa', $parabrisa);
    $sql->bindValue(':quebraSol', $quebasol);
    $sql->bindValue(':bordo', $bordo);
    $sql->bindValue(':buzina', $buzina);
    $sql->bindValue(':cinto', $cinto);
    $sql->bindValue(':extintor', $extintor);
    $sql->bindValue(':triangulo', $triangulo);
    $sql->bindValue(':macaco', $macaco);
    $sql->bindValue(':tanque', $tanque);
    $sql->bindValue(':janelas', $janela);
    $sql->bindValue(':banco', $banco);
    $sql->bindValue(':porta', $porta);
    $sql->bindValue(':cambio', $cambio);
    $sql->bindValue(':seta', $seta);
    $sql->bindValue(':luzFreio', $luzFreio);
    $sql->bindValue(':luzRe', $luzRe);
    $sql->bindValue(':alerta', $alerta);
    $sql->bindValue(':luz_teto', $luzTeto);
    $sql->bindValue(':faixas', $faixas);
    $sql->bindValue(':farolDianteiro', $farolDianteiro);
    $sql->bindValue(':farolTraseiro', $farolTraseiro);
    $sql->bindValue(':farolNeblina', $farolNeblina);
    $sql->bindValue(':farolAlto', $farolAlto);
    $sql->bindValue(':painel', $luzPainel);
    $sql->bindValue(':pneus', $pneus);
    $sql->bindValue(':rodas', $rodas);
    $sql->bindValue(':estepe', $estepe);
    $sql->bindValue(':molas', $molas);
    $sql->bindValue(':cabo_forca', $cabo);
    $sql->bindValue(':refrigeracao', $refrigeracao);
    $sql->bindValue(':ventilador', $ventilador);
    $sql->bindValue(':obs', $obs);
    $sql->bindValue(':checksaida',$idCheckSaida);
    
    if($sql->execute()){
        echo "<script> alert('Check-List Realizado!!')</script>";
        echo "<script> window.location.href='checklists.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }


}else{

}

?>