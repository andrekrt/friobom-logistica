<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==4){

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $idUsuario = $_SESSION['idUsuario'];
    $idSolicitacao = filter_input(INPUT_GET, "idSolic");

    $sql = $db->query("SELECT * FROM `solicitacoes` INNER JOIN usuarios where solicitacoes.id = $idSolicitacao" );

    if($sql->rowCount()>0){
        $dado=$sql->fetch();

        $servico = $dado['servico'];
        $descricao = $dado['descricao'];
        $placa = $dado['placarVeiculo'];
        $statusAtual = $dado['statusSolic'];
        $obs = $dado['obs']?$dado['obs']:"";
        $valor = $dado['valor']?$dado['valor']:"";
        $local = $dado['localReparo']?$dado['localReparo']:"";
        
    }
    
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
        <title>Validação</title>
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
                        <h2>Análise </h2>
                   </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="resp-analise.php" class="despesas" method="post">
                        <div class="form-row">
                            <div class="form-group">
                                <input type="hidden" readonly name="idSolic" class="form-control" value="<?php echo $idSolicitacao; ?>">
                            </div>
                            <div class="form-group espaco col-md-4">
                                <label for="servico">Serviço / Peça</label>
                                <input type="text" readonly name="servico" class="form-control" value="<?php echo $servico; ?>">
                            </div>
                            <div class="form-group espaco col-md-4">
                                <label for="servico">Descrição</label>
                                <input type="text" readonly name="descricao" class="form-control" value="<?php echo $descricao; ?>">
                            </div>
                            <div class="form-group espaco col-md-4">
                                <label for="servico">Placa</label>
                                <input type="text" readonly name="placa" class="form-control" value="<?php echo $placa; ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            
                            <div class="form-group espaco col-md-4">
                                <label for="servico">Valor Geral do Serviço/Peça</label>
                                <input type="text" id="valor" required name="valor" class="form-control" value="<?php echo $valor; ?>">
                            </div>
                            <div class="form-group espaco col-md-4">
                                <label for="local">Local do Serviço</label>
                                <input type="text" name="local" class="form-control" value="<?php echo $local; ?>">
                            </div>
                            <div class="form-group espaco col-md-4">
                                <label for="servico">Status da Solicitação</label>
                                <select name="status" id="" class="form-control">
                                    <option value="<?php $statusAtual ?>"> <?php echo $statusAtual; ?> </option>
                                    <option value="Orçamento">Reprovado</option>
                                    <option value="Aprovado"> Aprovado </option>
                                    <option value="Em Análise">Em Análise</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group espaco col-md-4">
                                <label for="obs">Observações</label>
                                <textarea name="obs" id=""  rows="5" class="form-control"> </textarea>
                            </div>
                        </div>
                         <?php
                        $busca = $db->query("SELECT * FROM solicitacoes02 WHERE idSocPrinc = $idSolicitacao ");
                        if($busca->rowCount()>0){
                            $dados = $busca->fetch();
                            $servicoAdcional = $dados['servico'];
                            $descricao = $dados['descricao'];
                            
                        ?>
                        <div class="form-row">
                            <div class="form-group col-md-4 espaco">
                                <label for="">Serviço/Peça Adicional</label>
                                <input type="text" readonly required name="servicoAdicional" class="form-control"  value="<?php echo $servicoAdcional ?>">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="">Descricao</label>
                                <input type="text" readonly name="descricaoAdicional" class="form-control" value="<?php echo $descricao ?>">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="">Valor</label>
                                <input type="text" id="valorNovo" required name="valorNovo" id="" class="form-control">
                            </div>
                        </div>
                        <?php
                        }            

                        ?>
                        <button type="submit" class="btn btn-primary"> Enviar </button>                
                    </form>
                </div>
            </div>
        </div>

        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script src="../assets/js/jquery.mask.js"></script>
        <script>
            jQuery(function($){
                $("#valor").mask('###0,00', {reverse: true});
                $("#valorNovo").mask('###0,00', {reverse: true});
            })
        </script>
        
    </body>
</html>