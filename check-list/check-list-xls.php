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
    <title>Veículos</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
    <?php
    
    if($tipoUsuario==1 || $tipoUsuario==99){

        $arquivo = 'check-list.xls';
        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> Código  </td>';
        $html .= '<td class="text-center font-weight-bold"> Placa </td>';
        $html .= '<td class="text-center font-weight-bold"> Data Check-List </td>';
        $html .= '<td class="text-center font-weight-bold"> Tipo Veículo </td>';
        $html .= '<td class="text-center font-weight-bold"> Km Inicial </td>';
        $html .= '<td class="text-center font-weight-bold"> Limpeza  </td>';
        $html .= '<td class="text-center font-weight-bold"> Retrovisores  </td>';
        $html .= '<td class="text-center font-weight-bold"> Para Brisa  </td>';
        $html .= '<td class="text-center font-weight-bold"> Quebra Sol  </td>';
        $html .= '<td class="text-center font-weight-bold"> Computador de Bordo  </td>';
        $html .= '<td class="text-center font-weight-bold"> Buzina  </td>';
        $html .= '<td class="text-center font-weight-bold"> Cinto  </td>';
        $html .= '<td class="text-center font-weight-bold"> Extintor  </td>';
        $html .= '<td class="text-center font-weight-bold"> Triângulo  </td>';
        $html .= '<td class="text-center font-weight-bold"> Macaco  </td>';
        $html .= '<td class="text-center font-weight-bold"> Tanque Combustível  </td>';
        $html .= '<td class="text-center font-weight-bold"> Janelas  </td>';
        $html .= '<td class="text-center font-weight-bold"> Setas  </td>';
        $html .= '<td class="text-center font-weight-bold"> Luz Freio  </td>';
        $html .= '<td class="text-center font-weight-bold"> Luz Ré  </td>';
        $html .= '<td class="text-center font-weight-bold"> Pisca Alerta  </td>';
        $html .= '<td class="text-center font-weight-bold"> Luzes Teto  </td>';
        $html .= '<td class="text-center font-weight-bold"> Faixas Reflexivas  </td>';
        $html .= '<td class="text-center font-weight-bold"> Farol Dianteiro  </td>';
        $html .= '<td class="text-center font-weight-bold"> Farol Traseiro  </td>';
        $html .= '<td class="text-center font-weight-bold"> Farol Neblina  </td>';
        $html .= '<td class="text-center font-weight-bold"> Farol Alto  </td>';
        $html .= '<td class="text-center font-weight-bold"> Luzes Painel  </td>';
        $html .= '<td class="text-center font-weight-bold"> Pneus  </td>';
        $html .= '<td class="text-center font-weight-bold"> Rodas  </td>';
        $html .= '<td class="text-center font-weight-bold"> Pneu Estepe  </td>';
        $html .= '<td class="text-center font-weight-bold"> Molas  </td>';
        $html .= '<td class="text-center font-weight-bold"> Cabo de Força  </td>';
        $html .= '<td class="text-center font-weight-bold"> Qtd NF  </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor Carga   </td>';
        $html .= '<td class="text-center font-weight-bold"> Data Saida  </td>';
        $html .= '<td class="text-center font-weight-bold"> Horímetro  </td>';
        $html .= '<td class="text-center font-weight-bold"> Rota  </td>';
        $html .= '<td class="text-center font-weight-bold"> Peso Carga  </td>';
        $html .= '<td class="text-center font-weight-bold"> Carregamento  </td>';
        $html .= '<td class="text-center font-weight-bold"> Motorista  </td>';
        $html .= '<td class="text-center font-weight-bold"> Observações  </td>';
        $html .= '<td class="text-center font-weight-bold"> Situação  </td>';
        $html .= '<td class="text-center font-weight-bold"> Responsável  </td>';
        $html .= '</tr>';

        $chechList = $db->query("SELECT * FROM check_list LEFT JOIN usuarios ON check_list.usuarios_idusuarios = usuarios.idusuarios");
        $dados = $chechList->fetchAll();
        foreach($dados as $dado){
            $html .= '<tr>';
            $html .= '<td>'.$dado['idcheck_list'] .'</td>';
            $html .= '<td>'. $dado['placa_veiculo'] .'</td>';
            $html .= '<td>'. date("d/m/Y", strtotime( $dado['data_check'])) .'</td>';
            $html .= '<td>'. $dado['tipo_veiculo'] .'</td>';
            $html .= '<td>'. $dado['km_inicial'] .'</td>';
            $html .= '<td>'. $dado['limpeza'] .'</td>';
            $html .= '<td>'. $dado['retrovisores'] .'</td>';
            $html .= '<td>'. $dado['para_brisa'] .'</td>';
            $html .= '<td>'. $dado['quebra_sol'] .'</td>';
            $html .= '<td>'. $dado['pc_bordo'] .'</td>';
            $html .= '<td>'. $dado['buzina'] .'</td>';
            $html .= '<td>'. $dado['cinto'] .'</td>';
            $html .= '<td>'. $dado['extintor'] .'</td>';
            $html .= '<td>'. $dado['triangulo'] .'</td>';
            $html .= '<td>'. $dado['macaco_chave'] .'</td>';
            $html .= '<td>'. $dado['tanque_combustivel'] .'</td>';
            $html .= '<td>'. $dado['janelas'] .'</td>';
            $html .= '<td>'. $dado['setas'] .'</td>';
            $html .= '<td>'. $dado['luz_freio'] .'</td>';
            $html .= '<td>'. $dado['luz_re'] .'</td>';
            $html .= '<td>'. $dado['pisca_alerta'] .'</td>';
            $html .= '<td>'. $dado['luzes_teto'] .'</td>';
            $html .= '<td>'. $dado['faixas_refletivas'] .'</td>';
            $html .= '<td>'. $dado['farol_dianteiro'] .'</td>';
            $html .= '<td>'. $dado['farol_traseiro'] .'</td>';
            $html .= '<td>'. $dado['farol_neblina'] .'</td>';
            $html .= '<td>'. $dado['farol_alto'] .'</td>';
            $html .= '<td>'. $dado['luzes_painel'] .'</td>';
            $html .= '<td>'. $dado['pneus'] .'</td>';
            $html .= '<td>'. $dado['rodas'] .'</td>';
            $html .= '<td>'. $dado['pneu_estepe'] .'</td>';
            $html .= '<td>'. $dado['molas'] .'</td>';
            $html .= '<td>'. $dado['cabo_forca'] .'</td>';
            $html .= '<td>'. $dado['qtde_nf'] .'</td>';
            $html .= '<td>'. $dado['valor_carga'] .'</td>';
            $html .= '<td>'. date("d/m/Y", strtotime($dado['data_saida']))  .'</td>';
            $html .= '<td>'. $dado['horimetro'] .'</td>';
            $html .= '<td>'. $dado['rota'] .'</td>';
            $html .= '<td>'. $dado['peso_carga'] .'</td>';
            $html .= '<td>'. $dado['num_carregemento'] .'</td>';
            $html .= '<td>'. $dado['motorista'] .'</td>';
            $html .= '<td>'. $dado['observacoes'] .'</td>';
            $html .= '<td>'. $dado['situacao'] .'</td>';
            $html .= '<td>'. $dado['nome_usuario'] .'</td>';
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