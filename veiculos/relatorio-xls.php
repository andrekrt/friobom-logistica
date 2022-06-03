<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if($_SESSION['tipoUsuario'] != 4 ){

        $arquivo = 'despesas-por-veiculo.xls';
        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> Placa </td>';
        $html .= '<td class="text-center font-weight-bold"> Tipo </td>';
        $html .= '<td class="text-center font-weight-bold"> Qtd Viagens </td>';
        $html .= '<td class="text-center font-weight-bold"> Km Rodado </td>';
        $html .= '<td class="text-center font-weight-bold"> Total Abastecido </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor Abastecido </td>';
        $html .= '<td class="text-center font-weight-bold"> Qtd Reparos </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor Reparos </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('R$/Km').' </td>';
        $html .= '</tr>';

        $veiculos = $db->query("SELECT tipo_veiculo, placa_veiculo FROM veiculos");
        $dados = $veiculos->fetchAll();
        foreach($dados as $dado){
            $html .= '<tr>';
            $html .= '<td>'.$dado['placa_veiculo'] .'</td>';
            $html .= '<td>'. utf8_decode($dado['tipo_veiculo']) .'</td>';

            $qtdVeiculos = $db->query("SELECT * FROM viagem WHERE placa_veiculo = '$dado[placa_veiculo]' ");
            $numVeiculo = $qtdVeiculos->rowCount();

            $html .= '<td>'. $numVeiculo .'</td>';

            $kmRodado = $db->query("SELECT SUM(km_rodado) as totalKmRodado FROM viagem WHERE placa_veiculo = '$dado[placa_veiculo]'");
            $totalKmRodado = $kmRodado->fetch();

            $html .= '<td>'. $totalKmRodado['totalKmRodado'] .'</td>';

            $sql = $db->query("SELECT placa_veiculo, SUM(litros) as totalLitros FROM viagem WHERE placa_veiculo = '$dado[placa_veiculo]' ");
            $result = $sql->fetch();

            $html .= '<td>'. number_format($result['totalLitros'],2, ",", ".") .'</td>';
            
            $sql = $db->query("SELECT placa_veiculo, SUM(valor_total_abast) as valorLitros FROM viagem WHERE placa_veiculo = '$dado[placa_veiculo]' ");
            $result = $sql->fetch();

            $html .= '<td>'. number_format($result['valorLitros'],2, ",", ".") .'</td>';

            $sql = $db->query("SELECT placarVeiculo FROM solicitacoes WHERE placarVeiculo = '$dado[placa_veiculo]' ");
            $result = $sql->rowCount();

            echo $result;
            $html .= '<td>'. $result .'</td>';

            $sql = $db->query("SELECT solicitacoes.placarVeiculo, SUM(solicitacoes.valor) + SUM(solicitacoes02.valor) as totalDespesas FROM solicitacoes LEFT JOIN solicitacoes02 ON solicitacoes.id = solicitacoes02.idSocPrinc WHERE solicitacoes.placarVeiculo = '$dado[placa_veiculo]' ");
            $result = $sql->fetch();

            $html .= '<td>'. number_format($result['totalDespesas'],2, ",", ".") .'</td>';

            $sql = $db->query("SELECT placa_veiculo, SUM(valor_total_abast) as valorLitros FROM viagem WHERE placa_veiculo = '$dado[placa_veiculo]' ");
            $result = $sql->fetch();
            $valorLitros = $result['valorLitros'];

            $sql = $db->query("SELECT SUM(km_rodado) as totalKmRodado FROM viagem WHERE placa_veiculo = '$dado[placa_veiculo]'");
            $result = $sql->fetch();
            $kmRodado = $result['totalKmRodado'];
            
            $sql = $db->query("SELECT solicitacoes.placarVeiculo, SUM(solicitacoes.valor) + SUM(solicitacoes02.valor) as totalDespesas FROM solicitacoes LEFT JOIN solicitacoes02 ON solicitacoes.id = solicitacoes02.idSocPrinc WHERE solicitacoes.placarVeiculo = '$dado[placa_veiculo]' ");
            $result = $sql->fetch();

            $despesas = $result['totalDespesas'];

            $mediaCusto= ($despesas+$valorLitros)/$kmRodado;

            $html .= '<td>'. number_format($mediaCusto, 2, ",", ".") .'</td>';

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