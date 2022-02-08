<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Planilha</title>
      
    </head>
    </head>
    <body>
        <?php
        
            if($_SESSION['tipoUsuario'] != 4 ){

                $arquivo = 'alinhamentos.xls';
                $html = '';
                $html .= '<table border="1">';
                $html .= '<tr>';
                $html .= '<td class="text-center font-weight-bold"> Data Alinhamento </td>';
                $html .= '<td class="text-center font-weight-bold">Placa Veículo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km Alinhamento </td>';
                $html .= '<td class="text-center font-weight-bold"> Tipo Alinhamento </td>';
                $html .= '</tr>';

                $revisao = $db->query("SELECT * FROM alinhamentos_veiculo");
                $dados = $revisao->fetchAll();
                foreach($dados as $dado){
                    $html .= '<tr>';
                    $html .= '<td>'.$dado['data_alinhamento'] .'</td>';
                    $html .= '<td>'. $dado['placa_veiculo'] .'</td>';
                    $html .= '<td>'. $dado['km_alinhamento'] .'</td>';
                    $html .= '<td>'. $dado['tipo_alinhamento'] .'</td>';
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