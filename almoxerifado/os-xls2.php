<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

        $arquivo = 'os.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold">Data Abertura </td>';
        $html .= '<td class="text-center font-weight-bold"> Placa </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Descrição Problema').'  </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Tipo de Manutenção').'  </td>';
        $html .= '<td class="text-center font-weight-bold"> Corretiva</td>';
        $html .= '<td class="text-center font-weight-bold"> Preventiva</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Manutenção Externa').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Troca de Óleo ').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Higienização').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Agente Causador </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Nº Requisição').'  </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Nº Solicitação')  .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Nº NF').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Obs. </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Situação').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Data Encerramento </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Lançado por').' </td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM ordem_servico LEFT JOIN usuarios ON ordem_servico.idusuario = usuarios.idusuarios");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $corretiva = $dado['corretiva']?"SIM":"NÃO";
            $preventiva = $dado['preventiva']?"SIM":"NÃO";
            $externa = $dado['externa']?"SIM":"NÃO";
            $oleo = $dado['oleo']?"SIM":"NÃO";
            $higienizacao = $dado['higienizacao']?"SIM":"NÃO";

            $html .= '<tr>';
            $html .= '<td>'.$dado['idordem_servico']. '</td>';
            $html .= '<td>'.date("d/m/Y",strtotime($dado['data_abertura'])). '</td>';
            $html .= '<td>'.$dado['placa']. '</td>';
            $html .= '<td>'. utf8_decode($dado['descricao_problema']) . '</td>';
            $html .= '<td>'. utf8_decode($dado['tipo_manutencao']) . '</td>';
            $html .= '<td>'. utf8_decode($corretiva) . '</td>';
            $html .= '<td>'. utf8_decode($preventiva) . '</td>';
            $html .= '<td>'. utf8_decode($externa) . '</td>';
            $html .= '<td>'. utf8_decode($oleo) . '</td>';
            $html .= '<td>'. utf8_decode($higienizacao) . '</td>';
            $html .= '<td>'. utf8_decode($dado['causador'])  . '</td>';
            $html .= '<td>'. utf8_decode($dado['requisicao_saida'])  . '</td>';
            $html .= '<td>'. utf8_decode($dado['solicitacao_peca']) . '</td>';
            $html .= '<td>'. $dado['num_nf'] . '</td>';
            $html .= '<td>'. utf8_decode($dado['obs']) . '</td>';
            $html .= '<td>'. utf8_decode($dado['situacao']) . '</td>';
            $html .= '<td>'.date("d/m/Y",strtotime($dado['data_encerramento'])).  '</td>';
            $html .= '<td>'. utf8_decode($dado['nome_usuario']) . '</td>';
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