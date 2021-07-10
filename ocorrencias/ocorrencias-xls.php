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
        
            if($tipoUsuario==99){

                $arquivo = 'ocorrencias.xls';

                $html = '';
                $html .= '<table border="1">';
                $html .= '<tr>';
                $html .= '<td class="text-center font-weight-bold"> ID </td>';
                $html .= '<td class="text-center font-weight-bold">Motorista</td>';
                $html .= '<td class="text-center font-weight-bold"> Data Ocorrência </td>';
                $html .= '<td class="text-center font-weight-bold"> Tipo Ocorrência </td>';
                $html .= '<td class="text-center font-weight-bold"> Advertência </td>';
                $html .= '<td class="text-center font-weight-bold"> Laudo </td>';
                $html .= '<td class="text-center font-weight-bold"> Descrição </td>';
                $html .= '<td class="text-center font-weight-bold"> Valor Total </td>';
                $html .= '<td class="text-center font-weight-bold"> Situação </td>';
                
                $html .= '</tr>';

                $sql = $db->query("SELECT * FROM `ocorrencias`LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista LEFt JOIN usuarios ON ocorrencias.usuario_lancou = usuarios.idusuarios");
                $dados = $sql->fetchAll();
                foreach($dados as $dado){

                    $html .= '<tr>';
                    $html .= '<td>'.$dado['idocorrencia']. '</td>';
                    $html .= '<td>'.$dado['cod_interno_motorista']. '</td>';
                    $html .= '<td>'.$dado['data_ocorrencia']. '</td>';
                    $html .= '<td>'.$dado['tipo_ocorrencia']. '</td>';
                    $html .= '<td>'. $dado['advertencia']. '</td>';
                    $html .= '<td>'. $dado['laudo'] . '</td>';
                    $html .= '<td>'. $dado['descricao_custos'] . '</td>';
                    $html .= '<td>'. $dado['vl_total_custos'] . '</td>';
                    $html .= '<td>'. $dado['situacao'] . '</td>';
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