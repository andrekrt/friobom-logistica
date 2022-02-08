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
        
            if($tipoUsuario==4 || $tipoUsuario==99){

                $arquivo = 'colaboradores.xls';

                $html = '';
                $html .= '<table border="1">';
                $html .= '<tr>';
                $html .= '<td class="text-center font-weight-bold"> CPF </td>';
                $html .= '<td class="text-center font-weight-bold"> Nome </td>';
                $html .= '<td class="text-center font-weight-bold"> Salário </td>';
                $html .= '<td class="text-center font-weight-bold"> Extra </td>';
                $html .= '<td class="text-center font-weight-bold">Função </td>';
                $html .= '</tr>';

                $sql = $db->query("SELECT * FROM colaboradores WHERE ativo = 1 ORDER BY nome_colaborador");
                $dados = $sql->fetchAll();
                foreach($dados as $dado){

                    $html .= '<tr>';
                    $html .= '<td>'.$dado['cpf_colaborador']. '</td>';
                    $html .= '<td>'.$dado['nome_colaborador']. '</td>';
                    $html .= '<td>'. $dado['salario_colaborador'] . '</td>';
                    $html .= '<td>'. $dado['salario_extra'] . '</td>';
                    $html .= '<td>'. $dado['cargo_colaborador'] . '</td>';
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