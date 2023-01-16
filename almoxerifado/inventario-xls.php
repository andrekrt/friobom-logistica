<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

        $arquivo = 'inventario.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold">Data</td>';
        $html .= '<td class="text-center font-weight-bold">' . mb_convert_encoding('Peça','ISO-8859-1', 'UTF-8') . '</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Grupo', 'ISO-8859-1', 'UTF-8')  .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Medida','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Qtd', 'ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Lançado','ISO-8859-1', 'UTF-8'). '</td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM inventario_almoxarifado LEFT JOIN usuarios ON inventario_almoxarifado.usuario = usuarios.idusuarios LEFT JOIN peca_estoque ON inventario_almoxarifado.peca = peca_estoque.idpeca");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>'.$dado['idinventario']. '</td>';
            $html .= '<td>'. date("d/m/Y", strtotime($dado['data_inv'])). '</td>';
            $html .= '<td>'. $dado['idpeca']." - ". mb_convert_encoding($dado['descricao_peca'],'ISO-8859-1', 'UTF-8'). '</td>';
            $html .= '<td>'.mb_convert_encoding($dado['grupo_peca'],'ISO-8859-1', 'UTF-8'). '</td>';
            $html .= '<td>'. mb_convert_encoding($dado['un_medida'], 'ISO-8859-1', 'UTF-8') . '</td>';
            $html .= '<td>'. number_format($dado['qtd'],2,",",".")  . '</td>';
            $html .= '<td>'. mb_convert_encoding($dado['nome_usuario'],'ISO-8859-1', 'UTF-8')  . '</td>';
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