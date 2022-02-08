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
        
            if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

                $arquivo = 'suco.xls';

                $html = '';
                $html .= '<table border="1">';
                $html .= '<tr>';
                $html .= '<td class="text-center font-weight-bold"> Data de Medição </td>';
                $html .= '<td class="text-center font-weight-bold"> Nº Fogo </td>';
                $html .= '<td class="text-center font-weight-bold"> Medida </td>';
                $html .= '<td class="text-center font-weight-bold"> Calibragem Máxima </td>';
                $html .= '<td class="text-center font-weight-bold"> Marca </td>';
                $html .= '<td class="text-center font-weight-bold"> Nº Série </td>';
                $html .= '<td class="text-center font-weight-bold"> Vida </td>';
                $html .= '<td class="text-center font-weight-bold"> Veículo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km Veículo </td>';
                $html .= '<td class="text-center font-weight-bold"> Km Pneu </td>';
                $html .= '<td class="text-center font-weight-bold"> Carcaça </td>';
                $html .= '<td class="text-center font-weight-bold"> Posição </td>';
                $html .= '<td class="text-center font-weight-bold"> Suco 01 </td>';
                $html .= '<td class="text-center font-weight-bold"> Suco 02 </td>';
                $html .= '<td class="text-center font-weight-bold"> Suco 03 </td>';
                $html .= '<td class="text-center font-weight-bold"> Suco 04 </td>';
                $html .= '<td class="text-center font-weight-bold"> Calibragem Encontrada </td>';
                $html .= '<td class="text-center font-weight-bold"> Cadastrado </td>';
                $html .= '</tr>';

                $sql = $db->query("SELECT * FROM sucos LEFT JOIN pneus ON sucos.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON pneus.usuario = usuarios.idusuarios");
                $dados = $sql->fetchAll();
                foreach($dados as $dado){

                    $html .= '<tr>';
                    $html .= '<td>' . date("d/m/Y", strtotime($dado['data_medicao'])) .  '</td>';
                    $html .= '<td>' .$dado['num_fogo'].  '</td>';
                    $html .= '<td>' .$dado['medida'].  '</td>';
                    $html .= '<td>' .$dado['calibragem_maxima'].  '</td>';
                    $html .= '<td>' .$dado['marca'].  '</td>';
                    $html .= '<td>' .$dado['num_serie'].  '</td>';
                    $html .= '<td>' .$dado['vida'].  '</td>';
                    $html .= '<td>' .$dado['veiculo'].  '</td>';
                    $html .= '<td>' .$dado['km_veiculo'].  '</td>';
                    $html .= '<td>' .$dado['km_pneu'].  '</td>';
                    $html .= '<td>' .$dado['carcaca'].  '</td>';
                    $html .= '<td>' .$dado['posicao_inicio'].  '</td>';
                    $html .= '<td>' .$dado['suco01'].  '</td>';
                    $html .= '<td>' .$dado['suco02'].  '</td>';
                    $html .= '<td>' .$dado['suco03'].  '</td>';
                    $html .= '<td>' .$dado['suco04'].  '</td>';
                    $html .= '<td>' .$dado['calibragem'].  '</td>';
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