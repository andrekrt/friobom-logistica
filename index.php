<?php

session_start();
require("conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false){

    $idUsuario = $_SESSION['idUsuario'];

    $sql = $db->prepare("SELECT * FROM usuarios WHERE idusuarios = :idUsuario");
    $sql->bindValue(':idUsuario', $idUsuario);
    $sql->execute();

    if($sql->rowCount()>0){
        $dado = $sql->fetch();

        $nomeUsuario = $dado['nome_usuario'];
        $tipoUsuario = $dado['idtipo_usuario'];
        $_SESSION['tipoUsuario'] = $tipoUsuario;
        $_SESSION['nomeUsuario'] = $nomeUsuario;
        $_SESSION['filial']= $dado['filial'];

        $qtdeVeiculos = $db->query("SELECT * FROM veiculos WHERE ativo = 1")->rowCount();
        $qtdRotas = $db->query("SELECT * FROM rotas")->rowCount();
        $qtdViagem = $db->query("SELECT * FROM viagem")->rowCount();
        $qtdMotoristas = $db->query("SELECT * FROM motoristas WHERE ativo =1")->rowCount();
        $qtdeRepatos = $db->query("SELECT * FROM solicitacoes_new")->rowCount();

        $totalKmRodado = $db->query("SELECT SUM(km_rodado) as kmRodado FROM viagem");
        $totalKmRodado = $totalKmRodado->fetch();
        $totalAbastecido = $db->query("SELECT SUM(litros) as litros FROM viagem");
        $totalAbastecido=$totalAbastecido->fetch();
        $mediaCombustivel = $totalKmRodado['kmRodado']/$totalAbastecido['litros'];
        
        
    }else{
        header("Location:login.php");
    }

}else{
    header("Location:login.php");
}

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FRIOBOM - TRANSPORTE</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="assets/favicon/site.webmanifest">
        <link rel="mask-icon" href="assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.css"/>
        <link rel="stylesheet" href="assets/css/menu.css">
    </head>
    <body>
        <div class="container-fluid corpo">
        <?php require('menu-principal.php') ?>
            <!-- Tela com os dados -->
            <div class="tela-principal">
                <div class="menu-superior">
                   <div class="icone-menu-superior">
                        <img src="assets/images/icones/home.png" alt="">
                   </div>
                   <div class="title">
                        <h2>Bem-Vindo <?php echo $nomeUsuario ?></h2>
                   </div>
                   <div class="menu-mobile">
                        <img src="assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                   </div>
                </div>
                <div class="menu-principal">
                    <div class="indices">
                        <div class="indice-area-title">
                            <div class="icone-indice">
                                <img src="assets/images/dados.png" alt="">
                            </div>
                            <div class="title-indice">
                                <p>Dados Logístico</p>
                            </div>
                        </div>
                    </div>
                    <div class="area-indice-val">
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Veículos</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/veiculo.png" alt="">
                                <p class="qtde"> <?php echo $qtdeVeiculos ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Rotas</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/rotas.png" alt="">
                                <p class="qtde"> <?php echo $qtdRotas ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Viagens</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/despesas.png" alt="">
                                <p class="qtde"> <?php echo $qtdViagem ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Motoristas</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/motoristas.png" alt="">
                                <p class="qtde"> <?php echo $qtdMotoristas ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Reparos</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/reparos.png" alt="">
                                <p class="qtde"> <?php echo $qtdeRepatos ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Média Geral Km/L</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/combustivel.png" alt="">
                                <p class="qtde"> <?php echo number_format($mediaCombustivel,2,",", ".")  ; ?>  </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Média 3/4 Km/L</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/combustivel.png" alt="">
                                <p class="qtde"> 
                                
                                <?php

                                    $sql = $db->query("SELECT veiculos.categoria, SUM(km_rodado) as kmRodado, SUM(litros) as litros FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE veiculos.categoria = 'Mercedinha' ");
                                    $dados = $sql->fetch();
                                    echo number_format($dados['kmRodado']/$dados['litros'],2, ",", ".");
                                

                                ?>  
                                
                                </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Média Toco Km/L</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/combustivel.png" alt="">
                                <p class="qtde"> 
                                
                                <?php

                                    $sql = $db->query("SELECT veiculos.categoria, SUM(km_rodado) as kmRodado, SUM(litros) as litros FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE veiculos.categoria = 'toco' ");
                                    $dados = $sql->fetch();
                                    echo number_format($dados['kmRodado']/$dados['litros'],2, ",", ".");
                                

                                ?>  
                                
                                </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Média Truck Km/L</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/combustivel.png" alt="">
                                <p class="qtde"> 
                                
                                <?php

                                    $sql = $db->query("SELECT veiculos.categoria, SUM(km_rodado) as kmRodado, SUM(litros) as litros FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE veiculos.categoria = 'truck' ");
                                    $dados = $sql->fetch();
                                    echo number_format($dados['kmRodado']/$dados['litros'],2, ",", ".");
                                

                                ?>  
                                
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/js/jquery.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/menu.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
        <script>
            // function carregarPagina(pagina){
            //     $(".tela-principal").load(pagina, function(){
                    
            //     });
            // }

            // $('a.nav-link').click(function(event){
            //     event.preventDefault();

            //     var pagina = $(this).attr("href"); 

            //     carregarPagina(pagina);
                
            // });

            
        </script>
    </body>
</html>