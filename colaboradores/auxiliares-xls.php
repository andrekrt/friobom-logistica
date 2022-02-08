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
    <body>
        <?php
        
            if($_SESSION['tipoUsuario'] != 2 && $_SESSION['tipoUsuario'] != 3){

                $arquivo = 'auxiliares.xls';

                $html = '';
                $html .= '<table border="1">';
                $html .= '<tr>';
                $html .= '<td class="text-center font-weight-bold"> CPF </td>';
                $html .= '<td class="text-center font-weight-bold"> Nome </td>';
                $html .= '<td class="text-center font-weight-bold"> Salário </td>';
                $html .= '<td class="text-center font-weight-bold">Rota </td>';
                $html .= '</tr>';

                $sql = $db->query("SELECT * FROM auxiliares_rota LEFT JOIN rotas ON auxiliares_rota.rota = rotas.cod_rota WHERE ativo = 1 ORDER BY nome_auxiliar");
                $dados = $sql->fetchAll();
                foreach($dados as $dado){

                    $html .= '<tr>';
                    $html .= '<td>'.$dado['cpf_auxiliar']. '</td>';
                    $html .= '<td>'.$dado['nome_auxiliar']. '</td>';
                    $html .= '<td>'. $dado['salario_auxiliar'] . '</td>';
                    $html .= '<td>'. $dado['nome_rota'] . '</td>';
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