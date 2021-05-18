<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==2 || $_SESSION['tipoUsuario'] == 99){

    $nomeUsuario = $_SESSION['nomeUsuario'];

    $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM check_list");
    
}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Check-List</title>
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
            <div class="menu-lateral" id="menu-lateral">
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
                                <li class="nav-item"> <a class="nav-link" href="../veiculos/revisao.php"> Revisões </a> </li>
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
                                <li class="nav-item"> <a class="nav-link" href="../controle-despesas/despesas.php"> Despesas </a> </li><li class="nav-item"> <a class="nav-link" href="../controle-despesas/complementos.php"> Complementos </a> </li>
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
                        <a onclick="menuCarregamentos()">
                            <img src="../assets/images/menu/carregamentos.png" alt="">
                        </a>
                        <nav id="submenuCarregamentos">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a href="../carregamentos/carregamentos.php" class="nav-link"> Carregamentos </a> </li>
                                <li class="nav-item"> <a href="../carregamentos/form-carregamento.php" class="nav-link"> Novo Carregamento </a> </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="item">
                        <a onclick="menuAlmoxerifado()">
                            <img src="../assets/images/menu/almoxerifado.png" alt="">
                        </a>
                        <nav id="submenuAlmoxerifado">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a href="../almoxerifado/pecas.php" class="nav-link"> Estoque </a> </li>
                                <li class="nav-item"> <a href="../almoxerifado/entradas.php" class="nav-link"> Entrada </a> </li>
                                <li class="nav-item"> <a href="../almoxerifado/saidas.php" class="nav-link"> Saída </a> </li>
                                <li class="nav-item"> <a href="../almoxerifado/ordem-servico.php" class="nav-link"> Ordem de Serviço </a> </li>
                                <li class="nav-item"> <a href="../fornecedores/fornecedores.php" class="nav-link"> Fornecedores </a> </li>
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
                        <img src="../assets/images/icones/check.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Check-List Realizados</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <div class="filtro">
                        <form action="" class="form-inline" method="post">
                            <div class="form-row">
                                <select name="placa" id="" class="form-control">
                                    <option value=""></option>
                                    <?php
                                        $filtro = $db->query("SELECT * FROM check_list ORDER BY idcheck_list DESC");
                                        if($filtro->rowCount()>0){
                                            $dados = $filtro->fetchAll();
                                            foreach($dados as $dado){

                                    ?>
                                    <option value="<?php echo $dado['placa_veiculo'] ?>"> <?php echo $dado['placa_veiculo'] ?> </option>
                                    <?php            

                                            }
                                        }
                                    
                                    ?>
                                </select>
                                <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                            </div>
                        </form>
                        <a href="check-list-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap"> Placa do Veículo</th>
                                    <th scope="col" class="text-center text-nowrap"> Data do Check-List</th>
                                    <th scope="col" class="text-center text-nowrap"> Tipo do Veículo</th>
                                    <th scope="col" class="text-center text-nowrap"> Km Inicial</th>
                                    <th scope="col" class="text-center text-nowrap"> Situação</th>
                                    <th scope="col" class="text-center text-nowrap"> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                
                                $totalDespesas = $selecionar->rowCount();
                                $qtdPorPagina = 10;
                                $numPaginas = ceil($totalDespesas/$qtdPorPagina);
                                $paginaInicial = ($qtdPorPagina*$pagina)-$qtdPorPagina;

                                if(isset($_POST['filtro']) && empty($_POST['placa'])==false){
                                    $placa = filter_input(INPUT_POST, 'placa');
                                    $consulta = "SELECT * FROM check_list WHERE placa_veiculo = :placaVeiculo ORDER BY data_check DESC LIMIT $paginaInicial, $qtdPorPagina";

                                    $consulta=$db->prepare($consulta);
                                    $consulta->bindValue(':placaVeiculo', $placa);
                                    if($consulta->execute()){
                                        $dados = $consulta->fetchAll();
                                        foreach($dados as $dado){
                                            $idCheck = $dado['idcheck_list'];
                                ?>
                                    <tr>
                                        <td class="text-center text-nowrap"> <?php echo $dado['placa_veiculo'] ?> </td>
                                        <td class="text-center text-nowrap"> <?php echo date("d/m/Y",strtotime( $dado['data_check']))  ?> </td>
                                        <td class="text-center text-nowrap"> <?php echo $dado['tipo_veiculo'] ?> </td>
                                        <td class="text-center text-nowrap"> <?php echo $dado['km_inicial'] ?> </td>
                                        <td class="text-center text-nowrap"> <?php echo $dado['situacao'] ?> </td>
                                        <td class="text-center text-nowrap"> 
                                            <button type="button" class="btn " data-toggle="modal" data-target="#modalVisualizar<?php echo $dado['idcheck_list']; ?>" data-whatever="@mdo" value="<?php echo $dado['idcheck_list']; ?>" name="id" > <img class="icone" src="../assets/images/icon-olho.png" alt=""> </button>
                                            <?php 
                                                if($dado['situacao']!='Finalizado'){
                                            ?>
                                                <button type="button" class="btn" data-toggle="modal" data-target="#modal<?php echo $dado['idcheck_list']; ?>" data-whatever="@mdo" value="<?php echo $dado['idcheck_list']; ?>" name="id" > <img class="icone" src="../assets/images/editar.png" alt=""> </button>
                                                
                                            <?php        
                                                }elseif($dado['situacao']=='Finalizado'){
                                            ?>
                                                <a href="imprimir.php?id=<?php echo $dado['idcheck_list']; ?>"> <img class="icone" src="../assets/images/imprimir.png" alt=""> </a>
                                            <?php        
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <!-- INICIO MODAL supervisor-->
                                    <div class="modal fade" id="modal<?php echo $dado['idcheck_list']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <form action="editar.php"  method="post"  enctype="multipart/form-data">
                                                        <div class="form-row">
                                                            <input type="hidden" name="idCheck" value="<?php echo $dado['idcheck_list']; ?>">
                                                            <div class="form-group col-md-6">
                                                                <label for="data_solicitacao" class="col-form-label">Data Check-List</label>
                                                                <input type="text" class="form-control" Readonly id="data_solicitacao" value="<?php echo date("d/m/Y",strtotime($dado['data_check']));  ?>">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="placa" class="col-form-label">Veículo</label>
                                                                <input type="text" class="form-control" id="placa" value="<?php echo $dado['placa_veiculo']  ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="qtd-nf" class="col-form-label">Qtde NF's</label>
                                                                <input type="text" class="form-control" name="qtd-nf" id="qtd-nf" value="">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="valorCarga" class="col-form-label">Valor Carga</label>
                                                                <input type="text" class="form-control" name="valorCarga" id="valorCarga" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="dataSaida" class="col-form-label">Data e Hora Saída</label>
                                                                <input type="DateTime-Local" class="form-control" name="dataSaida" id="dataSaida" value="">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="horimetro" class="col-form-label">Horímetro</label>
                                                                <input type="text" class="form-control" name="horimetro" id="horimetro" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="rota" class="col-form-label">Rota</label>
                                                                <input type="text" class="form-control" name="rota" id="rota" value="">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="numCarga" class="col-form-label">Carregamento</label>
                                                                <input type="text" class="form-control" name="numCarga" id="numCarga" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="pesoCarga" class="col-form-label">Peso Carga</label>
                                                                <input type="text" class="form-control" name="pesoCarga" id="pesoCarga" >
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="motorista" class="col-form-label">Motorista</label>
                                                                <input type="text" class="form-control" name="motorista" id="motorista" >
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="col-form-label" for="kmInicial">Km Inicial</label>
                                                                <input type="text" name="kmInicial" class="form-control" id="kmInicial">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="situacao" class="col-form-label">Situação</label>
                                                                <select name="situacao" required class="form-control" id="">
                                                                    <option value="">
                                                                    </option>
                                                                    <option value="Finalizado">
                                                                        Finalizado
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                        
                                                        <button type="submit" name="analisar" class="btn btn-primary">Atualizar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- FIM MODAL -->
                                    <!-- INICIO MODAL de visualizar -->
                                    <div class="modal fade" id="modalVisualizar<?php echo $dado['idcheck_list']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <form action="atualiza.php"  method="post"  enctype="multipart/form-data">
                                                        <div class="form-row">
                                                            <input type="hidden" name="idCheck" value="<?php echo $dado['idcheck_list']; ?>">
                                                            <div class="form-group col-md-4">
                                                                <label for="data_solicitacao" class="col-form-label">Data </label>
                                                                <input type="text" class="form-control" Readonly id="data_solicitacao" value="<?php echo date("d/m/Y",strtotime($dado['data_check']));  ?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="placa" class="col-form-label">Veículo</label>
                                                                <input type="text" name="placaVeiculo" class="form-control" id="placa" value="<?php echo $dado['placa_veiculo']  ?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="qtd-nf" class="col-form-label">Tipo Veiculo</label>
                                                                <input type="text" class="form-control" name="tipoVeiculo" id="tipoVeiculo" value="<?php echo $dado['tipo_veiculo']  ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="">Observações</label>
                                                            <textarea name="observacoes" id="" rows="3" class="form-control" ><?php echo $dado['observacoes'] ?></textarea>
                                                        </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label for="dataSaida" class="col-form-label">Qtde NF's</label>
                                                                <input type="text" readonly class="form-control" name="qtdNf" id="qtdNf"  value="<?php echo $dado['qtde_nf'] ?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="valorCarga" class="col-form-label">Valor Carga</label>
                                                                <input type="text" class="form-control" readonly name="valorCarga" id="valorCarga" value="<?php echo str_replace(".", ",",$dado['valor_carga'])?str_replace(".", ",",$dado['valor_carga']):0  ?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="dataSaida" class="col-form-label">Data de Saída</label>
                                                                <input type="text" readonly class="form-control" name="dataSaida" id="dataSaida" value="<?php echo $dado['data_saida'] ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label for="horimetro" class="col-form-label">Horímetro</label>
                                                                <input type="text" class="form-control" name="horimetro" id="horimetro" readonly value="<?php echo $dado['horimetro'] ?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="rota" class="col-form-label">Rota</label>
                                                                <input type="text" class="form-control" name="rota" id="rota" readonly value="<?php echo $dado['rota'] ?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="carregamento" class="col-form-label">Carregamento</label>
                                                                <input type="text" class="form-control" name="carregamento" readonly id="carregamento" value="<?php echo $dado['num_carregemento'] ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label for="pesoCarga" class="col-form-label">Peso Carga</label>
                                                                <input type="text" readonly class="form-control" name="pesoCarga" value="<?php echo str_replace(".", ",", $dado['peso_carga']) ?>" id="pesoCarga" >
                                                            </div>
                                                            <div class="form-group col-md-8">
                                                                <label for="motorista" class="col-form-label">Motorista</label>
                                                                <input type="text" class="form-control" readonly name="motorista" id="motorista" value="<?php echo $dado['motorista'] ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label class="col-form-label" for="kmInicial">Km Inicial</label>
                                                                <input type="text" name="kmInicial" readonly class="form-control" id="kmInicial" value="<?php echo $dado['km_inicial'] ?>">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="situacao" class="col-form-label">Situação</label>
                                                                <select name="situacao" readonly class="form-control" id="">
                                                                    <option value="<?php echo $dado['situacao'] ?>"> <?php echo $dado['situacao'] ?>
                                                                    </option>
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">    
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                    <button type="submit" name="analisar" class="btn btn-primary">Atualizar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- FIM MODAL -->
                                <?php
                                        }
                                    }
                                    
                                }else{
                                    $consulta = $db->query("SELECT * FROM check_list ORDER BY data_check DESC LIMIT $paginaInicial, $qtdPorPagina");
                                    if($consulta->rowCount()>0){
                                        $dados = $consulta->fetchAll();
                                        foreach($dados as $dado){
                                            $idCheck = $dado['idcheck_list'];
                                ?>
                                    <tr>
                                        <td class="text-center text-nowrap"> <?php echo $dado['placa_veiculo'] ?> </td>
                                        <td class="text-center text-nowrap"> <?php echo date("d/m/Y",strtotime( $dado['data_check']))  ?> </td>
                                        <td class="text-center text-nowrap"> <?php echo $dado['tipo_veiculo'] ?> </td>
                                        <td class="text-center text-nowrap"> <?php echo $dado['km_inicial'] ?> </td>
                                        <td class="text-center text-nowrap"> <?php echo $dado['situacao'] ?> </td>
                                        <td class="text-center text-nowrap"> 
                                            <button type="button" class="btn " data-toggle="modal" data-target="#modalVisualizar<?php echo $dado['idcheck_list']; ?>" data-whatever="@mdo" value="<?php echo $dado['idcheck_list']; ?>" name="id" > <img class="icone" src="../assets/images/icon-olho.png" alt=""> </button>
                                            <?php 
                                                if($dado['situacao']!='Finalizado'){
                                            ?>
                                                <button type="button" class="btn" data-toggle="modal" data-target="#modal<?php echo $dado['idcheck_list']; ?>" data-whatever="@mdo" value="<?php echo $dado['idcheck_list']; ?>" name="id" > <img class="icone" src="../assets/images/editar.png" alt=""> </button>
                                                
                                            <?php        
                                                }elseif($dado['situacao']=='Finalizado'){
                                            ?>
                                                <a href="imprimir.php?id=<?php echo $dado['idcheck_list']; ?>"> <img class="icone" src="../assets/images/imprimir.png" alt=""> </a>
                                            <?php        
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <!-- INICIO MODAL de editar -->
                                    <div class="modal fade" id="modal<?php echo $dado['idcheck_list']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <form action="editar.php"  method="post"  enctype="multipart/form-data">
                                                        <div class="form-row">
                                                            <input type="hidden" name="idCheck" value="<?php echo $dado['idcheck_list']; ?>">
                                                            <div class="form-group col-md-6">
                                                                <label for="data_solicitacao" class="col-form-label">Data Check-List</label>
                                                                <input type="text" class="form-control" Readonly id="data_solicitacao" value="<?php echo date("d/m/Y",strtotime($dado['data_check']));  ?>">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="placa" class="col-form-label">Veículo</label>
                                                                <input type="text" class="form-control" Readonly id="placa" value="<?php echo $dado['placa_veiculo']  ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="qtd-nf" class="col-form-label">Qtde NF's</label>
                                                                <input type="text" class="form-control" required name="qtd-nf" id="qtd-nf" value="">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="valorCarga" class="col-form-label">Valor Carga</label>
                                                                <input type="text" class="form-control" required name="valorCarga" id="valorCarga" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="dataSaida" class="col-form-label">Data e Hora Saída</label>
                                                                <input type="DateTime-Local" class="form-control" name="dataSaida" id="dataSaida" required value="">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="horimetro" class="col-form-label">Horímetro</label>
                                                                <input type="text" class="form-control" required name="horimetro" id="horimetro" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="rota" class="col-form-label">Rota</label>
                                                                <input type="text" class="form-control" required name="rota" id="rota" value="">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="numCarga" class="col-form-label">Carregamento</label>
                                                                <input type="text" class="form-control" required name="numCarga" id="numCarga" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="pesoCarga" class="col-form-label">Peso Carga</label>
                                                                <input type="text" class="form-control" required name="pesoCarga" id="pesoCarga" >
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="motorista" class="col-form-label">Motorista</label>
                                                                <input type="text" class="form-control" required name="motorista" id="motorista" >
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="col-form-label" for="kmInicial">Km Inicial</label>
                                                                <input type="text" required name="kmInicial" class="form-control" id="kmInicial">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="situacao" class="col-form-label">Situação</label>
                                                                <select name="situacao" required class="form-control" id="">
                                                                    <option value="">
                                                                    </option>
                                                                    <option value="Finalizado">
                                                                        Finalizado
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">  
                                                        <button type="submit" name="analisar" class="btn btn-primary">Atualizar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- FIM MODAL -->
                                    <!-- INICIO MODAL de visualizar -->
                                    <div class="modal fade" id="modalVisualizar<?php echo $dado['idcheck_list']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <form action="atualiza.php"  method="post"  enctype="multipart/form-data">
                                                        <div class="form-row">
                                                            <input type="hidden" name="idCheck" value="<?php echo $dado['idcheck_list']; ?>">
                                                            <div class="form-group col-md-4">
                                                                <label for="data_solicitacao" class="col-form-label">Data </label>
                                                                <input type="text" class="form-control" id="data_solicitacao" value="<?php echo date("d/m/Y",strtotime($dado['data_check']));  ?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="placa" class="col-form-label">Veículo</label>
                                                                <input type="text" name="placaVeiculo" class="form-control" id="placa" value="<?php echo $dado['placa_veiculo']  ?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="qtd-nf" class="col-form-label">Tipo Veiculo</label>
                                                                <input type="text" class="form-control" name="tipoVeiculo" id="tipoVeiculo" value="<?php echo $dado['tipo_veiculo']  ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="">Observações</label>
                                                            <textarea name="observacoes" id="" rows="3" class="form-control"  ><?php echo $dado['observacoes'] ?></textarea>
                                                        </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label for="dataSaida" class="col-form-label">Qtde NF's</label>
                                                                <input type="text" class="form-control" name="qtdNf" readonly id="qtdNf" value="<?php echo $dado['qtde_nf'] ?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="valorCarga" class="col-form-label">Valor Carga</label>
                                                                <input type="text" class="form-control" name="valorCarga" id="valorCarga" readonly value="<?php echo  str_replace(".", ",",$dado['valor_carga'])?str_replace(".", ",",$dado['valor_carga']):0?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="dataSaida" class="col-form-label">Data de Saída</label>
                                                                <input type="text" class="form-control" name="dataSaida" readonly id="dataSaida" value="<?php echo $dado['data_saida']?$dado['data_saida']:"00/00/0000" ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label for="horimetro" class="col-form-label">Horímetro</label>
                                                                <input type="text" class="form-control" name="horimetro" readonly id="horimetro" value="<?php echo $dado['horimetro']?$dado['horimetro']:0 ?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="rota" class="col-form-label">Rota</label>
                                                                <input type="text" class="form-control" name="rota" readonly id="rota" value="<?php echo $dado['rota'] ?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="carregamento" class="col-form-label">Carregamento</label>
                                                                <input type="text" class="form-control" name="carregamento" id="carregamento" readonly value="<?php echo $dado['num_carregemento'] ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label for="pesoCarga" class="col-form-label">Peso Carga</label>
                                                                <input type="text" class="form-control" name="pesoCarga" readonly value="<?php echo str_replace(".", ",", $dado['peso_carga'])?str_replace(".", ",", $dado['peso_carga']):0 ?>" id="pesoCarga" >
                                                            </div>
                                                            <div class="form-group col-md-8">
                                                                <label for="motorista" class="col-form-label">Motorista</label>
                                                                <input type="text" class="form-control" name="motorista" id="motorista" readonly value="<?php echo $dado['motorista'] ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label class="col-form-label" for="kmInicial">Km Inicial</label>
                                                                <input type="text" name="kmInicial" class="form-control" id="kmInicial" readonly value="<?php echo $dado['km_inicial']?$dado['km_inicial']:0 ?>">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="situacao" class="col-form-label">Situação</label>
                                                                <select name="situacao" readonly class="form-control" id="">
                                                                    <option value="<?php echo $dado['situacao'] ?>"> <?php echo $dado['situacao'] ?>
                                                                    </option>
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">    
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                        <button type="submit" name="analisar" class="btn btn-primary">Atualizar</button>
                                                    </form>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- FIM MODAL -->
                                <?php            
                                        }
                                    }
                                }
                                
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php

                        $paginaAnterior = $pagina - 1;
                        $paginaPosterior = $pagina + 1;

                    ?>
                    <nav aria-label="Navegação de página exemplo" class="paginacao">
                        <ul class="pagination">
                            <li class="page-item">
                                <?php
                                if ($paginaAnterior != 0) {
                                    echo "<a class='page-link' href='check-list.php?pagina=$paginaAnterior' aria-label='Anterior'>
                                        <span aria-hidden='true'>&laquo;</span>
                                        <span class='sr-only'>Anterior</span>
                                    </a>";
                                } else {
                                    echo "<a class='page-link' aria-label='Anterior'> 
                                            <span aria-hidden='true'>&laquo;</span>
                                            <span class='sr-only'>Anterior</span>
                                        </a>";
                                }
                                ?>

                            </li>
                            <?php
                            for ($i = 1; $i < $numPaginas + 1; $i++) {
                                echo "<li class='page-item'><a class='page-link' href='check-list.php?pagina=$i'>$i</a></li>";
                            }
                            ?>
                            <li class="page-item">
                                <?php
                                if ($paginaPosterior <= $numPaginas) {
                                    echo " <a class='page-link' href='check-list.php?pagina=$paginaPosterior' aria-label='Próximo'>
                                        <span aria-hidden='true'>&raquo;</span>
                                        <span class='sr-only'>Próximo</span>
                                    </a>";
                                } else {
                                    echo " <a class='page-link' aria-label='Próximo'>
                                                <span aria-hidden='true'>&raquo;</span>
                                                <span class='sr-only'>Próximo</span>
                                        </a> ";
                                }
                                ?>

                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
    </body>
</html>