<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] == 2 || $_SESSION['tipoUsuario'] == 99){

    $idUsuario = $_SESSION['idUsuario'];

    $dataCheck = date("Y-m-d");
    $placaVeiculo = filter_input(INPUT_POST, 'placa');
    $tipoVeiculo = filter_input(INPUT_POST, 'tipoVeiculo');
    $kmInicial = filter_input(INPUT_POST, 'kmInicial');
    $limpeza = filter_input(INPUT_POST, 'limpeza');
    $retrovisores = filter_input(INPUT_POST, 'retrovisores');
    $paraBrisas = filter_input(INPUT_POST, 'paraBrisas');
    $quebraSol = filter_input(INPUT_POST, 'quebraSol');
    $pcBordo = filter_input(INPUT_POST, 'pcBordo');
    $buzina = filter_input(INPUT_POST, 'buzina');
    $cintoSeguranca = filter_input(INPUT_POST, 'cinto');
    $extintor = filter_input(INPUT_POST, 'extintor');
    $trianguloSinalizacao = filter_input(INPUT_POST, 'triangulo');
    $macacoChave = filter_input(INPUT_POST, 'macaco');
    $tanqueCombustível = filter_input(INPUT_POST, 'tanqueCombustivel');
    $vidroJanela = filter_input(INPUT_POST, 'janelas');
    $luzDirecao = filter_input(INPUT_POST, 'luzDirecao');
    $luzFreio = filter_input(INPUT_POST, 'luzFreio');
    $luzRe = filter_input(INPUT_POST, 'luzRe');
    $piscaAlerta = filter_input(INPUT_POST, 'piscaAlerta');
    $luzTeto = filter_input(INPUT_POST, 'luzTeto');
    $faixaRefletiva = filter_input(INPUT_POST, 'faixaRefletiva');
    $farolDianteiro = filter_input(INPUT_POST, 'farolDianteiro');
    $farolTraseiro = filter_input(INPUT_POST, 'farolTraseiro');
    $farolNeblina = filter_input(INPUT_POST, 'farolNeblina');
    $farolAlto = filter_input(INPUT_POST, 'farolAlto');
    $luzPainel = filter_input(INPUT_POST, 'luzPainel');
    $pneus = filter_input(INPUT_POST, 'pneus');
    $rodas = filter_input(INPUT_POST, 'rodas');
    $estepe = filter_input(INPUT_POST, 'estepe');
    $molas = filter_input(INPUT_POST, 'molas');
    $caboForca = filter_input(INPUT_POST, 'caboForca');
    $observacoes = filter_input(INPUT_POST, 'observacoes');
    $situacao = 'Saindo para Rota';

   // echo "$placaVeiculo<br>$tipoVeiculo<br>$kmInicial<br>$limpeza<br>$retrovisores<br>$paraBrisas<br>$quebraSol<br>$pcBordo<br>$buzina<br>$cintoSeguranca<br>$extintor<br>$trianguloSinalizacao<br>$macacoChave<br>$tanqueCombustível<br>$vidroJanela<br>$luzDirecao<br>$luzFreio<br>$luzRe<br>$piscaAlerta<br>$luzTeto<br>$faixaRefletiva<br>$farolDianteiro<br>$farolTraseiro<br>$farolNeblina<br>$farolAlto<br>$luzPainel<br>$pneus<br>$rodas<br>$estepe<br>$molas<br>$caboForca<br>$observacoes";

   $sql = "INSERT INTO check_list (data_check, placa_veiculo, tipo_veiculo, km_inicial, limpeza, retrovisores, para_brisa, quebra_sol, pc_bordo, buzina, cinto, extintor, triangulo, macaco_chave, tanque_combustivel, janelas, setas, luz_freio, luz_re, pisca_alerta, luzes_teto, faixas_refletivas, farol_dianteiro, farol_traseiro, farol_neblina, farol_alto, luzes_painel, pneus, rodas, pneu_estepe, molas, cabo_forca, observacoes,situacao, usuarios_idusuarios) VALUES (:dataCheck, :placaVeiculo, :tipoVeiculo, :kmInicial, :limpeza, :retrovisores, :paraBrisa, :quebraSol, :pcBordo, :buzina, :cinto, :extintor, :triangulo, :macacoChave, :tanqueCombustivel, :janelas, :setas, :luzFreio, :luzRe, :piscaAlerta, :luzTeto, :faixasReflexivas, :farolDianteiro, :farolTraseiro, :farolNeblina, :farolAlto, :luzPainel, :pneus, :rodas, :estepe, :molas, :caboForca, :observacoes, :situacao, :idUsuario)";

   $sql = $db->prepare($sql);
   $sql->bindValue(':dataCheck', $dataCheck);
   $sql->bindValue(':placaVeiculo', $placaVeiculo);
   $sql->bindValue(':tipoVeiculo', $tipoVeiculo);
   $sql->bindValue(':kmInicial', $kmInicial);
   $sql->bindValue(':limpeza', $limpeza);
   $sql->bindValue(':retrovisores', $retrovisores);
   $sql->bindValue(':paraBrisa', $paraBrisas);
   $sql->bindValue(':quebraSol', $quebraSol);
   $sql->bindValue(':pcBordo', $pcBordo);
   $sql->bindValue(':buzina', $buzina);
   $sql->bindValue(':cinto', $cintoSeguranca);
   $sql->bindValue(':extintor', $extintor);
   $sql->bindValue(':triangulo', $trianguloSinalizacao);
   $sql->bindValue(':macacoChave', $macacoChave);
   $sql->bindValue(':tanqueCombustivel', $tanqueCombustível);
   $sql->bindValue(':janelas', $vidroJanela);
   $sql->bindValue(':setas', $luzDirecao);
   $sql->bindValue(':luzFreio', $luzFreio);
   $sql->bindValue(':luzRe', $luzRe);
   $sql->bindValue(':piscaAlerta', $piscaAlerta);
   $sql->bindValue(':luzTeto', $luzTeto);
   $sql->bindValue(':faixasReflexivas', $faixaRefletiva);
   $sql->bindValue(':farolDianteiro', $farolDianteiro);
   $sql->bindValue(':farolTraseiro', $farolTraseiro);
   $sql->bindValue(':farolNeblina', $farolNeblina);
   $sql->bindValue(':farolAlto', $farolAlto);
   $sql->bindValue(':luzPainel', $luzPainel);
   $sql->bindValue(':pneus', $pneus);
   $sql->bindValue(':rodas', $rodas);
   $sql->bindValue(':estepe', $estepe);
   $sql->bindValue(':molas', $molas);
   $sql->bindValue(':caboForca', $caboForca);
   $sql->bindValue(':observacoes', $observacoes);
   $sql->bindValue(':situacao', $situacao);
   $sql->bindValue(':idUsuario', $idUsuario);

   
   if($sql->execute()){
        header("Location:check-list.php");
   }else{
        print_r($sql->errorInfo());
   }
    
}else{
    header("Location:form-check.php");
}

?>

