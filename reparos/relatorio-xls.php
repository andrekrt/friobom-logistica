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
        <title>Planilha</title>
    </head>
    <body>
        <?php
        
            if($tipoUsuario==4 || $tipoUsuario==3 || $tipoUsuario==99){

                $arquivo = 'pecas-solicitadas.xls';

                $html = '';
                $html .= '<table border="1">';
                $html .= '<tr>';
                $html .= '<td class="text-center font-weight-bold"> ID </td>';
                $html .= '<td class="text-center font-weight-bold"> Data </td>';
                $html .= '<td class="text-center font-weight-bold"> Serviço </td>';
                $html .= '<td class="text-center font-weight-bold"> Descrição </td>';
                $html .= '<td class="text-center font-weight-bold"> Placa Veículo </td>';
                $html .= '<td class="text-center font-weight-bold"> Solicitante </td>';
                $html .= '<td class="text-center font-weight-bold"> Situação </td>';
                $html .= '<td class="text-center font-weight-bold"> Obs. </td>';
                $html .= '<td class="text-center font-weight-bold"> Valor </td>';
                $html .= '<td class="text-center font-weight-bold"> Local </td>';
                $html .= '</tr>';

                $sql = $db->query("SELECT * FROM solicitacoes");
                $dados = $sql->fetchAll();
                foreach($dados as $dado){

                    $html .= '<tr>';
                    $html .= '<td>' .$dado['id'].  '</td>';
                    $html .= '<td>' .$dado['dataAtual'].  '</td>';
                    $html .= '<td>' .$dado['servico'].  '</td>';
                    $html .= '<td>' .$dado['descricao'].  '</td>';
                    $html .= '<td>' .$dado['placarVeiculo'].  '</td>';
                    $html .= '<td>' .$dado['idSolic'].  '</td>';
                    $html .= '<td>' .$dado['statusSolic'].  '</td>';
                    $html .= '<td>' .$dado['obs'].  '</td>';
                    $html .= '<td>' .$dado['valor'].  '</td>';
                    $html .= '<td>' .$dado['localReparo'].  '</td>';
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
    </body>
</html>