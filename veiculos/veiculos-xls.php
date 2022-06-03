<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
    
if($_SESSION['tipoUsuario'] != 4 ){

    $arquivo = 'veiculos.xls';
    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Código').'  </td>';
    $html .= '<td class="text-center font-weight-bold"> Tipo </td>';
    $html .= '<td class="text-center font-weight-bold"> Categoria </td>';
    $html .= '<td class="text-center font-weight-bold"> Placa </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Peso Máximo').' </td>';
    $html .= '<td class="text-center font-weight-bold"> Cubagem </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Data Revisão Óleo').' </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Revisão Óleo (Km)').' </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Data Revisão Diferencial').' </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Revisão Diferencial (Km)') .'</td>';
    $html .= '<td class="text-center font-weight-bold"> Km Atual </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Km Restante de Revisão').' </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Status Revisão').'</td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Km último Alinhamento').'</td>';
    $html .= '<td class="text-center font-weight-bold">Status ALinhamento</td>';
    $html .= '<td class="text-center font-weight-bold">Ativo</td>';
    $html .= '</tr>';

    $revisao = $db->query("SELECT * FROM veiculos");
    $dados = $revisao->fetchAll();
    foreach($dados as $dado){

        $kmRestante = $dado['km_atual']-$dado['km_ultima_revisao'];
        $kmRestanteAlinhamento = $dado['km_atual']-$dado['km_alinhamento'];
        if ($dado['categoria']=='Truck' && $kmRestante >= 20000) {
            $situacao = "Pronto para Revisão";
        } elseif($dado['categoria']=='Toco' && $kmRestante >= 20000) {
            $situacao = "Pronto para Revisão";
        }elseif($dado['categoria']=='Mercedinha' && $kmRestante >= 15000){
            $situacao = "Pronto para Revisão";
        }else{
            $situacao = "Aguardando";
        }
            //situacao alinhamento
        if($kmRestanteAlinhamento>=7000){
            $situacaoAlinhamento = 'Pronto para Alinhamento';
        }else{
            $situacaoAlinhamento = 'Aguardando';
        }

        if($dado['ativo']==0){
            $ativo = "NÃO";
        }else{
            $ativo = "SIM";
        }

        $html .= '<tr>';
        $html .= '<td>'.$dado['cod_interno_veiculo'] .'</td>';
        $html .= '<td>'. utf8_decode($dado['tipo_veiculo'])  .'</td>';
        $html .= '<td>'. utf8_decode($dado['categoria']) .'</td>';
        $html .= '<td>'. $dado['placa_veiculo'] .'</td>';
        $html .= '<td>'. $dado['peso_maximo'] .'</td>';
        $html .= '<td>'. $dado['cubagem'] .'</td>';
        $html .= '<td>'. date("d/m/Y", strtotime($dado['data_revisao_oleo']))  .'</td>';
        $html .= '<td>'. $dado['km_ultima_revisao'] .'</td>';
        $html .= '<td>'. date("d/m/Y", strtotime($dado['data_revisao_diferencial']))  .'</td>';
        $html .= '<td>'. $dado['km_revisao_diferencial'] .'</td>';
        $html .= '<td>'. $dado['km_atual']  .'</td>';
        $html .= '<td>'. $kmRestante .'</td>';
        $html .= '<td>'. utf8_decode($situacao)  .'</td>';
        $html .= '<td>'. $kmRestanteAlinhamento .'</td>';
        $html .= '<td>'. utf8_decode($situacaoAlinhamento) .'</td>';
        $html .= '<td>'. utf8_decode($ativo) .'</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$arquivo.'"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');

    echo $html;

}

?>