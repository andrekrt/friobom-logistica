<?php

session_start();
require("../conexao.php");

$idModudulo = 13;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    //qtd de ENTRADAS
    $entradas = $db->query("SELECT SUM(total_litros) as totalLitros, SUM(valor_total) as valorTotal FROM combustivel_entrada WHERE situacao='Aprovado'");
    $qtdEntradas = $entradas->fetch();
    $valorTotal = $qtdEntradas['valorTotal']?$qtdEntradas['valorTotal']:0;
    $qtdEntradas = $qtdEntradas['totalLitros']?$qtdEntradas['totalLitros']:0;
    if($valorTotal==0 || $qtdEntradas==0){
        $precoMedio=0;
    }else{
        $precoMedio = $valorTotal/$qtdEntradas;
    }
    

    //qtd saidas
    $saidas = $db->query("SELECT SUM(litro_abastecimento) as litroAbastecimento FROM combustivel_saida");
    $qtdSaidas = $saidas->fetch();
    $qtdSaidas = $qtdSaidas['litroAbastecimento']?$qtdSaidas['litroAbastecimento']:0;

    $estoqueAtual = $qtdEntradas-$qtdSaidas;
    $percentual = ($estoqueAtual/15000)*100;

    // inventario
    $inventario = $db->query("SELECT * FROM combustivel_inventario ORDER BY data_inventario LIMIT 1");
    $inventario = $inventario->fetch();
    $dataInventario = date("d/m/Y", strtotime($inventario['data_inventario']));
    $volume = $inventario['qtd_encontrada'];

}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='index.php'</script>"; 
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
                        <img src="../assets/images/icones/combustivel.png" alt="">
                   </div>
                   <div class="title">
                        <h2>Combustível</h2>
                   </div>
                   <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                   </div>
                </div>
                <div class="menu-principal">
                    <div class="indices">
                        
                    </div>
                    <div class="area-indice-val">
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Entradas</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="../assets/images/icones/combustivel-entrada.png" alt="">
                                <p class="qtde" style="font-size: 20px;"> <?=number_format($qtdEntradas,2,",",".")."l"  ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Saídas</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="../assets/images/icones/combustivel-saida.png" alt="">
                                <p class="qtde" style="font-size: 20px;"> <?=number_format($qtdSaidas,2,",",".")."l"?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Estoque Atual</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="../assets/images/icones/tanque.png" alt="">
                                <p class="qtde" style="font-size: 20px;"> <?=number_format($estoqueAtual,2,",",".")."l" ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind " id="alerta" >
                            <div class="indice-ind-tittle">
                                <p>% Disponível</p>
                            </div>
                            <div class="indice-qtde" >
                                <img src="../assets/images/icones/percentual.png" alt="">
                                <p class="qtde" style="font-size: 20px;"> <?=number_format($percentual,2,",",".")."%" ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind " id="alerta" >
                            <div class="indice-ind-tittle">
                                <p>Preço Médio</p>
                            </div>
                            <div class="indice-qtde" >
                                <img src="../assets/images/icones/preco.png" alt="">
                                <p class="qtde" style="font-size: 20px;"> <?="R$ ".number_format($precoMedio,2,",",".") ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind " id="alerta" >
                            <div class="indice-ind-tittle">
                                <p> Último Inventário</p>
                            </div>
                            <div class="indice-qtde" >
                                <img src="../assets/images/icones/percentual.png" alt="">
                                <p class="qtde" style="font-size: 20px;"> <?=$dataInventario ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind " id="alerta" >
                            <div class="indice-ind-tittle">
                                <p>Volume Encotrado</p>
                            </div>
                            <div class="indice-qtde" >
                                <img src="../assets/images/icones/inventario.png" alt="">
                                <p class="qtde" style="font-size: 20px;"> <?=number_format($volume,2,",",".")."l" ?> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script>
            var percentual = <?= $percentual ?>;
            if(percentual<=50 && percentual>25){
                document.getElementById("alerta").style.backgroundColor='green';
            }else if(percentual<=25 && percentual>15){
                document.getElementById("alerta").style.backgroundColor='orange';
            }else if(percentual<=15){
                document.getElementById("alerta").style.backgroundColor='red';
                document.getElementById("alerta").classList.add('fa-blink');
            }
        </script>
    </body>
</html>