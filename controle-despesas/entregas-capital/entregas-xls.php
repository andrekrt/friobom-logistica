<?php

session_start();
require("../../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if($tipoUsuario==10 || $tipoUsuario==99){

        $arquivo = 'entregas.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold">Data Atual</td>';
        $html .= '<td class="text-center font-weight-bold"> Carga </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Sequência').'  </td>';
        $html .= '<td class="text-center font-weight-bold"> Motorista </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Veículo').'  </td>';
        $html .= '<td class="text-center font-weight-bold"> Carro c/ Defeito </td>';
        $html .= '<td class="text-center font-weight-bold"> Total de Entregas </td>';
        $html .= '<td class="text-center font-weight-bold"> Entregas Realizadas </td>';
        $html .= '<td class="text-center font-weight-bold"> Entregas Restante </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Hora Saída').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Hora Chegada </td>';
        $html .= '<td class="text-center font-weight-bold"> Tempo em Rota </td>';     
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Km de Saída').'  </td>'; 
        $html .= '<td class="text-center font-weight-bold">Km de Chegada </td>';    
        $html .= '<td class="text-center font-weight-bold">Km Rodado </td>'; 
        $html .= '<td class="text-center font-weight-bold">Litros Abastecidos </td>';  
        $html .= '<td class="text-center font-weight-bold">Valor Abastecido </td>'; 
        $html .= '<td class="text-center font-weight-bold"> Consumo </td>';
        $html .= '<td class="text-center font-weight-bold">Media de Consumo </td>';
        $html .= '<td class="text-center font-weight-bold">Diaria Motorista </td>';
        $html .= '<td class="text-center font-weight-bold">Diaria Auxiliar  </td>';
        $html .= '<td class="text-center font-weight-bold">Outros Gastos </td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM entregas_capital LEFT JOIN motoristas ON entregas_capital.motorista = motoristas.cod_interno_motorista LEFT JOIN veiculos ON entregas_capital.veiculo = veiculos.cod_interno_veiculo LEFT JOIN usuarios ON entregas_capital.usuario = usuarios.idusuarios");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>'.$dado['identregas_capital']. '</td>';
            $html .= '<td>'.date("d/m/Y", strtotime($dado['data_atual'])) . '</td>';
            $html .= '<td>'.$dado['carga']. '</td>';
            $html .= '<td>'.$dado['sequencia']. '</td>';
            $html .= '<td>'. utf8_decode($dado['nome_motorista']) . '</td>';
            $html .= '<td>'. $dado['placa_veiculo']  . '</td>';
            $html .= '<td>'. utf8_decode($dado['defeito_carro']) . '</td>';
            $html .= '<td>'. $dado['qtd_total'] . '</td>';
            $html .= '<td>'. $dado['qtd_entregue'] . '</td>';
            $html .= '<td>'. $dado['qtd_falta'] . '</td>';
            $html .= '<td>'. $dado['hr_saida'] . '</td>';
            $html .= '<td>'. $dado['hr_chegada'] . '</td>';
            $html .= '<td>'. $dado['hr_rota'] . '</td>';
            $html .= '<td>'. $dado['km_saida'] . '</td>';
            $html .= '<td>'. $dado['km_chegada'] . '</td>';
            $html .= '<td>'. $dado['km_rodado'] . '</td>';
            $html .= '<td>'. str_replace(".",".",$dado['lt_abastec'])  . '</td>';
            $html .= '<td>'. str_replace(".", ",", $dado['vl_abastec'])  . '</td>';
            $html .= '<td>'. str_replace(".",",", $dado['consumo'])  . '</td>';
            $html .= '<td>'. str_replace(".",",", $dado['media_consumo'])  . '</td>';
            $html .= '<td>'. str_replace(".",",", $dado['diaria_motorista'])  . '</td>';
            $html .= '<td>'. str_replace(".",",",$dado['diaria_auxiliar'] )  . '</td>';
            $html .= '<td>'. str_replace(".",",",$dado['outros_gastos'])  . '</td>';
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