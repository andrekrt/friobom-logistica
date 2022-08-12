<?php
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
session_start();
require("../conexao.php");

//pegando dados
$sqlCustos = $db->query("SELECT month(data_chegada) as mes, year(data_chegada) as ano,SUM(dias_motorista*diarias_motoristas) as diariasMotorista, SUM(dias_ajudante*diarias_ajudante) as diariasAjudante, SUM(dias_chapa*diarias_chapa) as diariasChapa, (SUM(outros_gastos_ajudante)+SUM(outros_servicos)+SUM(tomada)+SUM(descarga)+SUM(travessia)) as outrosGastos, SUM(valor_total_abast) as abastecimento, SUM(valor_transportado) as faturado FROM `viagem` GROUP BY month(data_chegada), year(data_chegada) ORDER BY year(data_chegada) DESC, month(data_chegada) DESC LIMIT 1,12");
$custos = $sqlCustos->fetchAll();

$sqlManutecoes = $db->query("SELECT month(data_aprovacao) as mes, year(data_aprovacao) as ano, SUM(vl_total+frete) as valorTotal FROM `solicitacoes_new` GROUP BY month(data_aprovacao), year(data_aprovacao) ORDER BY year(data_aprovacao) DESC, month(data_aprovacao) DESC LIMIT 1,12");
$manutecoes = $sqlManutecoes->fetchAll();

$SalarioMot = $db->query("SELECT SUM(salario) as salarioMot FROM motoristas WHERE ativo =1");
$SalarioMot = $SalarioMot->fetch();

$SalarioAjud = $db->query("SELECT SUM(salario_auxiliar) as salarioAjud FROM auxiliares_rota WHERE ativo =1");
$SalarioAjud=$SalarioAjud->fetch();

$salarioInt = $db->query("SELECT SUM(salario_colaborador+salario_extra) as salarioInt FROM colaboradores WHERE ativo =1");
$salarioInt = $salarioInt->fetch(); 

    $arquivo = 'relatorio-custo.xls';
    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td> </td>';
    $mesAtual = date('Y-m'); 
    for($i=0;$i<12; $i++): 
        $mesAtual = date('Y-m', strtotime('-1 months', strtotime(date($mesAtual))));
        $html.= ' <th scope="col" class="text-center text-nowrap" >'. date('m/Y', strtotime($mesAtual)) .'</th>';
       
    endfor;
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<th> Diarias Motoristas </th>' ;
    foreach($custos as $custo){
        $motoristas[] =$custo['diariasMotorista'];
        $faturado[] = $custo['faturado'];

        $html .= '<td>'. number_format($custo['diariasMotorista'],2,",",".")  .'</td>';
    }
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<th> Diarias Ajudante</th>' ;
    foreach($custos as $custo){
        $html .= '<td>'. number_format($custo['diariasAjudante'],2,",",".")  .'</td>';
        $ajudantes[] = $custo['diariasAjudante'];
    }
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<th> Diarias Chapas</th>' ;
    foreach($custos as $custo){
        $html .= '<td>'. number_format($custo['diariasChapa'],2,",",".")  .'</td>';
        $chapas[] = $custo['diariasChapa'];
    }
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<th> Outras Despesas</th>' ;
    foreach($custos as $custo){
        $html .= '<td>'. number_format($custo['outrosGastos'],2,",",".")  .'</td>';
        $outros[] =$custo['outrosGastos'];
    }
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<th> Abastecimento</th>' ;
    foreach($custos as $custo){
        $html .= '<td>'. number_format($custo['abastecimento'],2,",",".")  .'</td>';
        $abastecimento[]=$custo['abastecimento'];
    }
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<th> Manutenção</th>' ;
    foreach($manutecoes as $manutecao){
        $html .= '<td>'. number_format($manutecao['valorTotal'],2,",",".")  .'</td>';
        $valoreManutencao[] = $manutecao['valorTotal'];
    }
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<th> Salário Motoristas</th>' ;
    for($i=0;$i<12;$i++){
        $html .= '<td>'. number_format($SalarioMot['salarioMot'],2,",",".")  .'</td>';
    }
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<th> Salário Ajudantes</th>' ;
    for($i=0;$i<12;$i++){
        $html .= '<td>'. number_format($SalarioAjud['salarioAjud'],2,",",".")  .'</td>';
    }
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<th> Salário Internos</th>' ;
    for($i=0;$i<12;$i++){
        $html .= '<td>'. number_format($salarioInt['salarioInt'],2,",",".")  .'</td>';
    }
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<th> TOTAL</th>' ;
    for($i=0;$i<12;$i++){
        $total = $total = $motoristas[$i]+$ajudantes[$i]+$chapas[$i]+$outros[$i]+$abastecimento[$i]+$valoreManutencao[$i]+$SalarioMot['salarioMot']+$SalarioAjud['salarioAjud']+$salarioInt['salarioInt'];
        $html .= '<td>'. number_format($total,2,",",".")  .'</td>';
        $valorTotal[] = $total;
    }
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '<th> Custo %</th>' ;
    for($i=0;$i<12;$i++){
        $perCusto = ($valorTotal[$i]/$faturado[$i])*100;
        $html .= '<td>'. number_format($perCusto,2,",",".")  .'</td>';
    }
    $html .= '</tr>';

    $html .= '</table>';

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$arquivo.'"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');

    echo $html;

?>