<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] == 3 || $_SESSION['tipoUsuario'] == 4 || $_SESSION['tipoUsuario'] == 99){

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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pneus</title>
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
            <?php require('../menu-lateral.php') ?>
            <!-- Tela com os dados -->
            <div class="tela-principal">
                <div class="menu-superior">
                    <div class="icone-menu-superior">
                        <img src="../assets/images/icones/pneu.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Pneus</h2>
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
                                    <select name="pneu" id="pneu" class="form-control">
                                        <option></option>
                                        <?php

                                        $sql = $db->query("SELECT num_fogo FROM pneus ORDER BY num_fogo ASC");
                                        if ($sql->rowCount() > 0):
                                            $dados = $sql->fetchAll();
                                            foreach ($dados as $dado) :
                                        ?>
                                            <option value="<?=$dado['num_fogo']?>"><?=$dado['num_fogo']?></option>
                                        <?php        
                                            endforeach;
                                        endif;

                                        ?>
                                    </select>
                                    <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                                </div>
                            </div>
                        </form>
                        <a href="pneus-xls"><img src="../assets/images/excel.jpg" alt=""></a>
                    </div>
                    <!-- fim filtro por veiculo -->
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">Nº Fogo</th>
                                    <th scope="col" class="text-center text-nowrap">Cadastrado</th>
                                    <th scope="col" class="text-center text-nowrap">Medida</th>
                                    <th scope="col" class="text-center text-nowrap">Calibragem Máxima</th>
                                    <th scope="col" class="text-center text-nowrap">Marca</th>
                                    <th scope="col" class="text-center text-nowrap">Modelo</th>
                                    <th scope="col" class="text-center text-nowrap">Nº Série</th>
                                    <th scope="col" class="text-center text-nowrap">Vida</th>
                                    <th scope="col" class="text-center text-nowrap">Posição Início</th>
                                    <th scope="col" class="text-center text-nowrap">Veículo</th>
                                    <th scope="col" class="text-center text-nowrap">Km Rodado</th>
                                    <th scope="col" class="text-center text-nowrap">Situação</th>
                                    <th scope="col" class="text-center text-nowrap">Localização</th>
                                    <th scope="col" class="text-center text-nowrap">Suco 01</th>
                                    <th scope="col" class="text-center text-nowrap">Suco 02</th>
                                    <th scope="col" class="text-center text-nowrap">Suco 03</th>
                                    <th scope="col" class="text-center text-nowrap">Suco 04</th>
                                    <th scope="col" class="text-center text-nowrap">Lançado por</th>
                                    <th scope="col" class="text-center text-nowrap">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                if(isset($_POST['filtro']) && !empty($_POST['pneu'])){

                                    $pneu = filter_input(INPUT_POST, 'pneu');
                                    $sql = $db->prepare("SELECT * FROM pneus LEFT JOIN usuarios ON pneus.usuario = usuarios.idusuarios WHERE uso = 1 AND num_fogo = :pneu");
                                    $sql->bindValue(':pneu', $pneu);
                                    $sql->execute();

                                    $total = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($total / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                   
                                }else{

                                    $sql = $db->query("SELECT * FROM pneus LEFT JOIN usuarios ON pneus.usuario = usuarios.idusuarios WHERE uso = 1");

                                    $total = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($total / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                    $sql = $db->query("SELECT * FROM pneus LEFT JOIN usuarios ON pneus.usuario = usuarios.idusuarios WHERE uso = 1 LIMIT $paginaInicial,$qtdPorPagina");
                                    
                                }

                                $dados = $sql->fetchAll();
                                foreach($dados as $dado):

                                ?>
                                <tr id="<?=$dado['idpneus']?>">
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['num_fogo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?= date("d/m/Y H:i",strtotime( $dado['data_cadastro'])); ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['medida']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['calibragem_maxima']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['marca']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['modelo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['num_serie']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['vida']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['posicao_inicio']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['veiculo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['km_rodado']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['situacao']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['localizacao']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['suco01']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['suco02']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['suco03']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['suco04']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['nome_usuario']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle" >
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?=$dado['idpneus']; ?>" data-whatever="@mdo" value="<?=$dado['idpneus']; ?>" name="token"> Visualisar </button>    
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#desativar<?=$dado['idpneus']; ?>" data-whatever="@mdo" value="<?=$dado['idpneus']; ?>" name="token"> Desativar </button>   
                                    </td>
                                </tr>
                                <!-- INICIO MODAL Editar-->
                                <div class="modal fade" id="modal<?=$dado['idpneus'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Pneu</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="atualiza-pneu.php" method="post">
                                                    <input type="hidden" name="idpneu" value="<?=$dado['idpneus']?>">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-3">
                                                            <label for="dataCadastro">Data do Cadastro</label>
                                                            <input type="datetime-local" name="dataCadastro" id="dataCadastro" readonly class="form-control" value="<?=date("Y-m-d\TH:i", strtotime($dado['data_cadastro']))?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="nFogo">Nº Fogo</label>
                                                            <input type="text" name="nFogo" id="nFogo" class="form-control" value="<?=$dado['num_fogo']?>">
                                                        </div>
                                                        <div class="form-group col-md-2 ">
                                                            <label for="medida">Medida</label>
                                                            <input type="text" name="medida" id="medida" class="form-control" required value="<?=$dado['medida']?>">
                                                        </div>
                                                        <div class="form-group col-md-2 ">
                                                            <label for="calibMax">Calibragem Máxima</label>
                                                            <input type="text" required name="calibMax" id="calibMax" class="form-control" value="<?=$dado['calibragem_maxima']?>">
                                                        </div>
                                                        <div class="form-group col-md-2  ">
                                                            <label for="marca">Marca</label>
                                                            <input type="text" required name="marca" id="marca" class="form-control" value="<?=$dado['marca']?>">
                                                        </div>
                                                        <div class="form-group col-md-2  ">
                                                            <label for="modelo">Modelo</label>
                                                            <input type="text" required name="modelo" id="modelo" class="form-control" value="<?=$dado['modelo']?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-2  ">
                                                            <label for="nSerie">Nº Série</label>
                                                            <input type="text" required name="nSerie" id="nSerie" class="form-control" value="<?=$dado['num_serie']?>">
                                                        </div>
                                                        <div class="form-group col-md-1  ">
                                                            <label for="vida">Vida </label>
                                                            <input type="text" required name="vida" id="vida" class="form-control" value="<?=$dado['vida']?>">
                                                        </div>
                                                        <div class="form-group col-md-2 ">
                                                            <label for="posicao">Posição Início</label>
                                                            <input type="text" required name="posicao" class="form-control" id="posicao" value="<?=$dado['posicao_inicio']?>">
                                                        </div>
                                                        <div class="form-group col-md-2 ">
                                                            <label for="veiculo">Veículo</label>
                                                            <select class="form-control" name="veiculo" required id="veiculo">
                                                                <option value="<?=$dado['veiculo']?>"><?=$dado['veiculo']?></option>
                                                                <?php
                                                                $sql = $db->query("SELECT placa_veiculo FROM veiculos ORDER BY placa_veiculo ASC");
                                                                $dados = $sql->fetchAll();
                                                                foreach ($dados as $veiculo):
                                                                ?>
                                                                <option value=<?=$veiculo['placa_veiculo']?>><?= $veiculo['placa_veiculo']?>  </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="kmVeiculo">Km Veículo</label>
                                                            <input type="text" required name="kmVeiculo" class="form-control" id="kmVeiculo" value="<?=$dado['km_inicial']?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="kmRodado">Km Rodado</label>
                                                            <input type="text" required name="kmRodado" class="form-control" readonly id="kmRodado" value="<?=$dado['km_rodado']?>">
                                                        </div>
                                                        <div class="form-group col-md-2 ">
                                                            <label for="situacao">Situação</label>
                                                            <input type="text" required name="situacao" class="form-control" id="situacao" value="<?=$dado['situacao']?>">
                                                        </div>                                                
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-2 ">
                                                            <label for="localizacao">Localização</label>
                                                            <input type="text" required name="localizacao" class="form-control" id="localizacao" value="<?=$dado['localizacao']?>">
                                                        </div>
                                                        <div class="form-group col-md-1 ">
                                                            <label for="suco01">Suco 01</label>
                                                            <input type="text" required name="suco01" class="form-control" id="suco01" value="<?=$dado['suco01']?>">
                                                        </div>
                                                        <div class="form-group col-md-1 ">
                                                            <label for="suco02">Suco 02</label>
                                                            <input type="text" required name="suco02" class="form-control" id="suco02" value="<?=$dado['suco02']?>">
                                                        </div>
                                                        <div class="form-group col-md-1 ">
                                                            <label for="suco03">Suco 03</label>
                                                            <input type="text" required name="suco03" class="form-control" id="suco03" value="<?=$dado['suco03']?>" >
                                                        </div>
                                                        <div class="form-group col-md-1 ">
                                                            <label for="suco04">Suco 04</label>
                                                            <input type="text" required name="suco04" class="form-control" id="suco04" value="<?=$dado['suco04']?>">
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Atualizar  </button>
                                                </form>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIM MODAL --> 

                                <!-- INICIO MODAL Desativar-->
                                <div class="modal fade" id="desativar<?=$dado['idpneus'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Desativar Pneu</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="desativar-pneu.php" method="post">
                                                    <input type="hidden" name="idpneu" value="<?=$dado['idpneus']?>">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-3">
                                                            <label for="kmFinal">Km final do Veículo</label>
                                                            <input type="text" name="kmFinal" id="kmFinal" required class="form-control" >
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-danger">Desativar  </button>
                                                </form>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIM MODAL --> 
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
                                    echo "<a class='page-link' href='solicitacoes.php?pagina=$paginaAnterior' aria-label='Anterior'>
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
                                    echo "<li class='page-item'><a class='page-link' href='solicitacoes.php?pagina=$i'>$i</a></li>";
                                }
                            ?>
                            <li class="page-item">
                            <?php
                                if($paginaPosterior <= $numPaginas){
                                    echo " <a class='page-link' href='solicitacoes.php?pagina=$paginaPosterior' aria-label='Próximo'>
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
        <script>
            $(document).ready(function() {
                $('#pneu').select2();
            });
        </script>
    </body>
</html>