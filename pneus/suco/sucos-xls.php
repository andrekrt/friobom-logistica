<?php

session_start();
require("../../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

        $arquivo = 'suco.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Data de Cadastro','ISO-8859-1', 'UTF-8')  .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Data de Medição','ISO-8859-1', 'UTF-8')  .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Nº Fogo','ISO-8859-1', 'UTF-8').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Medida </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Calibragem Máxima','ISO-8859-1', 'UTF-8').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Marca </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Nº Série','ISO-8859-1', 'UTF-8').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Vida </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Veículo','ISO-8859-1', 'UTF-8').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Km Veículo','ISO-8859-1', 'UTF-8').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Km Pneu','ISO-8859-1', 'UTF-8').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Carcaça','ISO-8859-1', 'UTF-8').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Posição','ISO-8859-1', 'UTF-8').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Suco 01 de Cadastro </td>';
        $html .= '<td class="text-center font-weight-bold"> Suco 01 de Medida </td>';
        $html .= '<td class="text-center font-weight-bold"> Suco 02 de Cadastro</td>';
        $html .= '<td class="text-center font-weight-bold"> Suco 02 de Medida </td>';
        $html .= '<td class="text-center font-weight-bold"> Suco 03 de Cadastro </td>';
        $html .= '<td class="text-center font-weight-bold"> Suco 03 de Medida </td>';
        $html .= '<td class="text-center font-weight-bold"> Suco 04 de Cadastro</td>';
        $html .= '<td class="text-center font-weight-bold"> Suco 04 de Medida </td>';
        $html .= '<td class="text-center font-weight-bold"> Calibragem Encontrada </td>';
        $html .= '<td class="text-center font-weight-bold"> Cadastrado </td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT data_cadastro, data_medicao, num_fogo, medida, calibragem_maxima, marca, num_serie, sucos.vida, veiculo, km_veiculo, km_pneu, carcaca, posicao_inicio, sucos.suco01 as medida01, sucos.suco02 as medida02, sucos.suco03 as medida03, sucos.suco04 as medida04 , pneus.suco01 as cadastro01, pneus.suco02 as cadastro02, pneus.suco03 as cadastro03, pneus.suco04 as cadastro04,  calibragem, nome_usuario FROM sucos LEFT JOIN pneus ON sucos.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON sucos.usuario = usuarios.idusuarios");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>' . date("d/m/Y", strtotime($dado['data_cadastro'])) .  '</td>';
            $html .= '<td>' . date("d/m/Y", strtotime($dado['data_medicao'])) .  '</td>';
            $html .= '<td>' .$dado['num_fogo'].  '</td>';
            $html .= '<td>' .$dado['medida'].  '</td>';
            $html .= '<td>' .$dado['calibragem_maxima'].  '</td>';
            $html .= '<td>' .$dado['marca'].  '</td>';
            $html .= '<td>' .$dado['num_serie'].  '</td>';
            $html .= '<td>' .$dado['vida'].  '</td>';
            $html .= '<td>' .$dado['veiculo'].  '</td>';
            $html .= '<td>' .$dado['km_veiculo'].  '</td>';
            $html .= '<td>' .$dado['km_pneu'].  '</td>';
            $html .= '<td>' .$dado['carcaca'].  '</td>';
            $html .= '<td>' .$dado['posicao_inicio'].  '</td>';
            $html .= '<td>' .$dado['cadastro01'].  '</td>';
            $html .= '<td>' .$dado['medida01'].  '</td>';
            $html .= '<td>' .$dado['cadastro02'].  '</td>';
            $html .= '<td>' .$dado['medida02'].  '</td>';
            $html .= '<td>' .$dado['cadastro03'].  '</td>';
            $html .= '<td>' .$dado['medida03'].  '</td>';
            $html .= '<td>' .$dado['cadastro04'].  '</td>';
            $html .= '<td>' .$dado['medida04'].  '</td>';
            $html .= '<td>' .$dado['calibragem'].  '</td>';
            $html .= '<td>' . mb_convert_encoding($dado['nome_usuario'],'ISO-8859-1', 'UTF-8') .  '</td>';
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