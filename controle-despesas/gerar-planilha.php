<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Planilha</title>
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="../assets/favicon/site.webmanifest">
        <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body>
        <?php 
        
            if($_SESSION['tipoUsuario'] != 4){
                $arquivo = 'despesas.xls';
                $html = '';
                $html .= '<table border="1">';
                $html .= '<tr>';
                $html .= '<td class="text-center font-weight-bold"> Código Veículo </td>';
                $html .= '<td class="text-center font-weight-bold"> Tipo Veículo </td>';
                $html .= '<td class="text-center font-weight-bold"> Placa Veículo </td>';
                $html .= '<td class="text-center font-weight-bold"> Código Motorista </td>';
                $html .= '<td class="text-center font-weight-bold"> Motorista </td>';
                $html .= '<td class="text-center font-weight-bold"> Data Carregamento </td>';
                $html .= '<td class="text-center font-weight-bold"> Data Saída </td>';
                $html .= '<td class="text-center font-weight-bold"> Data Retorno </td>';
                $html .= '<td class="text-center font-weight-bold"> Dias em Rota </td>';
                $html .= '<td class="text-center font-weight-bold"> Carregamento </td>';
                $html .= '<td class="text-center font-weight-bold"> Código Rota </td>';
                $html .= '<td class="text-center font-weight-bold"> Rota </td>';
                $html .= '<td class="text-center font-weight-bold"> Valor Transportado </td>';
                $html .= '<td class="text-center font-weight-bold"> Valor Devolvido </td>';
                $html .= '<td class="text-center font-weight-bold"> Valor Liquído </td>';
                $html .= '<td class="text-center font-weight-bold"> Qtde Entregas </td>';
                $html .= '<td class="text-center font-weight-bold"> Carga </td>';
                $html .= '<td class="text-center font-weight-bold"> Peso Carga </td>';
                $html .= '<td class="text-center font-weight-bold"> Km Saída </td>';
                $html .= '<td class="text-center font-weight-bold"> Tk Saída </td>';
                $html .= '<td class="text-center font-weight-bold"> Km 1° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Tk 1° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Litros 1° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Valor 1° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km 1° Percusso Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km 1° Percusso Tk Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km/L Sem TK Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Local 1° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km 2° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Tk 2° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Litros 2° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Valor 2° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km 2° Percusso Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km 2° Percusso TK Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km/L Sem Tk 2 Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Local 2° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km 3° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Tk 3° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Litros 3° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Valor 3° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km 3° Percusso Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km 3° Percusso Tk Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km/L Sem Tk 3 Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Local 3° Abastecimento Externo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km Abastecimento Interno </td>';
                $html .= '<td class="text-center font-weight-bold"> Tk Abastecimento Interno</td>';
                $html .= '<td class="text-center font-weight-bold"> Litros Abastecimento Interno</td>';
                $html .= '<td class="text-center font-weight-bold"> Valor Abastecimento Interno </td>';
                $html .= '<td class="text-center font-weight-bold"> Km Percusso Interno </td>';
                $html .= '<td class="text-center font-weight-bold"> Km Percusso Interno Tk </td>';
                $html .= '<td class="text-center font-weight-bold"> Local Abastecimento Interno</td>';
                $html .= '<td class="text-center font-weight-bold"> Km/L Sem Tk Interno </td>';
                $html .= '<td class="text-center font-weight-bold"> Km Rodado </td>';
                $html .= '<td class="text-center font-weight-bold"> Km Final </td>';
                $html .= '<td class="text-center font-weight-bold"> Litros </td>';
                $html .= '<td class="text-center font-weight-bold"> Media Sem Tk </td>';
                $html .= '<td class="text-center font-weight-bold"> Consumo Tk </td>';
                $html .= '<td class="text-center font-weight-bold"> Media Tk </td>';
                $html .= '<td class="text-center font-weight-bold"> Valor Total Abastecimento </td>';
                $html .= '<td class="text-center font-weight-bold"> Diarias Motoristas </td>';
                $html .= '<td class="text-center font-weight-bold"> Diarias Ajudante </td>';
                $html .= '<td class="text-center font-weight-bold"> Diarias Chapa </td>';
                $html .= '<td class="text-center font-weight-bold"> Dias Motoristas </td>';
                $html .= '<td class="text-center font-weight-bold"> Dias Ajudante </td>';
                $html .= '<td class="text-center font-weight-bold"> Dias Chapa </td>';
                $html .= '<td class="text-center font-weight-bold"> Outros Gastos </td>';
                $html .= '<td class="text-center font-weight-bold"> Tomada </td>';
                $html .= '<td class="text-center font-weight-bold"> Descarga </td>';
                $html .= '<td class="text-center font-weight-bold"> Travessia </td>';
                $html .= '<td class="text-center font-weight-bold"> Outros Serviços </td>';
                $html .= '<td class="text-center font-weight-bold"> Ajudante </td>';
                $html .= '</tr>';
                
                $sql = $db->query("SELECT * FROM viagem");
                $dados = $sql->fetchAll();
                foreach($dados as $dado){
                    $html .= '<tr>';
                    $html .= '<td>' .$dado['cod_interno_veiculo'].  '</td>';
                    $html .= '<td>' .$dado['tipo_veiculo'].  '</td>';
                    $html .= '<td>' .$dado['placa_veiculo'].  '</td>';
                    $html .= '<td>' .$dado['cod_interno_motorista'].  '</td>';
                    $html .= '<td>' .$dado['nome_motorista'].  '</td>';
                    $html .= '<td>' .$dado['data_carregamento'].  '</td>';
                    $html .= '<td>' .$dado['data_saida'].  '</td>';
                    $html .= '<td>' .$dado['data_chegada'].  '</td>';
                    $html .= '<td>' .$dado['dias_em_rota'].  '</td>';
                    $html .= '<td>' .$dado['num_carregemento'].  '</td>';
                    $html .= '<td>' .$dado['cod_rota'].  '</td>';
                    $html .= '<td>' .$dado['nome_rota'].  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['valor_transportado']) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['valor_devolvido']) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['valor_liquido']) .  '</td>';
                    $html .= '<td>' .$dado['qtd_entregas'].  '</td>';
                    $html .= '<td>' .$dado['num_carga'].  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['peso_carga']) .  '</td>';
                    $html .= '<td>' .$dado['km_saida'].  '</td>';
                    $html .= '<td>' .$dado['hr_tk_saida'].  '</td>';
                    $html .= '<td>' .$dado['km_abast1'].  '</td>';
                    $html .= '<td>' .$dado['hr_tk_abast1'].  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['lt_abast1']) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['valor_abast1'] ) .  '</td>';
                    $html .= '<td>' .$dado['km_perc1'].  '</td>';
                    $html .= '<td>' .$dado['km_pec1_tk'].  '</td>';
                    $html .= '<td>' .$dado['kmPorLtSemTk'].  '</td>';
                    $html .= '<td>' .$dado['localAbast1'].  '</td>';
                    $html .= '<td>' .$dado['km_abast2'].  '</td>';
                    $html .= '<td>' .$dado['hr_tk_abast2'].  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['lt_abast2'] ) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['valor_abast2']) .  '</td>';
                    $html .= '<td>' .$dado['km_perc2'].  '</td>';
                    $html .= '<td>' .$dado['km_pec2_tk_'].  '</td>';
                    $html .= '<td>' .$dado['kmPorLtSemTk2'].  '</td>';
                    $html .= '<td>' .$dado['localAbast2'].  '</td>';
                    $html .= '<td>' .$dado['km_abast3'].  '</td>';
                    $html .= '<td>' .$dado['hr_tk_abast3'].  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['lt_abast3'] ) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['valor_abast3']) .  '</td>';
                    $html .= '<td>' .$dado['km_perc3'].  '</td>';
                    $html .= '<td>' .$dado['km_pec3_tk'].  '</td>';
                    $html .= '<td>' .$dado['kmPorLtSemTk3'].  '</td>';
                    $html .= '<td>' .$dado['localAbast3'].  '</td>';
                    $html .= '<td>' .$dado['km_abast4'].  '</td>';
                    $html .= '<td>' .$dado['hr_tk_abast4'].  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['lt_abast4']) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",", $dado['valor_abast4']) .  '</td>';
                    $html .= '<td>' .$dado['km_perc4'].  '</td>';
                    $html .= '<td>' .$dado['km_perc4_tk'].  '</td>';
                    $html .= '<td>' .$dado['kmPorLtSemTk4'].  '</td>';
                    $html .= '<td>' .$dado['localAbast4'].  '</td>';
                    $html .= '<td>' .$dado['km_rodado'].  '</td>';
                    $html .= '<td>' .$dado['km_final'].  '</td>';
                    $html .= '<td>' . str_replace(".", ",", $dado['litros'] ).  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['mediaSemTk'] ) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['consumo_tk']) .  '</td>';
                    $html .= '<td>' . str_replace(".", ".",$dado['media_comtk']) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['valor_total_abast']) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['diarias_motoristas'] ) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['diarias_ajudante']) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['diarias_chapa']) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['dias_motorista']) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",", $dado['dias_ajudante']) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['dias_chapa']) .  '</td>';
                    $html .= '<td>' . str_replace(".", ",",$dado['outros_gastos_ajudante'] ) .  '</td>';
                    $html .= '<td>' .$dado['tomada'].  '</td>';
                    $html .= '<td>' .$dado['descarga'].  '</td>';
                    $html .= '<td>' .$dado['travessia'].  '</td>';
                    $html .= '<td>' .$dado['outros_servicos'].  '</td>';
                    $html .= '<td>' .$dado['nome_ajudante'].  '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$arquivo.'"');
                header('Cache-Control: max-age=0');
                header('Cache-Control: max-age=1');

                echo $html;
                exit;
            }
            
        
        ?>
    </body>
</html>