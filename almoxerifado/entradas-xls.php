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

                $arquivo = 'entradas.xls';

                $html = '';
                $html .= '<table border="1">';
                $html .= '<tr>';
                $html .= '<td class="text-center font-weight-bold"> ID </td>';
                $html .= '<td class="text-center font-weight-bold">Data NF</td>';
                $html .= '<td class="text-center font-weight-bold"> Nº NF </td>';
                $html .= '<td class="text-center font-weight-bold"> Nº Pedido  </td>';
                $html .= '<td class="text-center font-weight-bold"> Peça </td>';
                $html .= '<td class="text-center font-weight-bold"> Preço </td>';
                $html .= '<td class="text-center font-weight-bold"> Quantidade </td>';
                $html .= '<td class="text-center font-weight-bold"> Desconto </td>';
                $html .= '<td class="text-center font-weight-bold"> Observações </td>';
                $html .= '<td class="text-center font-weight-bold"> Fornecedor </td>';
                $html .= '<td class="text-center font-weight-bold"> Valor Total </td>';
                $html .= '</tr>';

                $sql = $db->query("SELECT * FROM `entrada_estoque` LEFT JOIN peca_estoque ON entrada_estoque.peca_idpeca = peca_estoque.idpeca LEFT JOIN usuarios ON entrada_estoque.id_usuario = usuarios.idusuarios LEFT JOIN fornecedores ON entrada_estoque.fornecedor = fornecedores.id");
                $dados = $sql->fetchAll();
                foreach($dados as $dado){

                    $html .= '<tr>';
                    $html .= '<td>'.$dado['identrada_estoque']. '</td>';
                    $html .= '<td>'.$dado['data_nf']. '</td>';
                    $html .= '<td>'.$dado['num_nf']. '</td>';
                    $html .= '<td>'.$dado['num_pedido']. '</td>';
                    $html .= '<td>'. $dado['descricao_peca']. '</td>';
                    $html .= '<td>'. $dado['preco_custo'] . '</td>';
                    $html .= '<td>'. $dado['qtd'] . '</td>';
                    $html .= '<td>'. $dado['desconto'] . '</td>';
                    $html .= '<td>'. $dado['obs'] . '</td>';
                    $html .= '<td>'. $dado['apelido'] . '</td>';
                    $html .= '<td>'. $dado['vl_total_comprado'] . '</td>';
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