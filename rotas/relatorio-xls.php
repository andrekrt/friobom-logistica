<?php

session_start();
require("../conexao.php");

$idModudulo = 3;
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

        $arquivo = 'relatorio-rotas.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> Rota </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Categoria Veículo').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Qtd </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('R$/Entrega').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor Transportado </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor Denvolvido </td>';
        $html .= '<td class="text-center font-weight-bold"> Qtd de Entrega </td>';
        $html .= '<td class="text-center font-weight-bold"> Km Rodado </td>';
        $html .= '<td class="text-center font-weight-bold"> Litros </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor Abastecido </td>';
        $html .= '<td class="text-center font-weight-bold"> Km/L </td>';
        $html .= '<td class="text-center font-weight-bold"> Dias em Rota </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor Diaria Motorista </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor Diaria Ajudante </td>';
        $html .= '<td class="text-center font-weight-bold">Diarias Motoristas</td>';
        $html .= '<td class="text-center font-weight-bold"> Diarias Ajudante </td>';
        $html .= '<td class="text-center font-weight-bold"> Outros Gastos</td>';
        $html .= '<td class="text-center font-weight-bold"> Tomada </td>';
        $html .= '<td class="text-center font-weight-bold"> Descarga </td>';
        $html .= '<td class="text-center font-weight-bold"> Travessia </td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT nome_rota, veiculos.categoria as categoria, viagem.placa_veiculo,COUNT(nome_rota) as contagem, AVG(custo_entrega) as custoEntrega, SUM(valor_transportado) as mediaValorTransportado, SUM(valor_devolvido) as valorDevolvido, SUM(qtd_entregas) as entregas, SUM(peso_carga) as peso, SUM(km_rodado) as kmRodado, SUM(litros) as litros, SUM(valor_total_abast) as abastecimento, SUM(mediaSemTk) as mediaKm, SUM(dias_em_rota) AS diasEmRota, SUM(diarias_motoristas) as diariasMotoristas, SUM(diarias_ajudante) as diariasAjudante, SUM(dias_motorista) as diasMotoristas, SUM(dias_ajudante) as diasAjudante, SUM(outros_servicos) as outrosServicos, SUM(dias_em_rota) as diasEmRota, SUM(tomada) as tomada, SUM(descarga) as descarga, SUM(travessia) as travessia FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE 1 $condicao GROUP BY nome_rota, veiculos.categoria");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>'. utf8_decode($dado['nome_rota']) . '</td>';
            $html .= '<td>'. utf8_decode($dado['categoria']) . '</td>';
            $html .= '<td>'.$dado['contagem']. '</td>';
            $html .= '<td>'. number_format($dado['custoEntrega'], 2, ",", "." ) . '</td>';
            $html .= '<td>'. number_format($dado['mediaValorTransportado'], 2, ",", "." ) . '</td>';
            $html .= '<td>'.number_format($dado['valorDevolvido'],2, ",", ".") . '</td>';
            $html .= '<td>'.$dado['entregas']. '</td>';
            $html .= '<td>'.$dado['kmRodado']. '</td>';
            $html .= '<td>'.number_format($dado['litros'], 2, ",", ".") . '</td>';
            $html .= '<td>'.number_format($dado['abastecimento'],2,",",".") . '</td>';
            $html .= '<td>'. number_format($dado['mediaKm'],2,",",".") . '</td>';
            $html .= '<td>'.number_format($dado['diasEmRota'],2,",","."). '</td>';
            $html .= '<td>'.number_format($dado['diariasMotoristas'],2,",","."). '</td>';
            $html .= '<td>'.number_format($dado['diariasAjudante'],2,",","."). '</td>';
            $html .= '<td>'.number_format($dado['diasMotoristas'],2,",","."). '</td>';
            $html .= '<td>'.number_format($dado['diasAjudante'],2,",","."). '</td>';
            $html .= '<td>'.number_format($dado['outrosServicos'],2,",","."). '</td>';
            $html .= '<td>'.number_format($dado['tomada'],2,",","."). '</td>';
            $html .= '<td>'.number_format($dado['descarga'],2,",","."). '</td>';
            $html .= '<td>'.number_format($dado['travessia'],2,",","."). '</td>';
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