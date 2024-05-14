<?php

session_start();
require("../conexao.php");

$idModudulo = 4;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];
    if($filial===99){
        $condicao = " ";
    }else{
        $condicao = "AND viagem.filial=$filial";
    }
    $minimo =  filter_input(INPUT_GET, 'min')?filter_input(INPUT_GET, 'min'):'2015-01-01';
    $maximo = filter_input(INPUT_GET, 'max')?filter_input(INPUT_GET, 'max'):'2035-12-31';
    $motorista = filter_input(INPUT_GET, 'motorista')?filter_input(INPUT_GET, 'motorista'):"%";

    //echo "$dataInicio<br>$dataFim<br>$motorista";

    $arquivo = 'media-combustivel.xls';

    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td class="text-center font-weight-bold"> Motorista </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Média Truck')   .'</td>';
    $html .= '<td class="text-center font-weight-bold"> % Truck  </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Média Toco') .'</td>';
    $html .= '<td class="text-center font-weight-bold"> % Toco </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Média 3/4')  .'</td>';
    $html .= '<td class="text-center font-weight-bold">% 3/4 </td>';
    $html .= '<td class="text-center font-weight-bold">% Geral </td>';
    $html .= '</tr>';

    $nomes=$db->prepare("SELECT DISTINCT(nome_motorista) as motorista FROM viagem WHERE nome_motorista $condicao LIKE :motorista");
    $nomes->bindValue(':motorista', $motorista);
    $nomes->execute();
    $nomes = $nomes->fetchAll();

    foreach($nomes as $nome):
                        
        $mediaTruck = $db->prepare("SELECT AVG(media_comtk) as mediaTruck FROM viagem LEFT JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE nome_motorista = :motorista AND categoria = :categoria AND data_chegada BETWEEN :inicio AND :final $condicao ");
        $mediaTruck->bindValue(':motorista', $nome['motorista']);
        $mediaTruck->bindValue(':categoria', "Truck");
        $mediaTruck->bindValue(':inicio', $minimo);
        $mediaTruck->bindValue(':final', $maximo);
        $mediaTruck->execute();
        $mediaTruck = $mediaTruck->fetch();
        if($mediaTruck['mediaTruck']==null){
            $percTruck = 0;
        }else{
            $percTruck = ($mediaTruck['mediaTruck']/3.5)*100;
        }

        $mercedinha = $db->prepare("SELECT AVG(media_comtk) as mediaMercedinha FROM viagem LEFT JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE nome_motorista = :motorista AND categoria = :categoria AND data_chegada BETWEEN :inicio AND :final $condicao ");
        $mercedinha->bindValue(':motorista', $nome['motorista']);
        $mercedinha->bindValue(':categoria', "Mercedinha");
        $mercedinha->bindValue(':inicio', $minimo);
        $mercedinha->bindValue(':final', $maximo);
        $mercedinha->execute();
        $mercedinha = $mercedinha->fetch();
        if($mercedinha['mediaMercedinha']==null){
            $percMercedinha = 0;
        }else{
            $percMercedinha = ($mercedinha['mediaMercedinha']/5.2)*100;
        }

        $toco = $db->prepare("SELECT AVG(media_comtk) as mediaToco FROM viagem LEFT JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE nome_motorista = :motorista AND categoria = :categoria AND data_chegada BETWEEN :inicio AND :final $condicao ");
        $toco->bindValue(':motorista', $nome['motorista']);
        $toco->bindValue(':categoria', "Toco");
        $toco->bindValue(':inicio', $minimo);
        $toco->bindValue(':final', $maximo);
        $toco->execute();
        $toco = $toco->fetch();
        if($toco['mediaToco']==null){
            $percToco = 0;
        }else{
            $percToco = ($toco['mediaToco']/3.9)*100;
        }
        
        if($percTruck>0 && $percMercedinha>0 && $percToco>0){
            $mediaGeral = ($percTruck+$percMercedinha+$percToco)/3;
        }elseif(($percTruck>0 && $percToco>0) || ($percTruck>0 && $percMercedinha>0) || ($percToco>0 && $percMercedinha>0)){
            $mediaGeral = ($percTruck+$percMercedinha+$percToco)/2;
        }elseif($percToco>0 || $percTruck>0 || $percMercedinha>0){
            $mediaGeral = ($percTruck+$percMercedinha+$percToco)/1;
        }else{
            $mediaGeral = 0;
        }

        $html.='<tr>';
        $html.='<td>'.utf8_decode($nome['motorista']).'</td>';
        $html.='<td>'.number_format($mediaTruck['mediaTruck'],2,",",".").'</td>';
        $html.='<td>'.number_format($percTruck,2,",",".").'</td>';
        $html.='<td>'.number_format($toco['mediaToco'],2,",",".").'</td>';
        $html.='<td>'.number_format($percToco,2,",",".").'</td>';
        $html.='<td>'.number_format($mercedinha['mediaMercedinha'],2,",",".").'</td>';
        $html.='<td>'.number_format($percMercedinha,2,",",".").'</td>';
        $html.='<td>'.number_format($mediaGeral,2,",",".").'</td>';

    
    endforeach;

    $html .= '</table>';

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$arquivo.'"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');

    echo $html;

}   

?>