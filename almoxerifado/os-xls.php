<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Peças</title>
    </head>
    <body>
        <?php
        
            if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

                $arquivo = 'os.xls';

                $html = '';
                $html .= '<table border="1">';
                $html .= '<tr>';
                $html .= '<td class="text-center font-weight-bold"> ID </td>';
                $html .= '<td class="text-center font-weight-bold">Data Abertura </td>';
                $html .= '<td class="text-center font-weight-bold"> Placa </td>';
                $html .= '<td class="text-center font-weight-bold"> Descrição Problema </td>';
                $html .= '<td class="text-center font-weight-bold"> Tipo Manutenção </td>';
                $html .= '<td class="text-center font-weight-bold"> Agente Causador </td>';
                $html .= '<td class="text-center font-weight-bold"> Nº Requisição </td>';
                $html .= '<td class="text-center font-weight-bold"> Nº Solicitação </td>';
                $html .= '<td class="text-center font-weight-bold"> Nº NF</td>';
                $html .= '<td class="text-center font-weight-bold"> Obs. </td>';
                $html .= '<td class="text-center font-weight-bold"> Situação </td>';
                $html .= '<td class="text-center font-weight-bold"> Data Encerramento </td>';
                $html .= '<td class="text-center font-weight-bold">Lançado por</td>';
                $html .= '</tr>';

                $sql = $db->query("SELECT * FROM ordem_servico LEFT JOIN usuarios ON ordem_servico.idusuario = usuarios.idusuarios");
                $dados = $sql->fetchAll();
                foreach($dados as $dado){

                    $html .= '<tr>';
                    $html .= '<td>'.$dado['idordem_servico']. '</td>';
                    $html .= '<td>'.date("d/m/Y",strtotime($dado['data_abertura'])). '</td>';
                    $html .= '<td>'.$dado['placa']. '</td>';
                    $html .= '<td>'.$dado['descricao_problema']. '</td>';
                    $html .= '<td>'. $dado['tipo_manutencao']. '</td>';
                    $html .= '<td>'. $dado['causador'] . '</td>';
                    $html .= '<td>'. $dado['requisicao_saida'] . '</td>';
                    $html .= '<td>'. $dado['solicitacao_peca']. '</td>';
                    $html .= '<td>'. $dado['num_nf'] . '</td>';
                    $html .= '<td>'.$dado['obs']. '</td>';
                    $html .= '<td>'.$dado['situacao']. '</td>';
                    $html .= '<td>'.date("d/m/Y",strtotime($dado['data_encerramento'])).  '</td>';
                    $html .= '<td>'.$dado['nome_usuario']. '</td>';
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
    </body>
</html>