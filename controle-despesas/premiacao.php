<?php

require("../conexao-on.php");
include("../conexao-oracle.php");

$idViagemn = filter_input(INPUT_GET, 'id');

//pegando qtd de entregas, media de combustivel e veiculo
$sql = $db->prepare("SELECT  valor_devolvido,qtd_entregas, media_comtk, viagem.placa_veiculo, meta_combustivel, meta_dias, dias_em_rota, num_carregemento FROM viagem LEFT JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo LEFT JOIN rotas ON viagem.cod_rota=rotas.cod_rota WHERE iddespesas=:iddespesa");
$sql->bindValue(':iddespesa', $idViagemn);
if($sql->execute()){
    $qtdEntregas = $sql->fetch();
    $qtdEntregasReal = $qtdEntregas['qtd_entregas'];
    $vlDevoldido = $qtdEntregas['valor_devolvido'];
    $metaComb = $qtdEntregas['meta_combustivel'];
    $metadias = $qtdEntregas['meta_dias']?$qtdEntregas['meta_dias']:1;
    $mediaComb = $qtdEntregas['media_comtk'];
    $diasRota = $qtdEntregas['dias_em_rota']==0?1:$qtdEntregas['dias_em_rota'];
    $percComb = $mediaComb/$metaComb;
    $percDias = $metadias/$diasRota;
    $carregamento = $qtdEntregas['num_carregemento'];
}else{
    print_r($sql->errorInfo());
}

//pegando qtd de devoluções
$qtdDev = $dbora->prepare("SELECT COUNT(DISTINCT codcli) as qtdDev FROM friobom.pcnfsaid WHERE numcar=:carreg AND coddevol IS NOT NULL");
$qtdDev->bindValue(':carreg', $carregamento);
if($qtdDev->execute()){
    $qtdDev=$qtdDev->fetch();
    $qtdDev=$qtdDev['QTDDEV'];
}else{
    print_r($qtdDev->errorInfo());
}
//entregas liquidas
$entregasLiq = $qtdEntregasReal-$qtdDev;

 if($qtdDev>0){
    $percDev = 0;
}else{
    $percDev= 1;
    $percDev*100;
} 
$valorDev= $percDev*0.1*$entregasLiq;

if($percComb>1){
    $valorComb = 1*0.1*$entregasLiq;
}else{
    $valorComb= 0;
}

if($percDias>1){
    $valorRota= 1*0.1*$entregasLiq;
}else{
    $valorRota= 0;
}

//qtd de ocorrencias mau uso do fusion
$qtdMalUso = $db->prepare("SELECT * FROM ocorrencias WHERE num_carregamento = :carregamento AND tipo_ocorrencia = :ocorrencia");
$qtdMalUso->bindValue(':carregamento', $carregamento);
$qtdMalUso->bindValue(':ocorrencia', "Mau Uso do Fusion");
$qtdMalUso->execute();
$qtdMalUso=$qtdMalUso->rowCount();
if($qtdMalUso==0){
    $percFusion = 1;
}else{
    $percFusion=0;
}
$valorFusion= $percFusion*0.5*$entregasLiq;

//qtd de ocoorrencias por velocidade
$velExc = $db->prepare("SELECT * FROM ocorrencias WHERE num_carregamento=:carregamento AND tipo_ocorrencia=:ocorrencia");
$velExc->bindValue(':carregamento', $carregamento);
$velExc->bindValue(':ocorrencia', "Velocidade Excedida");
$velExc->execute();
$qtdvelExc = $velExc->rowCount();
if($qtdvelExc==0){
    $percVel = 1;
}else{
    $percVel = 0;
}
$valorVeloc= $percVel*0.1*$entregasLiq;

//verificando checklist
$check = $db->prepare("SELECT * FROM checklist_apps_retorno02 LEFT JOIN checklist_apps ON checklist_apps_retorno02.checksaida = checklist_apps.id  WHERE carregamento_ret = :carregamento");
$check->bindValue(':carregamento', $carregamento);
if($check->execute()){
    $checks=$check->fetch();
    if(
        $checks['cabine']==$checks['cabine'] &&
        $checks['retrovisores']==$checks['retrovisores_ret'] &&
        $checks['parabrisa']==$checks['parabrisa_ret'] &&
        $checks['quebra_sol']==$checks['quebra_sol_ret'] &&
        $checks['bordo']==$checks['bordo_ret'] &&
        $checks['buzina']==$checks['buzina_ret'] &&
        $checks['cinto']==$checks['cinto_ret'] &&
        $checks['extintor']==$checks['extintor_ret'] &&
        $checks['triangulo']==$checks['triangulo_ret'] &&
        $checks['macaco']==$checks['macaco_ret'] &&
        $checks['tanque']==$checks['tanque_ret'] &&
        $checks['janelas']==$checks['janelas_ret'] &&
        $checks['banco']==$checks['banco_ret'] &&
        $checks['porta']==$checks['porta_ret'] &&
        $checks['cambio']==$checks['cambio_ret'] &&
        $checks['seta']==$checks['seta_ret'] &&
        $checks['luz_freio']==$checks['luz_freio_ret'] &&
        $checks['luz_re']==$checks['luz_re_ret'] &&
        $checks['alerta']==$checks['alerta_ret'] &&
        $checks['luz_teto']==$checks['luz_teto_ret'] &&
        $checks['faixas']==$checks['faixas_ret'] &&
        $checks['farol_dianteiro']==$checks['farol_dianteiro_ret']&& 
        $checks['farol_traseiro']==$checks['farol_traseiro_ret']&& 
        $checks['farol_neblina']==$checks['farol_neblina_ret']&& 
        $checks['farol_alto']==$checks['farol_alto_ret'] &&
        $checks['painel']==$checks['painel_ret'] &&
        $checks['rodas']==$checks['rodas_ret'] &&
        $checks['pneus']==$checks['pneus_ret'] &&
        $checks['estepe']==$checks['estepe_ret'] &&
        $checks['molas']==$checks['molas_ret'] &&
        $checks['cabo_forca']==$checks['cabo_forca_ret'] &&
        $checks['refrigeracao']==$checks['refrigeracao_ret'] &&
        $checks['ventilador']==$checks['ventilador_ret']
    ){
        $variavelCheck = 1;
    }else{
        $variavelCheck = 0;
    }
    $valorCheck= $variavelCheck*0.1*$entregasLiq;

}else{
    print_r($check->errorInfo());
}

$tablePremiacao = '
<table >
    <tr >
        <th colspan="16" style="font-size:10pt;border:1px solid black;">Meta-Despesas</th>
    </tr>
    <tr>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">Entregas TT</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">Qtd Dev</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">Entreg Liq</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">% Fusion</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">% Check List</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">Meta Média</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">Real Média</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">% Média Comb</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">Meta Dev</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">Qtd Dev</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">% Dev</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">Meta Dia Rota</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">Dia em Rota</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">% Dias Rota</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">% Vel Máx</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">Prêmio Total</td>
    </tr>
    <tr>
        <td rowspan="2" style="text-align: center; font-size:8pt;border:1px solid black;">'.$qtdEntregasReal.'</td> 
        <td rowspan="2" style="text-align: center; font-size:8pt;border:1px solid black;">'.$qtdDev.'</td> 
        <td rowspan="2" style="text-align: center; font-size:8pt;border:1px solid black;">'.$entregasLiq.'</td> 
        <td style="text-align: center; font-size:8pt;border:1px solid black;">'.($percFusion*100).'%</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">'.($variavelCheck*100).'%</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">'.number_format($metaComb,2,",",".").' Km/L</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">'.number_format($mediaComb,2,",",".").' Km/L</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">'. (number_format($percComb,2)*100).' %</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">0</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">'.$qtdDev.'</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">'. $percDev*100 .'% </td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">'. $metadias .'</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">'. number_format($diasRota,2,",",".") .'</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">'. number_format(($metadias/$diasRota)*100,2,",",".").'%</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">'. ($percVel*100).'%</td>
        
    </tr>
    <tr>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">R$ '. number_format($valorFusion,2,",",".").'</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">R$ '.number_format($valorCheck,2,",",".") .'</td>
        <td colspan="3" style="text-align: center; font-size:8pt;border:1px solid black;">R$ '.number_format($valorComb,2,",",".") .' </td>
        <td colspan="3" style="text-align: center; font-size:8pt;border:1px solid black;">R$ '. number_format($valorDev,2,",",".") .'</td>
        <td colspan="3" style="text-align: center; font-size:8pt;border:1px solid black;">R$ '.number_format($valorRota,2,",",".") .'</td>
        <td style="text-align: center; font-size:8pt;border:1px solid black;">'.number_format($valorVeloc,2,",",".") .'</td>
        <td rowspan="2" style="text-align: center; font-size:8pt;border:1px solid black;">R$ '.number_format($valorFusion+$valorCheck+$valorComb+$valorDev+$valorRota+$valorVeloc,2,",",".").'</td>
    </tr>
</table>
';

//echo $tablePremiacao;
?>

    