<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if($tipoUsuario==4 || $tipoUsuario==99){

        $arquivo = 'colaboradores.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> CPF </td>';
        $html .= '<td class="text-center font-weight-bold"> Nome </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Salário').'  </td>';
        $html .= '<td class="text-center font-weight-bold"> Extra </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Função ').' </td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM colaboradores WHERE ativo = 1 ORDER BY nome_colaborador");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>'.$dado['cpf_colaborador']. '</td>';
            $html .= '<td>'. utf8_decode($dado['nome_colaborador']) . '</td>';
            $html .= '<td>'. number_format($dado['salario_colaborador'],2,",",".")  . '</td>';
            $html .= '<td>'. number_format($dado['salario_extra'] ,2,",",".") . '</td>';
            $html .= '<td>'. utf8_decode($dado['cargo_colaborador'])  . '</td>';
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