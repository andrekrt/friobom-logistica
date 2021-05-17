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
        
            if($tipoUsuario==1 || $tipoUsuario==99){

                $arquivo = 'saidas.xls';

                $html = '';
                $html .= '<table border="1">';
                $html .= '<tr>';
                $html .= '<td class="text-center font-weight-bold"> ID </td>';
                $html .= '<td class="text-center font-weight-bold">Data Saída</td>';
                $html .= '<td class="text-center font-weight-bold"> Qtd </td>';
                $html .= '<td class="text-center font-weight-bold"> Peça </td>';
                $html .= '<td class="text-center font-weight-bold"> Valor Médio </td>';
                $html .= '<td class="text-center font-weight-bold"> Solicitante </td>';
                $html .= '<td class="text-center font-weight-bold"> Placa </td>';
                $html .= '<td class="text-center font-weight-bold"> Observações </td>';
                $html .= '<td class="text-center font-weight-bold"> Saída </td>';
                
                $html .= '</tr>';

                $sql = $db->query("SELECT * FROM `saida_estoque` LEFT JOIN peca_estoque ON saida_estoque.peca_idpeca = peca_estoque.idpeca LEFT JOIN usuarios ON saida_estoque.id_usuario = usuarios.idusuarios");
                $dados = $sql->fetchAll();
                foreach($dados as $dado){

                    $valorMedio = $dado['valor_total']/$dado['total_estoque'];

                    $html .= '<tr>';
                    $html .= '<td>'.$dado['idsaida_estoque']. '</td>';
                    $html .= '<td>'.$dado['data_saida']. '</td>';
                    $html .= '<td>'.$dado['qtd']. '</td>';
                    $html .= '<td>'.$dado['descricao_peca']. '</td>';
                    $html .= '<td>'. $valorMedio. '</td>';
                    $html .= '<td>'. $dado['solicitante'] . '</td>';
                    $html .= '<td>'. $dado['placa'] . '</td>';
                    $html .= '<td>'. $dado['obs'] . '</td>';
                    $html .= '<td>'. $dado['nome_usuario'] . '</td>';
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