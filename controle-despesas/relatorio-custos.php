<?php

use Mpdf\Tag\Td;

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 4 ) {

   
} else {
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRIOBOM - TRANSPORTE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- arquivos para datatable -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.css"/>

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/despesas.png" alt="">
                </div>
                <div class="title">
                    <h2> Relatório de Custos</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <a href="relatorio-custos-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableRota' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                            <?php
                                $mesAtual = date('Y-m'); 
                                for($i=0;$i<12; $i++): 
                                    $mesAtual = date('Y-m', strtotime('-1 months', strtotime(date($mesAtual))));
                            ?>
                                <th scope="col" class="text-center text-nowrap" > <?=date('m/Y', strtotime($mesAtual))?> </th>
                            <?php endfor; ?>
                            </tr>
                        </thead>
                        <tr>
                            <td scope="col" class="text-center text-nowrap font-weight-bold" > Diarias Motoristas</td>
                        <?php 
                            $sqlCustos = $db->query("SELECT month(data_chegada) as mes, year(data_chegada) as ano,SUM(dias_motorista*diarias_motoristas) as diariasMotorista, SUM(dias_ajudante*diarias_ajudante) as diariasAjudante, SUM(dias_chapa*diarias_chapa) as diariasChapa, (SUM(outros_gastos_ajudante)+SUM(outros_servicos)+SUM(tomada)+SUM(descarga)+SUM(travessia)) as outrosGastos, SUM(valor_total_abast) as abastecimento, SUM(valor_transportado) as faturado FROM `viagem` GROUP BY month(data_chegada), year(data_chegada) ORDER BY year(data_chegada) DESC, month(data_chegada) DESC LIMIT 1,12");
                            $custos = $sqlCustos->fetchAll();
                            foreach($custos as $custo):
                        ?>
                            <td scope="col" class="text-center text-nowrap" > <?=number_format($custo['diariasMotorista'], 2, ",", ".") ?> </td>
                        <?php $motoristas[] =$custo['diariasMotorista']; 
                        $faturado[] = $custo['faturado'];
                        endforeach; ?>
                        </tr>
                        <tr>
                            <td scope="col" class="text-center text-nowrap font-weight-bold" > Diarias Ajudantes</td>
                        <?php foreach($custos as $custo): ?>
                            <td scope="col" class="text-center text-nowrap" > <?= number_format($custo['diariasAjudante'], "2",",",".") ?> </td>
                            
                        <?php $ajudantes[] = $custo['diariasAjudante']; endforeach; ?>
                        </tr>
                        <tr>
                            <td scope="col" class="text-center text-nowrap font-weight-bold" > Diarias Chapas</td>
                        <?php foreach($custos as $custo): ?>
                            <td scope="col" class="text-center text-nowrap" > <?= number_format($custo['diariasChapa'], "2",",",".") ?> </td>
                        <?php $chapas[] = $custo['diariasChapa']; endforeach; ?>
                        </tr>
                        <tr>
                            <td scope="col" class="text-center text-nowrap font-weight-bold" > Outras Despesas</td>
                        <?php foreach($custos as $custo): ?>
                            <td scope="col" class="text-center text-nowrap" > <?= number_format($custo['outrosGastos'], "2",",",".") ?> </td>
                        <?php $outros[] =$custo['outrosGastos'];  endforeach; ?>
                        </tr>
                        <tr>
                            <td scope="col" class="text-center text-nowrap font-weight-bold" > Abastecimento</td>
                        <?php foreach($custos as $custo): ?>
                            <td scope="col" class="text-center text-nowrap" > <?= number_format($custo['abastecimento'], "2",",",".") ?> </td>
                        <?php $abastecimento[]=$custo['abastecimento']; endforeach; ?>    
                        </tr>
                        <tr>
                            <td scope="col" class="text-center text-nowrap font-weight-bold" > Manutenção</td>
                        <?php 
                        $sqlManutecoes = $db->query("SELECT month(data_aprovacao) as mes, year(data_aprovacao) as ano, SUM(vl_total+frete) as valorTotal FROM `solicitacoes_new` GROUP BY month(data_aprovacao), year(data_aprovacao) ORDER BY year(data_aprovacao) DESC, month(data_aprovacao) DESC LIMIT 1,12");
                        $manutecoes = $sqlManutecoes->fetchAll();
                        foreach($manutecoes as $manutecao):
                        ?>
                            <td scope="col" class="text-center text-nowrap" > <?= number_format($manutecao['valorTotal'], "2",",",".") ?> </td>
                        <?php $valoreManutencao[] = $manutecao['valorTotal']; endforeach; ?>
                        </tr>
                        <tr>
                            <td scope="col" class="text-center text-nowrap font-weight-bold" > Salário Motoristas</td>
                        <?php
                        $SalarioMot = $db->query("SELECT SUM(salario) as salarioMot FROM motoristas WHERE ativo =1");
                        $SalarioMot = $SalarioMot->fetch();
                        for($i=0;$i<12;$i++):
                            echo "<td>". number_format($SalarioMot['salarioMot'],2,",",".")  ."</td>";
                        endfor;
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td scope='col' class='text-center text-nowrap font-weight-bold'> Salário Ajudantes </td>";
                        $SalarioAjud = $db->query("SELECT SUM(salario_auxiliar) as salarioAjud FROM auxiliares_rota WHERE ativo =1");
                        $SalarioAjud=$SalarioAjud->fetch();
                        for($i=0;$i<12;$i++):
                            echo "<td>". number_format($SalarioAjud['salarioAjud'],2,",",".")  ."</td>";
                        endfor;
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td scope='col' class='text-center text-nowrap font-weight-bold'> Salário Internos </td>"; 
                        $salarioInt = $db->query("SELECT SUM(salario_colaborador+salario_extra) as salarioInt FROM colaboradores WHERE ativo =1");
                        $salarioInt = $salarioInt->fetch(); 
                        for($i=0;$i<12;$i++):
                            echo "<td>". number_format($salarioInt['salarioInt'],2,",",".")  ."</td>";
                        endfor;
                        echo "</tr>";
                        ?>
                        
                        <tr>
                            <td scope="col" class="text-center text-nowrap font-weight-bold" > TOTAL</td>
                        <?php 
                        for($i=0; $i<12; $i++):
                            $total = $motoristas[$i]+$ajudantes[$i]+$chapas[$i]+$outros[$i]+$abastecimento[$i]+$valoreManutencao[$i]+$SalarioMot['salarioMot']+$SalarioAjud['salarioAjud']+$salarioInt['salarioInt'];
                            echo "<td>". number_format($total,2,",",".")  . "</td>";
                            $valorTotal[] = $total;
                        endfor;
                      
                        ?>    
                        </tr>
                        <tr> 
                            <td  scope="col" class="text-center text-nowrap font-weight-bold">Faturamento</td> 
                            <?php 
                            for($i=0; $i<12; $i++):
                                echo "<td>". number_format($faturado[$i],2,",",".") . "</td>";
                            endfor;
                            ?>
                        </tr>
                        <tr>
                            <td scope="col" class="text-center text-nowrap font-weight-bold" > Custo %</td>
                        <?php 
                        for($i=0; $i<12; $i++):
                            $perCusto = $valorTotal[$i]/$faturado[$i];
                            echo "<td>". number_format($perCusto*100,2,",",".") ."%"  . "</td>";
                        endfor;
                        ?>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/menu.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>