<?php

session_start();
require("../../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

        $arquivo = 'rodizio.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Data do Rodízio').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Pneu </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Veículo Anterior').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Km Inicial Veículo Anterior').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Km Final Veículo Anterior').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Km Rodado Veículo Anterior').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Veículo Atual').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Km Inicial Veículo Atual').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Lançado por').' </td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM rodizio_pneu LEFT JOIN pneus ON rodizio_pneu.pneu = pneus.idpneus LEFT JOIN usuarios ON pneus.usuario = usuarios.idusuarios");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>' . date("d/m/Y", strtotime($dado['data_rodizio'])) .  '</td>';
            $html .= '<td>' .$dado['num_fogo'].  '</td>';
            $html .= '<td>' .$dado['veiculo_anterior'].  '</td>';
            $html .= '<td>' .$dado['km_inicial_veiculo_anterior'].  '</td>';
            $html .= '<td>' .$dado['km_final_veiculo_anterior'].  '</td>';
            $html .= '<td>' .$dado['km_rodado_veiculo_anterior'].  '</td>';
            $html .= '<td>' .$dado['novo_veiculo'].  '</td>';
            $html .= '<td>' .$dado['km_inicial_novo_veiculo'].  '</td>';
            $html .= '<td>' . utf8_decode($dado['nome_usuario']) .  '</td>';
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