<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

        $arquivo = 'pneus.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> Data de Cadastro </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Nº Fogo').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Medida </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Calibragem Máxima').'  </td>';
        $html .= '<td class="text-center font-weight-bold"> Marca </td>';
        $html .= '<td class="text-center font-weight-bold"> Modelo </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Nº de Série').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Vida </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Posição Início').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Situação').'  </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Localização').'  </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Veículo Atual').'  </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Km Inicial Veículo').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Km Final Veículo').'</td>';
        $html .= '<td class="text-center font-weight-bold"> Km Rodado Pneu</td>';
        $html .= '<td class="text-center font-weight-bold"> Suco 01 </td>';
        $html .= '<td class="text-center font-weight-bold"> Suco 02 </td>';
        $html .= '<td class="text-center font-weight-bold"> Suco 03 </td>';
        $html .= '<td class="text-center font-weight-bold"> Suco 04 </td>';
        $html .= '<td class="text-center font-weight-bold"> Ativo </td>';
        $html .= '<td class="text-center font-weight-bold"> Cadastrado </td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM pneus LEFT JOIN usuarios ON pneus.usuario = usuarios.idusuarios");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>' . date("d/m/Y", strtotime($dado['data_cadastro'])) .  '</td>';
            $html .= '<td>' .$dado['num_fogo'].  '</td>';
            $html .= '<td>' .$dado['medida'].  '</td>';
            $html .= '<td>' .$dado['calibragem_maxima'].  '</td>';
            $html .= '<td>' . utf8_decode($dado['marca']) .  '</td>';
            $html .= '<td>' . utf8_decode($dado['modelo']) .  '</td>';
            $html .= '<td>' .$dado['num_serie'].  '</td>';
            $html .= '<td>' .$dado['vida'].  '</td>';
            $html .= '<td>' .$dado['posicao_inicio'].  '</td>';
            $html .= '<td>' . utf8_decode($dado['situacao']) .  '</td>';
            $html .= '<td>' .$dado['localizacao'].  '</td>';
            $html .= '<td>' .$dado['veiculo'].  '</td>';
            $html .= '<td>' .$dado['km_inicial'].  '</td>';
            $html .= '<td>' .$dado['km_final'].  '</td>';
            $html .= '<td>' .$dado['km_rodado'].  '</td>';
            $html .= '<td>' .$dado['suco01'].  '</td>';
            $html .= '<td>' .$dado['suco02'].  '</td>';
            $html .= '<td>' .$dado['suco03'].  '</td>';
            $html .= '<td>' .$dado['suco04'].  '</td>';
            $html .= '<td>' . utf8_decode($dado['uso']) .  '</td>';
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