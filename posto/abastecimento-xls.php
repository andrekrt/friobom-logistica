<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99)){

        $arquivo = 'saidas.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold">Data Abastecimento</td>';
        $html .= '<td class="text-center font-weight-bold"> Litros Abastecimento </td>';
        $html .= '<td class="text-center font-weight-bold"> R$/Lt </td>';
        $html .= '<td class="text-center font-weight-bold">Valor Total </td>';
        $html .= '<td class="text-center font-weight-bold">Carregamento</td>';
        $html .= '<td class="text-center font-weight-bold">Km</td>';
        $html .= '<td class="text-center font-weight-bold">Placa</td>';
        $html .= '<td class="text-center font-weight-bold">Rota</td>';
        $html .= '<td class="text-center font-weight-bold">Motorista</td>';
        $html .= '<td class="text-center font-weight-bold">Tipo de Abastecimento</td>';
        $html .= '<td class="text-center font-weight-bold">' .utf8_decode('Usuário que Lançou'). '</td>';
        $html .= '</tr>';        

        $sql = $db->query("SELECT * FROM combustivel_saida LEFT JOIN usuarios ON combustivel_saida.usuario = usuarios.idusuarios");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>'.$dado['idcombustivel_saida']. '</td>';
            $html .= '<td>'.date("d/m/Y", strtotime($dado['data_abastecimento'])). '</td>';
            $html .= '<td>'.number_format($dado['litro_abastecimento'],2,",","."). '</td>';
            $html .= '<td>'.number_format($dado['preco_medio'],2,",","."). '</td>';
            $html .= '<td>'.number_format($dado['valor_total'],2,",","."). '</td>';
            $html .= '<td>'.$dado['carregamento']. '</td>';
            $html .= '<td>'.$dado['km']. '</td>';
            $html .= '<td>'.$dado['placa_veiculo'] . '</td>';
            $html .= '<td>'.utf8_decode($dado['rota'])  . '</td>';
            $html .= '<td>'.utf8_decode($dado['motorista'])  . '</td>';
            $html .= '<td>'. utf8_decode($dado['tipo_abastecimento'])  . '</td>';
            $html .= '<td>'. utf8_decode($dado['nome_usuario'])  . '</td>';
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