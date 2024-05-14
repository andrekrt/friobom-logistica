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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                            $motoristas = $db->query("SELECT * FROM motoristas WHERE ativo = 1 $condicao ORDER BY nome_motorista");
                            $motoristas = $motoristas->fetchAll();
                            foreach($motoristas as $motorista):
                            ?>
                            <option value="<?=$motorista['nome_motorista']?>"><?=$motorista['nome_motorista']?></option>
                            <?php endforeach; ?>
                            <input style="margin-left: 10px;" type="submit" name="filtro" value="Filtrar" class="btn btn-primary">
                            <input style="margin-left: 10px;" type="submit" formaction="consumo-xls.php" name="excel" value="Gerar Excel" class="btn btn-success">
                        </select>
                    </form>
                </div>
                <div class="table-responsive">
                    <table id='tableMot' class='table table-striped table-bordered nowrap text-center' >
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap"> Motorista </th>
                                <th scope="col" class="text-center text-nowrap">Placa </th>
                                <th scope="col" class="text-center text-nowrap"> Categoria Veículo </th>
                                <th scope="col" class="text-center text-nowrap"> Rota </th>
                                <th scope="col" class="text-center text-nowrap"> Saída </th>
                                <th scope="col" class="text-center text-nowrap"> Chegada </th>
                                <th scope="col" class="text-center text-nowrap">Média Alcançada</th>
                                <th scope="col" class="text-center text-nowrap"> Meta Média</th>
                                <th scope="col" class="text-center text-nowrap"> % Atingido</th>
                                <th scope="col" class="text-center text-nowrap"> Qtd Viagem </th>
                                <th scope="col" class="text-center text-nowrap"> Valor Ganho </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $quantidade = 10;
                        
                        $pagina = (isset($_GET['pagina']))?(int)$_GET['pagina']:1;

                        $pagIni = ($quantidade*$pagina)-$quantidade;

                        $primeiroDia = date("Y-m-01", strtotime('-1 months', strtotime(date('Y-m-d'))));
                        $ultimodia = date("Y-m-t",strtotime('-1 months', strtotime(date('Y-m-d'))));

                        if(isset($_POST['filtro']) && (!empty($_POST['min']) || !empty($_POST['max']) || !empty($_POST['motorista'])) ){
                            $minimo = filter_input(INPUT_GET, 'min')?filter_input(INPUT_GET, 'min'):$primeiroDia;
                            $maximo = filter_input(INPUT_GET, 'max')?filter_input(INPUT_GET, 'max'):$ultimodia;
                            $nomeMotorista = filter_input(INPUT_GET, 'motorista')?filter_input(INPUT_GET, 'motorista'):"%";

                        } else{
                            $minimo = filter_input(INPUT_GET, 'min')?filter_input(INPUT_GET, 'min'):$primeiroDia;
                            $maximo = filter_input(INPUT_GET, 'max')?filter_input(INPUT_GET, 'max'):$ultimodia;
                            $nomeMotorista = filter_input(INPUT_GET, 'motorista')?filter_input(INPUT_GET, 'motorista'):"%";
                        }
                        
                        $sql=$db->prepare("SELECT nome_motorista, viagem.placa_veiculo, veiculos.categoria,nome_rota, data_saida, data_chegada, media_comtk FROM `viagem` LEFT JOIN veiculos ON viagem.cod_interno_veiculo = veiculos.cod_interno_veiculo WHERE data_chegada BETWEEN :dtInicial AND :dtFinal AND nome_motorista LIKE :motorista ORDER BY nome_motorista ASC LIMIT :inicio, :qtd");
                        $sql->bindValue(':dtInicial', $minimo);
                        $sql->bindValue(':dtFinal', $maximo);
                        $sql->bindValue(':motorista', $nomeMotorista);
                        $sql->bindValue(':inicio', (int)$pagIni, PDO::PARAM_INT);
                        $sql->bindValue(':qtd',(int)$quantidade, PDO::PARAM_INT);
                        $sql->execute();
                        $dados = $sql->fetchAll();
                
                        foreach($dados as $dado):
                            switch ($dado['categoria']) {
                                case 'Truck':
                                    $meta = 3.5;
                                    $percAting = $dado['media_comtk']==0?0:($dado['media_comtk']/$meta);
                                    break;
                                case 'Toco':
                                    $meta = 3.9;
                                    $percAting = $dado['media_comtk']==0?0:($dado['media_comtk']/$meta);
                                    break;
                                case 'Mercedinha':
                                    $meta = 5.2;
                                    $percAting = $dado['media_comtk']==0?0:($meta/$dado['media_comtk']/$meta);
                                    break;
                                default:
                                    $meta = 1;
                                    $percAting = $dado['media_comtk']==0?0:($dado['media_comtk']/$meta);
                                    break;
                            } 
                        $qtdViagem = $db->prepare("SELECT COUNT(*) as qtd FROM viagem WHERE nome_motorista = :motorista AND data_chegada BETWEEN :dtInicio AND :dtFinal $condicao");
                        $qtdViagem->bindValue(':motorista', $dado['nome_motorista']);
                        $qtdViagem->bindValue(':dtInicio', $primeiroDia);
                        $qtdViagem->bindValue(':dtFinal', $ultimodia);
                        $qtdViagem->execute();
                        $qtdViagem = $qtdViagem->fetch();

                        //valor ganho
                        if($percAting<1){
                            $valor = 0;
                        }elseif($percAting>=1 && $percAting<=1.2){
                            $valor = (100/$qtdViagem['qtd'])*$percAting;
                        }else{
                            $valor = (100/$qtdViagem['qtd'])*1.2;
                        }
                        
                        ?>
                        <tr>
                            <td class="text-center text-nowrap"><?=$dado['nome_motorista']?></td>
                            <td class="text-center text-nowrap"><?=$dado['placa_veiculo']?></td>
                            <td class="text-center text-nowrap"><?=$dado['categoria']?></td>
                            <td class="text-center text-nowrap"><?=$dado['nome_rota']?></td>
                            <td class="text-center text-nowrap"><?=date("d/m/Y", strtotime($dado['data_saida'])) ?></td>
                            <td class="text-center text-nowrap"><?=date("d/m/Y", strtotime($dado['data_chegada'])) ?></td>
                            <td class="text-center text-nowrap"><?=number_format($dado['media_comtk'],2,",",".") ?></td>
                            <td class="text-center text-nowrap"><?=number_format($meta,2,",",".") ?></td>
                            <td class="text-center text-nowrap"><?=number_format($percAting*100,2,",",".")."%"?></td>
                            <td class="text-center text-nowrap"><?=$qtdViagem['qtd']?></td>
                            <td class="text-center text-nowrap"><?="R$".number_format($valor,2,",",".") ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <ul class="pagination justify-content-center">
                    <?php
                    $sqlTotal = $db->prepare("SELECT nome_motorista, viagem.placa_veiculo, veiculos.categoria,nome_rota, data_saida, data_chegada, media_comtk FROM `viagem` LEFT JOIN veiculos ON viagem.cod_interno_veiculo = veiculos.cod_interno_veiculo WHERE data_chegada BETWEEN :dtInicial AND :dtFinal AND nome_motorista LIKE :motorista ");
                    $sqlTotal->bindValue(':dtInicial', $primeiroDia);
                    $sqlTotal->bindValue(':dtFinal', $ultimodia);
                    $sqlTotal->bindValue(':motorista', $nomeMotorista);
                    $sqlTotal->execute();
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#motorista').select2({
                theme: 'bootstrap4'
            });
        
        });
    </script>
</body>
</html>