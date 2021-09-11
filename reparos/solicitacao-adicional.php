<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] == 3 || $_SESSION['tipoUsuario'] == 99){

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $idUsuario = $_SESSION['idUsuario'];
    $tipoUsuario = $_SESSION['tipoUsuario'];

    $token = filter_input(INPUT_GET, 'token');
    $sql = $db->prepare("SELECT * FROM solicitacoes_new WHERE token = :token");
    $sql->bindValue(':token',$token);
    $sql->execute();
    $dados = $sql->fetchAll();

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
        <title>Nova Peça/ Serviço</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="../assets/favicon/site.webmanifest">
        <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    </head>
    <body>
        <div class="container-fluid corpo">
            <div class="menu-lateral">
                <div class="logo">  
                    <img src="../assets/images/logo.png" alt="">
                </div>
                <div class="opcoes">
                    <div class="item">
                        <a href="../index.php">
                            <img src="../assets/images/menu/inicio.png" alt="">
                        </a>
                    </div>
                    <div class="item">
                        <a class="" onclick="menuVeiculo()">
                            <img src="../assets/images/menu/veiculos.png" alt="">
                        </a>
                        <nav id="submenu">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="../veiculos/veiculos.php"> Veículos </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../veiculos/form-veiculos.php"> Cadastrar Veículo </a> </li>
                                <li class="nav-item"> <a href="../veiculos/relatorio.php" class="na-link">Despesas por Veículo</a> </li>
                                <li class="nav-item"> <a href="../veiculos/gastos.php" class="na-link">Relatório</a> </li>
                            </ul> 
                        </nav>  
                    </div>
                    <div class="item">
                        <a onclick="menuRota()">
                            <img src="../assets/images/menu/rotas.png" alt="">
                        </a>
                        <nav id="submenuRota">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="../rotas/rotas.php"> Rotas </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../rotas/form-rota.php"> Cadastrar Rota </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../rotas/relatorio.php"> Relatório</a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuMotorista()">
                            <img src="../assets/images/menu/motoristas.png" alt="">
                        </a>
                        <nav id="submenuMotorista">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="../motoristas/motoristas.php"> Motoristas </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../motoristas/form-motorista.php"> Cadastrar Motorista </a> </li>
                                <li class="nav-item"> <a href="../motoristas/dados.php" class="nav-link"> Relatório</a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuDespesas()">
                            <img src="../assets/images/menu/despesas.png" alt="">
                        </a>
                        <nav id="submenuDespesa">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="../controle-despesas/despesas.php"> Despesas </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../controle-despesas/form-lancar-despesas.php"> Lançar Despesa </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../controle-despesas/gerar-planilha.php"> Planilha de Despesas </a> </li>
                                <li class="nav-item"> <a onclick="menuEntregas()"> Entregas Capital </a>
                                    <nav id="submenuCapital">
                                        <ul class="nav flex-column">
                                            <li class="nav-item"> <a href="../controle-despesas/entregas-capital/form-entregas.php"> Registrar Entregas </a> </li>
                                            <li class="nav-item"> <a href="../controle-despesas/entregas-capital/entregas.php">  Entregas </a> </li>
                                        </ul> 
                                    </nav>
                                </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuCheck()">
                            <img src="../assets/images/menu/check-list.png" alt="">
                        </a>
                        <nav id="submenuCheck">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="../check-list/check-list.php"> Check-Lists </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../check-list/form-check.php"> Fazer Check-List </a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuReparos()">
                            <img src="../assets/images/menu/reparos.png" alt="">
                        </a>
                        <nav id="submenuReparos">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="../reparos/solicitacoes.php"> Solicitações </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../reparos/form-solicitacao.php"> Nova Solicitação </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../reparos/relatorio.php"> Valores Gastos</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../reparos/local-reparo.php">Local de Reparo</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../reparos/pecas.php">Peças/Serviços</a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a href="../sair.php">
                            <img src="../assets/images/menu/sair.png" alt="">
                        </a>
                    </div>
                </div>                
            </div>
            <!-- finalizando menu lateral -->
            <!-- Tela com os dados -->
            <div class="tela-principal">
                <div class="menu-superior">
                   <div class="icone-menu-superior">
                        <img src="../assets/images/icones/reparos.png" alt="">
                   </div>
                   <div class="title">
                        <h2>Solicitação Adicional</h2>
                   </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="add-solicitacao-adicional.php" class="despesas" method="post" enctype="multipart/form-data">
                        <div id="formulario">
                            <input type="hidden" name="token" value="<?=$dados[0]['token']?>">
                            <div class="form-row">
                                <div class="form-group col-md-2 espaco">
                                    <label for="veiculo">Placa Veículo</label>
                                    <input type="text" readonly name="veiculo" class="form-control" value="<?= $dados[0]['placa'] ?>">
                                </div>
                                <div class="form-group col-md-3 espaco">
                                    <label for="motorista">Motorista</label>
                                    <input type="text" readonly name="motorista" class="form-control" value="<?= $dados[0]['motorista'] ?>">
                                </div>
                                <div class="form-group col-md-2 espaco">
                                    <label for="rota">Rota</label>
                                    <input type="text" readonly name="rota" class="form-control" value="<?= $dados[0]['rota'] ?>">
                                </div>
                                <div class="form-group col-md-3 espaco">
                                    <label for="problema">Problema</label>
                                    <input type="text" readonly name="problema" id="problema" class="form-control" value="<?=$dados[0]['problema'] ?>">
                                </div>
                                <div class="form-group col-md-2 espaco">
                                    <label for="localReparo">Local Reparo</label>
                                    <input type="text" id="localReparo" readonly name="localReparo" class="form-control" value="<?= $dados[0]['local_reparo'] ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-grupo col-md-4 espaco">
                                    <label for="peca">Peça/Serviço</label>
                                    <select name="peca[]" required class="form-control" id="peca">
                                    <option value=""></option>
                                    <?php

                                    $sql = $db->query("SELECT * FROM peca_reparo");
                                    $pecas = $sql->fetchAll();
                                    foreach ($pecas as $peca) {

                                    ?>
                                        <option value="<?=$peca['id_peca_reparo'] ?>"><?=$peca['id_peca_reparo']." - ". $peca['descricao'] ?></option>
                                    <?php

                                    }

                                    ?>
                                </select>
                                </div>
                                <div class="form-grupo col-md-1 espaco">
                                    <label for="qtd">Qtd</label>
                                    <input type="text" required name="qtd[]" id="qtd" class="form-control">
                                </div>
                                <div class="form-grupo col-md-2 espaco">
                                    <label for="vlUnit">Valor Unit.</label>
                                    <input type="text" required name="vlUnit[]" id="vlUnit" class="form-control">
                                </div>
                                <div class="mb-3 form-grupo col-md-3 espaco">
                                    <label for="imagem" class="form-label">Imagem do problema</label>
                                    <input type="file" name="imagem[]" class="form-control" id="imagem" multiple>    
                                </div>
                                <div style="margin: auto; margin-left: 0;">
                                    <button type="button" class="btn btn-danger" id="add-peca">Adicionar Peça</button>
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="Adicionar" class="btn btn-primary" name="novo-servico">
                    </form>
                </div>
            </div>
        </div>

        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script>
            $(document).ready(function() {
                $('#peca').select2();
            });
        </script>
        <script>
        $(document).ready(function(){

            var cont = 1;
            $('#add-peca').click(function(){
                cont++;

                $('#formulario').append('<div class="form-row"> <div class="form-grupo col-md-4 espaco"> <label for="peca">Peça/Serviço</label> <select name="peca[]" class="form-control" id="peca"> <option value=""></option> <?php $sql = $db->query("SELECT * FROM peca_reparo"); $pecas = $sql->fetchAll(); foreach ($pecas as $peca) {?> <option value="<?=$peca['id_peca_reparo'] ?>"><?=$peca['id_peca_reparo']." - ". $peca['descricao'] ?></option> <?php } ?> </select> </div> <div class="form-grupo col-md-1 espaco"> <label for="qtd">Qtd</label> <input type="text" name="qtd[]" id="qtd" class="form-control"> </div> <div class="form-grupo col-md-2 espaco"> <label for="vlUnit">Valor Unit.</label> <input type="text" name="vlUnit[]" id="vlUnit" class="form-control"> </div> <div class="mb-3 form-grupo col-md-3 espaco"> <label for="imagem" class="form-label">Imagem do problema</label> <input type="file" name="imagem[]" class="form-control" id="imagem" multiple> </div> </div>');
            });
        });
    </script>
    </body>
</html>