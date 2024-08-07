<?php
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
session_start();
require("../conexao.php");

$idModudulo = 7;
$idUsuario = $_SESSION['idUsuario'];

$filial = $_SESSION['filial'];
if($filial===99){
    $condicao = " ";
}else{
    $condicao = "AND viagem.filial=$filial";
}

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
        $arquivo = 'despesas.xls';
        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Filial','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Código Veículo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Tipo Veículo','ISO-8859-1', 'UTF-8').' </td>';
        $html .= '<td class="text-center font-weight-bold">'.mb_convert_encoding('Placa Veículo','ISO-8859-1', 'UTF-8')  .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding(' Código Motorista','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold"> Motorista </td>';
        $html .= '<td class="text-center font-weight-bold"> Data Carregamento </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Data Saída','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold"> Data Retorno </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Mês Retorno','ISO-8859-1', 'UTF-8')  .'</td>';
        $html .= '<td class="text-center font-weight-bold"> Ano Retorno </td>';
        $html .= '<td class="text-center font-weight-bold"> Dias em Rota </td>';
        $html .= '<td class="text-center font-weight-bold"> Carregamento </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Código Rota','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold"> Rota </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor Transportado </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor Devolvido </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Valor Liquído','ISO-8859-1', 'UTF-8')  .'</td>';
        $html .= '<td class="text-center font-weight-bold"> Qtde Entregas </td>';
        $html .= '<td class="text-center font-weight-bold"> Carga </td>';
        $html .= '<td class="text-center font-weight-bold"> Peso Carga </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Km Saída','ISO-8859-1', 'UTF-8')  .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Tk Saída','ISO-8859-1', 'UTF-8')   .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding(' Km 1° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Tk 1° Abastecimento Externo','ISO-8859-1', 'UTF-8')  .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Litros 1° Abastecimento Externo','ISO-8859-1', 'UTF-8')  .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Valor 1° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Km 1° Percusso Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Km 1° Percusso Tk Externo','ISO-8859-1', 'UTF-8' ).'</td>';
        $html .= '<td class="text-center font-weight-bold"> Km/L Sem TK Externo </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Local 1° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('NF 1° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Km 2° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Tk 2° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Litros 2° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Valor 2° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Km 2° Percusso Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Km 2° Percusso TK Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold"> Km/L Sem Tk 2 Externo </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Local 2° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('NF 2° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Km 3° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Tk 3° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Litros 3° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Valor 3° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Km 3° Percusso Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Km 3° Percusso Tk Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold"> Km/L Sem Tk 3 Externo </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Local 3° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('NF 3° Abastecimento Externo','ISO-8859-1', 'UTF-8') .'</td>';
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
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Almoço','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold"> Passagem </td>';
        $html .= '<td class="text-center font-weight-bold"> Travessia </td>';
        $html .= '<td class="text-center font-weight-bold"> Outros Serviços </td>';
        $html .= '<td class="text-center font-weight-bold"> Ajudante </td>';
        $html .= '<td class="text-center font-weight-bold"> Chapa 01 </td>';
        $html .= '<td class="text-center font-weight-bold"> Chapa 02 </td>';
        $html .= '<td class="text-center font-weight-bold"> Status </td>';
        $html .= '<td class="text-center font-weight-bold"> Nota da Carga </td>';
        $html .= '<td class="text-center font-weight-bold"> Doca Congelado </td>';
        $html .= '<td class="text-center font-weight-bold"> Doca Seco </td>';
        $html .= '</tr>';
        
        $inicio = filter_input(INPUT_POST, 'inicio');
        $final = filter_input(INPUT_POST, 'final');

        $sql = $db->query("SELECT *, MONTH(data_chegada) as mes, YEAR(data_chegada) as ano FROM viagem WHERE DATE(data_chegada) BETWEEN '$inicio' and  '$final' $condicao ");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){
            $docas = explode('0', $dado['doca']);
            if(count($docas)<2){
                if($docas[0]<=10){
                    $docaCong = $docas[0];
                    $docaSeco = "";
                }else{
                    $docaSeco = $docas[0];
                    $docaCong = ""; 
                }
            }else{
                $docaCong = $docas[0];
                $docaSeco = $docas[1];
            }
            // echo "Congelado: $docaCong<br>";
            // echo "Seco: $docaSeco<hr>";
            $html .= '<tr>';
            $html .= '<td>' .$dado['filial'].  '</td>';
            $html .= '<td>' .$dado['cod_interno_veiculo'].  '</td>';
            $html .= '<td>' .$dado['tipo_veiculo'].  '</td>';
            $html .= '<td>' .$dado['placa_veiculo'].  '</td>';
            $html .= '<td>' .$dado['cod_interno_motorista'].  '</td>';
            $html .= '<td>' .mb_convert_encoding($dado['nome_motorista'],'ISO-8859-1', 'UTF-8').  '</td>';
            $html .= '<td>' .$dado['data_carregamento'].  '</td>';
            $html .= '<td>' .$dado['data_saida'].  '</td>';
            $html .= '<td>' .$dado['data_chegada'].  '</td>';
            $html .= '<td>' .utf8_encode(strftime('%B', strtotime($dado['data_chegada']))).  '</td>';
            $html .= '<td>' .$dado['ano'].  '</td>';
            $html .= '<td>' .$dado['dias_em_rota'].  '</td>';
            $html .= '<td>' .$dado['num_carregemento'].  '</td>';
            $html .= '<td>' .$dado['cod_rota'].  '</td>';
            $html .= '<td>' .mb_convert_encoding($dado['nome_rota'],'ISO-8859-1', 'UTF-8').  '</td>';
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
            $html .= '<td>' .mb_convert_encoding($dado['localAbast1'],'ISO-8859-1', 'UTF-8').  '</td>';
            $html .= '<td>' .$dado['nf1abast'].  '</td>';
            $html .= '<td>' .$dado['km_abast2'].  '</td>';
            $html .= '<td>' .$dado['hr_tk_abast2'].  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['lt_abast2'] ) .  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['valor_abast2']) .  '</td>';
            $html .= '<td>' .$dado['km_perc2'].  '</td>';
            $html .= '<td>' .$dado['km_pec2_tk_'].  '</td>';
            $html .= '<td>' .$dado['kmPorLtSemTk2'].  '</td>';
            $html .= '<td>' .mb_convert_encoding($dado['localAbast2'],'ISO-8859-1', 'UTF-8').  '</td>';
            $html .= '<td>' .$dado['nf2abast'].  '</td>';
            $html .= '<td>' .$dado['km_abast3'].  '</td>';
            $html .= '<td>' .$dado['hr_tk_abast3'].  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['lt_abast3'] ) .  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['valor_abast3']) .  '</td>';
            $html .= '<td>' .$dado['km_perc3'].  '</td>';
            $html .= '<td>' .$dado['km_pec3_tk'].  '</td>';
            $html .= '<td>' .$dado['kmPorLtSemTk3'].  '</td>';
            $html .= '<td>' .mb_convert_encoding($dado['localAbast3'],'ISO-8859-1', 'UTF-8').  '</td>';
            $html .= '<td>' .$dado['nf3abast'].  '</td>';
            $html .= '<td>' .$dado['km_abast4'].  '</td>';
            $html .= '<td>' .$dado['hr_tk_abast4'].  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['lt_abast4']) .  '</td>';
            $html .= '<td>' . str_replace(".", ",", $dado['valor_abast4']) .  '</td>';
            $html .= '<td>' .$dado['km_perc4'].  '</td>';
            $html .= '<td>' .$dado['km_perc4_tk'].  '</td>';
            $html .= '<td>' .$dado['kmPorLtSemTk4'].  '</td>';
            $html .= '<td>' .mb_convert_encoding($dado['localAbast4'],'ISO-8859-1', 'UTF-8').  '</td>';
            $html .= '<td>' .$dado['km_rodado'].  '</td>';
            $html .= '<td>' .$dado['km_final'].  '</td>';
            $html .= '<td>' . str_replace(".", ",", $dado['litros'] ).  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['mediaSemTk'] ) .  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['consumo_tk']) .  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['media_comtk']) .  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['valor_total_abast']) .  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['diarias_motoristas'] ) .  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['diarias_ajudante']) .  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['diarias_chapa']) .  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['dias_motorista']) .  '</td>';
            $html .= '<td>' . str_replace(".", ",", $dado['dias_ajudante']) .  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['dias_chapa']) .  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['outros_gastos_ajudante'] ) .  '</td>';
            $html .= '<td>' . str_replace(".", ",",$dado['tomada'] ) .  '</td>';
            $html .= '<td>' .str_replace(".", ",",$dado['descarga'] ).  '</td>';
            $html .= '<td>' .str_replace(".", ",",$dado['travessia'] ).  '</td>';
            $html .= '<td>' .str_replace(".", ",",$dado['outros_servicos'] ).  '</td>';
            $html .= '<td>' .mb_convert_encoding($dado['nome_ajudante'],'ISO-8859-1', 'UTF-8').  '</td>';
            $html .= '<td>' .mb_convert_encoding($dado['chapa01'],'ISO-8859-1', 'UTF-8').  '</td>';
            $html .= '<td>' .mb_convert_encoding($dado['chapa02'],'ISO-8859-1', 'UTF-8').  '</td>';
            $html .= '<td>' .mb_convert_encoding($dado['situacao'],'ISO-8859-1', 'UTF-8').  '</td>';
            $html .= '<td>' .$dado['nota_carga'].  '</td>';
            $html .= '<td>' . $docaCong .  '</td>';
            $html .= '<td>' . $docaSeco.  '</td>';
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