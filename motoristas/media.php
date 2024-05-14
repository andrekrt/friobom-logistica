<?php

session_start();
require("../conexao.php");

$idModudulo = 4;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];
    if($filial===99){
        $condicao = " ";
    }else{
        $condicao = "AND motoristas.filial=$filial";
    }
   
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

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/veiculo.png" alt="">
                </div>
                <div class="title">
                    <h2> Média de Combustível</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="filtro">
                    <form action="" class="form-inline" method="get">
                        <input type="date" name="min" class="form-control"> <span style="margin: 10px;">à</span>  <input type="date" name="max" class="form-control">
                        <select style="margin-left: 10px;" name="motorista" id="motorista" class="form-control">
                            <option value=""></option>
                            <?php
                            $motoristas = $db->query("SELECT * FROM motoristas WHERE 1 $condicao");
                            $motoristas = $motoristas->fetchAll();
                            foreach($motoristas as $motorista):
                            ?>
                            <option value="<?=$motorista['nome_motorista']?>"><?=$motorista['nome_motorista']?></option>
                            <?php endforeach; ?>
                            <input style="margin-left: 10px;" type="submit" name="filtro" value="Filtrar" class="btn btn-primary">
                            <input style="margin-left: 10px;" type="submit" formaction="media-xls.php" name="excel" value="Gerar Excel" class="btn btn-success">
                        </select>
                    </form>
                </div>
                <div class="table-responsive">
                    <table id='tableMot' class='table table-striped table-bordered nowrap text-center' >
                        <thead>
                            <tr>
                                <th scope="col" class="text-center"> Motorista </th>
                                <th scope="col" class="text-center"> Média Truck </th>
                                <th scope="col" class="text-center"> % Truck </th>
                                <th scope="col" class="text-center"> Média Toco </th>
                                <th scope="col" class="text-center"> % Toco </th>
                                <th scope="col" class="text-center"> Média 3/4 </th>
                                <th scope="col" class="text-center">% 3/4</th>
                                <th scope="col" class="text-center"> % Geral</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $quantidade = 10;
                        
                        $pagina = (isset($_GET['pagina']))?(int)$_GET['pagina']:1;

                        $pagIni = ($quantidade*$pagina)-$quantidade;

                        if(isset($_POST['filtro']) && (!empty($_POST['min']) || !empty($_POST['max']) || !empty($_POST['motorista'])) ){
                            $minimo = filter_input(INPUT_GET, 'min')?filter_input(INPUT_GET, 'min'):'2015-01-01';
                            $maximo = filter_input(INPUT_GET, 'max')?filter_input(INPUT_GET, 'max'):'2035-12-31';
                            $nomeMotorista = filter_input(INPUT_GET, 'motorista')?filter_input(INPUT_GET, 'motorista'):"%";

                        } else{
                            $minimo = filter_input(INPUT_GET, 'min')?filter_input(INPUT_GET, 'min'):'2015-01-01';
                            $maximo = filter_input(INPUT_GET, 'max')?filter_input(INPUT_GET, 'max'):'2035-12-31';
                            $nomeMotorista = filter_input(INPUT_GET, 'motorista')?filter_input(INPUT_GET, 'motorista'):"%";
                        }
                        
                        $nomes=$db->prepare("SELECT DISTINCT(nome_motorista) as motorista FROM viagem WHERE nome_motorista $condicao LIKE :motorista LIMIT :inicio, :qtd");
                        $nomes->bindValue(':motorista', $nomeMotorista);
                        $nomes->bindValue(':inicio', (int)$pagIni, PDO::PARAM_INT);
                        $nomes->bindValue(':qtd',(int)$quantidade, PDO::PARAM_INT);
                        $nomes->execute();
                        $nomes = $nomes->fetchAll();
                
                        foreach($nomes as $nome):
                            
                            $mediaTruck = $db->prepare("SELECT AVG(media_comtk) as mediaTruck FROM viagem LEFT JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE nome_motorista = :motorista AND categoria = :categoria AND data_chegada BETWEEN :inicio AND :final $condicao ");
                            $mediaTruck->bindValue(':motorista', $nome['motorista']);
                            $mediaTruck->bindValue(':categoria', "Truck");
                            $mediaTruck->bindValue(':inicio', $minimo);
                            $mediaTruck->bindValue(':final', $maximo);
                            $mediaTruck->execute();
                            $mediaTruck = $mediaTruck->fetch();
                            if($mediaTruck['mediaTruck']==null){
                                $percTruck = 0;
                            }else{
                                $percTruck = ($mediaTruck['mediaTruck']/3.5)*100;
                            }

                            $mercedinha = $db->prepare("SELECT AVG(media_comtk) as mediaMercedinha FROM viagem LEFT JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE nome_motorista = :motorista AND categoria = :categoria AND data_chegada BETWEEN :inicio AND :final $condicao ");
                            $mercedinha->bindValue(':motorista', $nome['motorista']);
                            $mercedinha->bindValue(':categoria', "Mercedinha");
                            $mercedinha->bindValue(':inicio', $minimo);
                            $mercedinha->bindValue(':final', $maximo);
                            $mercedinha->execute();
                            $mercedinha = $mercedinha->fetch();
                            if($mercedinha['mediaMercedinha']==null){
                                $percMercedinha = 0;
                            }else{
                                $percMercedinha = ($mercedinha['mediaMercedinha']/5.2)*100;
                            }

                            $toco = $db->prepare("SELECT AVG(media_comtk) as mediaToco FROM viagem LEFT JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE nome_motorista = :motorista AND categoria = :categoria AND data_chegada BETWEEN :inicio AND :final $condicao");
                            $toco->bindValue(':motorista', $nome['motorista']);
                            $toco->bindValue(':categoria', "Toco");
                            $toco->bindValue(':inicio', $minimo);
                            $toco->bindValue(':final', $maximo);
                            $toco->execute();
                            $toco = $toco->fetch();
                            if($toco['mediaToco']==null){
                                $percToco = 0;
                            }else{
                                $percToco = ($toco['mediaToco']/3.9)*100;
                            }
                          
                            if($percTruck>0 && $percMercedinha>0 && $percToco>0){
                                $mediaGeral = ($percTruck+$percMercedinha+$percToco)/3;
                            }elseif(($percTruck>0 && $percToco>0) || ($percTruck>0 && $percMercedinha>0) || ($percToco>0 && $percMercedinha>0)){
                                $mediaGeral = ($percTruck+$percMercedinha+$percToco)/2;
                            }elseif($percToco>0 || $percTruck>0 || $percMercedinha>0){
                                $mediaGeral = ($percTruck+$percMercedinha+$percToco)/1;
                            }else{
                                $mediaGeral = 0;
                            }
                        ?>
                        <tr>
                            <td><?=$nome['motorista']?></td>
                            <td><?=number_format($mediaTruck['mediaTruck'],2,",",".")."Km/l"?></td>
                            <td><?=number_format($percTruck,2,",",".")."%" ?></td>
                            <td><?=number_format($toco['mediaToco'],2,",",".")."Km/l"?></td>
                            <td><?=number_format($percToco,2,",",".")."%" ?></td>
                            <td><?=number_format($mercedinha['mediaMercedinha'],2,",",".")."Km/l"?></td>
                            <td><?=number_format($percMercedinha,2,",",".")."%" ?></td>
                            <td><?=number_format($mediaGeral,2,",",".")."%" ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <ul class="pagination justify-content-center">
                    <?php
                    $sqlTotal = $db->query("SELECT DISTINCT(nome_motorista) as motorista FROM viagem $condicao ");
                    $numtotal = $sqlTotal->rowCount();
                    $numPag = ceil($numtotal/$quantidade);
                    echo "<li class='page-item'> <span class='page-link'> Total de Registros: $numtotal </span></li>";
                    echo "<li class='page-item'> <a class='page-link' href='?pagina=1&min=$minimo&max=$maximo&motorista=$nomeMotorista'> Primeira Página </a></li>";

                    if($pagina>2):
                    ?>
                    <li class="page-item"> <a class="page-link" href="?pagina=<?=$pagina-1?>&min=<?=$minimo?>&max=<?=$maximo?>&motorista=<?=$nomeMotorista?>"> Anterior </a> </li>
                    <?php
                        
                    endif;

                    for($i=1;$i<=$numPag;$i++):
                        if($i>=($pagina-5) && $i<=($pagina+5)){
                            if($i==$pagina){
                                echo "<li class='page-item active'> <span class='page-link'>"  .$i. "</span></li>";
                            }else{
                                echo "<li class='page-item'> <a class='page-link' href=\"?pagina=$i&min=$minimo&max=$maximo&motorista=$nomeMotorista\">$i</a> </li>";
                            }
                        } 
                    endfor;

                    if($pagina<($numPag-1)):
                    ?>
                    <li class="page-item"> <a class="page-link" href="?pagina=<?=$pagina+1?>&min=<?=$minimo?>&max=<?=$maximo?>&motorista=<?$nomeMotorista?>"> Próximo </a> </li>
                    <?php
                            
                    endif;

                    echo "<li class='page-item'> <a class='page-link' href='?pagina=$numPag&min=$minimo&max=$maximo&motorista=$nomeMotorista'> Última Página </a> </li>";
                    ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/menu.js"></script>
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>