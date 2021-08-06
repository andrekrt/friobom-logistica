<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] == 3 || $_SESSION['tipoUsuario'] == 99){

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $tipoUsuario = $_SESSION['tipoUsuario'];
    $idUsuario = $_SESSION['idUsuario'];

    $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Peças</title>
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
                        <a onclick="menuOcorrencias()">
                            <img src="../assets/images/menu/ocorrencias.png" alt="">
                        </a>
                        <nav id="submenuOcorrencias">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="../ocorrencias/form-ocorrencias.php"> Registrar Nova Ocorrência </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../ocorrencias/ocorrencias.php"> Listar Ocorrências </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../ocorrencias/relatorio.php"> Ocorrências por Motorista</a> </li>
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
                                <li class="nav-item"> <a class="nav-link" href="../reparos/local-reparo.php">Local de Reparo</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="../reparos/pecas.php">Peças/Serviços</a> </li>
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
                        <img src="../assets/images/icones/reparos.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Peças/Serviços</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <!-- iniciando filtro por veiculo -->
                    <div class="filtro">
                        <form action="" class="form-inline" method="post">
                            <div class="form-row">
                                <div class="form-group">
                                    <select name="peca" id="peca" class="form-control">
                                        <option value=""></option>
                                        <?php
                                            $sql = $db->query("SELECT id_peca_reparo, descricao FROM peca_reparo ORDER BY descricao ASC");
                                            if($sql->rowCount()>0){
                                                $dados = $sql->fetchAll();
                                                foreach($dados as $dado):
                                        ?>
                                            <option value="<?=$dado['id_peca_reparo']?>"><?=$dado['id_peca_reparo']." - ".$dado['descricao']?></option>
                                        <?php
                                                endforeach;
                                            }
                                        ?>
                                    </select>
                                    <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                                </div>
                            </div>
                        </form>
                        <div class="area-opcoes-button">     
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalPecaServico" data-whatever="@mdo" name="pecaServico">Nova Peça/Serviço</button>
                        </div> 
                    </div>
                    <!-- fim filtro por veiculo -->
                    <!-- MODAL CADASTRO DE peça -->
                    <div class="modal fade" id="modalPecaServico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Peça/Serviço</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="add-peca.php" method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="descricao"> Descrição</label>
                                                <input type="text" required name="descricao" class="form-control" id="descricao">
                                            </div>
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="categoria"> Categoria </label>
                                                <select name="categoria" id="categoria" class="form-control">
                                                    <option value=""></option>
                                                    <option value="Terminais da Barra de Direção">Terminais da Barra de Direção</option>
                                                    <option value="Abraçadeira">Abraçadeira</option>
                                                    <option value="Molas">Molas</option>
                                                    <option value="Pino de Centro">Pino de Centro</option>
                                                    <option value="Rolamento">Rolamento</option>
                                                    <option value="Bicos Injetores">Bicos Injetores</option>
                                                    <option value="Motor de Partida">Motor de Partidada</option>
                                                    <option value="Pneu">Pneu</option>
                                                    <option value="Enbuchamentos">Enbuchamentos</option>
                                                    <option value="Alinhamento">Alinhamento</option>
                                                    <option value="Balanceamento">Balanceamento</option>
                                                    <option value="Buchas">Buchas</option>
                                                    <option value="Válvula de Pressão de Ar">Válvula de Pressão de Ar</option>
                                                    <option value="Sensores ABS">Sensores ABS</option>
                                                    <option value="Válvula de Distribuição">Válvula de Distribuição</option>
                                                    <option value="Freios">Freios</option>
                                                    <option value="Suspensão">Suspensão</option>
                                                    <option value="Soldas">Soldas</option>
                                                    <option value="Lanternagem">Lanternagem</option>
                                                    <option value="Serviços">Serviços</option>
                                                    <option value="Elétrica">Elétrica</option>
                                                    <option value="Graxas">Graxas</option>
                                                    <option value="Filtros">Filtros</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="medida"> Unidade de Medida </label>
                                                <select name="medida" id="medida" class="form-control">
                                                    <option value=""></option>
                                                    <option value="Litros">Litros</option>
                                                    <option value="Und">Und.</option>
                                                    <option value="Metro">Metro</option>
                                                    <option value="Kg">Kg</option>
                                                </select>
                                            </div>
                                        </div>    
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="analisar" class="btn btn-primary">Cadastrar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIM MODAL CADASTRO DE local de reparo-->
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">Descrição</th>
                                    <th scope="col" class="text-center text-nowrap">Categoria</th>
                                    <th scope="col" class="text-center text-nowrap">Medida</th>
                                    <th scope="col" class="text-center text-nowrap">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                if(isset($_POST['filtro']) && !empty($_POST['peca'])){
                                    $pecaServico = filter_input(INPUT_POST,'peca');
                                    $sql = $db->prepare("SELECT * FROM peca_reparo WHERE id_peca_reparo = :id");
                                    $sql->bindValue(':id', $pecaServico);
                                    $sql->execute();

                                    $totalPecas = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($pecaServico / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;
                                    
                                }else{
                                    $sql = $db->query("SELECT * FROM peca_reparo");

                                    $totalPecas = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($totalPecas / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                    $sql = $db->query("SELECT * FROM peca_reparo LIMIT $paginaInicial, $qtdPorPagina");
                                    
                                }
                                $dados = $sql->fetchAll();
                                foreach($dados as $dado):
                                ?>
                                <tr id="<?=$dado['id_peca_reparo']?>">
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['descricao'];?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['categoria'];?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['un_medida'];?>  </td>
                                    <td scope="col" class="text-center text-nowrap">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?=$dado['id_peca_reparo']; ?>" data-whatever="@mdo" value="<?=$dado['id_peca_reparo']; ?>" name="idPeca">Visualisar</button>
                                    </td>
                                </tr>
                                <!-- INICIO MODAL editar-->
                                <div class="modal fade" id="modal<?=$dado['id_peca_reparo']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Peça/Serviço</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="atualiza-peca.php" enctype="multipart/form-data" method="post">
                                                    <div class="form-row">
                                                        <input type="hidden" name="id" value="<?=$dado['id_peca_reparo']?>">
                                                        <div class="form-group espaco col-md-4">
                                                            <label for="descricao" >Descrição</label>
                                                            <input type="text" name="descricao" class="form-control" id="descricao" value="<?= $dado['descricao']; ?>">
                                                        </div>
                                                        <div class="form-group espaco col-md-4">
                                                            <label for="categoria" >Categoria</label>
                                                            <select name="categoria" id="categoria" class="form-control">
                                                                <option value="<?=$dado['categoria']?>"><?=$dado['categoria']?></option>
                                                                <option value="Terminais da Barra de Direção">Terminais da Barra de Direção</option>
                                                                <option value="Abraçadeira">Abraçadeira</option>
                                                                <option value="Molas">Molas</option>
                                                                <option value="Pino de Centro">Pino de Centro</option>
                                                                <option value="Rolamento">Rolamento</option>
                                                                <option value="Bicos Injetores">Bicos Injetores</option>
                                                                <option value="Motor de Partida">Motor de Partidada</option>
                                                                <option value="Pneu">Pneu</option>
                                                                <option value="Enbuchamentos">Enbuchamentos</option>
                                                                <option value="Alinhamento">Alinhamento</option>
                                                                <option value="Balanceamento">Balanceamento</option>
                                                                <option value="Buchas">Buchas</option>
                                                                <option value="Válvula de Pressão de Ar">Válvula de Pressão de Ar</option>
                                                                <option value="Sensores ABS">Sensores ABS</option>
                                                                <option value="Válvula de Distribuição">Válvula de Distribuição</option>
                                                                <option value="Freios">Freios</option>
                                                                <option value="Suspensão">Suspensão</option>
                                                                <option value="Soldas">Soldas</option>
                                                                <option value="Lanternagem">Lanternagem</option>
                                                                <option value="Serviços">Serviços</option>
                                                                <option value="Elétrica">Elétrica</option>
                                                                <option value="Graxas">Graxas</option>
                                                                <option value="Filtros">Filtros</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3 espaco">
                                                            <label for="medida"  >Medida</label>
                                                            <select name="medida" id="medida" class="form-control">
                                                                <option value="<?=$dado['un_medida']?>"><?=$dado['un_medida']?></option>
                                                                <option value="Litros">Litros</option>
                                                                <option value="Und">Und.</option>
                                                                <option value="Metro">Metro</option>
                                                                <option value="Kg">Kg</option>
                                                            </select>
                                                        </div>
                                                    </div>    
                                            </div>
                                            <div class="modal-footer">
                                                <a href="excluir-peca.php?id=<?=$dado['id_peca_reparo']; ?>" class="btn btn-danger" onclick="return confirm('Certeza que deseja excluir?')"> Excluir </a>
                                                <button type="submit" name="atualizar" class="btn btn-primary">Atualizar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIM MODAL editar -->  
                                <?php 
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                        $paginaAnterior = $pagina-1;
                        $paginaPosterior = $pagina+1;           
                    ?>
                    <nav aria-label="Navegação de página exemplo" class="paginacao">
                        <ul class="pagination">
                            <li class="page-item">
                            <?php
                                if($paginaAnterior!=0){
                                    echo "<a class='page-link' href='pecas.php?pagina=$paginaAnterior' aria-label='Anterior'>
                                    <span aria-hidden='true'>&laquo;</span>
                                    <span class='sr-only'>Anterior</span>
                                </a>";
                                }else{
                                    echo "<a class='page-link' aria-label='Anterior'> 
                                        <span aria-hidden='true'>&laquo;</span>
                                        <span class='sr-only'>Anterior</span>
                                    </a>";
                                }
                            ?>
                            
                            </li>
                            <?php
                                for($i=1;$i < $numPaginas+1;$i++){
                                    echo "<li class='page-item'><a class='page-link' href='pecas.php?pagina=$i'>$i</a></li>";
                                }
                            ?>
                            <li class="page-item">
                            <?php
                                if($paginaPosterior <= $numPaginas){
                                    echo " <a class='page-link' href='pecas.php?pagina=$paginaPosterior' aria-label='Próximo'>
                                    <span aria-hidden='true'>&raquo;</span>
                                    <span class='sr-only'>Próximo</span>
                                </a>";
                                }else{
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

        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script src="../assets/js/jquery.mask.js"></script>
        <script>
            $(document).ready(function() {
                $('#peca').select2();
            });
        </script>
    </body>
</html>