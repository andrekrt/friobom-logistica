<?php

session_start();
require("../../conexao.php");

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

                $arquivo = 'manutencoes.xls';

                $html = '';
                $html .= '<table border="1">';
                $html .= '<tr>';
                $html .= '<td class="text-center font-weight-bold"> Data do Reparo </td>';
                $html .= '<td class="text-center font-weight-bold"> Tipo de Reparo </td>';
                $html .= '<td class="text-center font-weight-bold"> Nº Fogo </td>';
                $html .= '<td class="text-center font-weight-bold"> Medida </td>';
                $html .= '<td class="text-center font-weight-bold"> Marca </td>';
                $html .= '<td class="text-center font-weight-bold"> Modelo </td>';
                $html .= '<td class="text-center font-weight-bold"> Vida </td>';
                $html .= '<td class="text-center font-weight-bold"> Km Veículo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km Pneu </td>';
                $html .= '<td class="text-center font-weight-bold"> Valor </td>';
                $html .= '<td class="text-center font-weight-bold">Nº NF </td>';
                $html .= '<td class="text-center font-weight-bold"> Fornecedor </td>';
                $html .= '<td class="text-center font-weight-bold"> Suco 01 </td>';
                $html .= '<td class="text-center font-weight-bold"> Suco 02 </td>';
                $html .= '<td class="text-center font-weight-bold"> Suco 03 </td>';
                $html .= '<td class="text-center font-weight-bold"> Suco 04 </td>';
                $html .= '<td class="text-center font-weight-bold"> Registrado </td>';
                $html .= '</tr>';

                $sql = $db->query("SELECT * FROM manutencao_pneu LEFT JOIN pneus ON manutencao_pneu.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON manutencao_pneu.usuario = usuarios.idusuarios");
                $dados = $sql->fetchAll();
                foreach($dados as $dado){

                    $html .= '<tr>';
                    $html .= '<td>' . date("d/m/Y", strtotime($dado['data_manutencao'])) .  '</td>';
                    $html .= '<td>' .$dado['tipo_manutencao'].  '</td>';
                    $html .= '<td>' .$dado['num_fogo'].  '</td>';
                    $html .= '<td>' .$dado['medida'].  '</td>';
                    $html .= '<td>' .$dado['marca'].  '</td>';
                    $html .= '<td>' .$dado['modelo'].  '</td>';
                    $html .= '<td>' .$dado['vida'].  '</td>';
                    $html .= '<td>' .$dado['km_veiculo'].  '</td>';
                    $html .= '<td>' .$dado['km_pneu'].  '</td>';
                    $html .= '<td>' .str_replace(".",",",$dado['valor']) .  '</td>';
                    $html .= '<td>' .$dado['num_nf'].  '</td>';
                    $html .= '<td>' .$dado['fornecedor'].  '</td>';
                    $html .= '<td>' .$dado['suco01'].  '</td>';
                    $html .= '<td>' .$dado['suco02'].  '</td>';
                    $html .= '<td>' .$dado['suco03'].  '</td>';
                    $html .= '<td>' .$dado['suco04'].  '</td>';
                    $html .= '<td>' .$dado['nome_usuario'].  '</td>';
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