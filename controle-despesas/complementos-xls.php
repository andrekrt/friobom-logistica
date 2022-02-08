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
        
            if($_SESSION['tipoUsuario'] != 4){

                $arquivo = 'complementos.xls';

                $html = '';
                $html .= '<table border="1">';
                $html .= '<tr>';
                $html .= '<td class="text-center font-weight-bold"> ID </td>';
                $html .= '<td class="text-center font-weight-bold">Veículo</td>';
                $html .= '<td class="text-center font-weight-bold"> Motorista </td>';
                $html .= '<td class="text-center font-weight-bold"> Km Saída </td>';
                $html .= '<td class="text-center font-weight-bold"> Km Chegada </td>';
                $html .= '<td class="text-center font-weight-bold"> Litros Abast. </td>';
                $html .= '<td class="text-center font-weight-bold"> Valor Abast. </td>';
                $html .= '<td class="text-center font-weight-bold"> Usuário </td>';               
                $html .= '</tr>';

                $sql = $db->query("SELECT * FROM complementos_combustivel LEFT JOIN veiculos ON complementos_combustivel.veiculo = veiculos.cod_interno_veiculo LEFT JOIN motoristas ON complementos_combustivel.motorista = motoristas.cod_interno_motorista LEFT JOIN usuarios ON complementos_combustivel.id_usuario = usuarios.idusuarios");
                $dados = $sql->fetchAll();
                foreach($dados as $dado){

                    $html .= '<tr>';
                    $html .= '<td>'.$dado['id_complemento']. '</td>';
                    $html .= '<td>'.$dado['placa_veiculo']. '</td>';
                    $html .= '<td>'.$dado['nome_motorista']. '</td>';
                    $html .= '<td>'.$dado['km_saida']. '</td>';
                    $html .= '<td>'. $dado['km_chegada']. '</td>';
                    $html .= '<td>'. str_replace(".",",",$dado['litros_abast'])  . '</td>';
                    $html .= '<td>'. str_replace(".",",",$dado['valor_abast'])  . '</td>';
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