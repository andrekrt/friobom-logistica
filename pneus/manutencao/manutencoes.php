<?php 

session_start();
require("../../conexao.php");

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
        <title>Manuteções</title>
        <link rel="stylesheet" href="../../assets/css/style.css">
        <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../../assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../../assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="../../assets/favicon/site.webmanifest">
        <link rel="mask-icon" href="../../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    </head>
    <body>
        <div class="container-fluid corpo">
            <?php require('../../menu-lateral02.php') ?>
            <!-- Tela com os dados -->
            <div class="tela-principal">
                <div class="menu-superior">
                    <div class="icone-menu-superior">
                        <img src="../../assets/images/icones/pneu.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Manutenções</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
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

                                        $sql = $db->query("SELECT * FROM pneus WHERE uso = 1");
                                        if ($sql->rowCount() > 0):
                                            $dados = $sql->fetchAll();
                                            foreach ($dados as $dado):
                                        ?>
                                            <option value="<?=$dado['idpneus']?>"><?=$dado['num_fogo']?></option>
                                        <?php
                                            endforeach;
                                        endif;

                                        ?>
                                    </select>
                                    <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                                </div>
                            </div>
                        </form>
                        <a href="manutencoes-xls.php"><img src="../../assets/images/excel.jpg" alt=""></a>
                    </div>
                    <!-- fim filtro por veiculo -->
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">Pneu</th>
                                    <th scope="col" class="text-center text-nowrap">Data da Manutenção</th>
                                    <th scope="col" class="text-center text-nowrap">Tipo de Manutenção</th>
                                    <th scope="col" class="text-center text-nowrap">Km Veículo</th>
                                    <th scope="col" class="text-center text-nowrap">Km Pneu</th>
                                    <th scope="col" class="text-center text-nowrap">Valor</th>
                                    <th scope="col" class="text-center text-nowrap">NF</th>
                                    <th scope="col" class="text-center text-nowrap">Fornecedor</th>
                                    <th scope="col" class="text-center text-nowrap">Suco 01</th>
                                    <th scope="col" class="text-center text-nowrap">Suco 02</th>
                                    <th scope="col" class="text-center text-nowrap">Suco 03</th>
                                    <th scope="col" class="text-center text-nowrap">Suco 04</th>
                                    <th scope="col" class="text-center text-nowrap">Suco 04</th>
                                    <th scope="col" class="text-center text-nowrap">Lançado por</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                if(isset($_POST['filtro']) && !empty($_POST['pneu'])){

                                    $pneu = filter_input(INPUT_POST, 'pneu');
                                    $sql = $db->prepare("SELECT * FROM manutencao_pneu LEFT JOIN pneus ON manutencao_pneu.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON manutencao_pneu.usuario = usuarios.idusuarios WHERE pneus_idpneus = :pneu");
                                    $sql->bindValue(':pneu', $pneu);
                                    $sql->execute();

                                    $total = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($total / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                   
                                }else{

                                    $sql = $db->query("SELECT * FROM manutencao_pneu LEFT JOIN pneus ON manutencao_pneu.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON manutencao_pneu.usuario = usuarios.idusuarios");

                                    $total = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($total / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                    $sql = $db->query("SELECT * FROM manutencao_pneu LEFT JOIN pneus ON manutencao_pneu.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON manutencao_pneu.usuario = usuarios.idusuarios LIMIT $paginaInicial,$qtdPorPagina");
                                    
                                }

                                $dados = $sql->fetchAll();
                                foreach($dados as $dado):

                                ?>
                                <tr id="<?=$dado['idmanutencao_pneu']?>">
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['num_fogo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?= date("d/m/Y ",strtotime( $dado['data_manutencao'])); ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['tipo_manutencao']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['km_veiculo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['km_pneu']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?="R$ ". str_replace(".", ",",$dado['valor']) ; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['num_nf']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['fornecedor']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['suco01']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['suco02']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['suco03']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['suco04']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['usuario']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle" >
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?=$dado['idmanutencao_pneu']; ?>" data-whatever="@mdo" value="<?=$dado['idmanutencao_pneu']; ?>" name="token"> Visualisar </button>       
                                    </td>
                                </tr>
                                <!-- INICIO MODAL Editar-->
                                <div class="modal fade" id="modal<?=$dado['idmanutencao_pneu'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Manutenção</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="atualiza-manutencao.php" method="post">
                                                    <input type="hidden" name="idmanutencao" value="<?=$dado['idmanutencao_pneu']?>">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-2">
                                                            <label for="dataManutencao">Data de Medição</label>
                                                            <input type="date" name="dataManutencao" id="dataManutencao" readonly class="form-control" value="<?=date("Y-m-d", strtotime($dado['data_manutencao']))?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="tipoManutencao">Tipo de Reparo</label>
                                                            <input type="text" name="tipoManutencao" id="tipoManutencao" required class="form-control" value="<?=$dado['tipo_manutencao']?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="nFogo">Nº Fogo</label>
                                                            <select required name="pneu" id="pneu" class="form-control">
                                                                <option value="<?=$dado['pneus_idpneus']?>"><?=$dado['num_fogo']?></option>
                                                                <?php
                                                                $sql=$db->query("SELECT * FROM pneus");
                                                                $pneus = $sql->fetchAll();
                                                                foreach($pneus as $pneu):
                                                                ?>
                                                                <option value="<?=$pneu['idpneus'] ?>"><?=$pneu['num_fogo']?></option>
                                                                <?php
                                                                endforeach;
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="medida">Medida</label>
                                                            <input type="text" name="medida" id="medida" readonly class="form-control" value="<?=$dado['medida']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="marca">Marca</label>
                                                            <input type="text" name="marca" id="marca" readonly class="form-control" value="<?=$dado['marca']?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="modelo">Modelo</label>
                                                            <input type="text" name="modelo" id="modelo" readonly class="form-control" value="<?=$dado['modelo']?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="kmVeiculo">Km Veículo</label>
                                                            <input type="text" name="kmVeiculo" id="kmVeiculo" required class="form-control" value="<?=$dado['km_veiculo']?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="kmPneu">Km Pneu</label>
                                                            <input type="text" name="kmPneu" id="kmPneu" readonly class="form-control" value="<?=$dado['km_pneu']?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-1">
                                                            <label for="valor">Valor (R$)</label>
                                                            <input type="text" name="valor" id="valor" required class="form-control" value="<?=str_replace(".",",",$dado['valor']) ?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="nf">Nº Nf</label>
                                                            <input type="text" name="nf" id="nf" required class="form-control" value="<?=$dado['num_nf']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="fornecedor">Fornecedor</label>
                                                            <input type="text" name="fornecedor" id="fornecedor" required class="form-control" value="<?=$dado['fornecedor']?>">
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
                                                            <input type="text" required name="suco03" class="form-control" id="suco03" value="<?=$dado['suco03']?>">
                                                        </div>
                                                        <div class="form-group col-md-1 ">
                                                            <label for="suco04">Suco 04</label>
                                                            <input type="text" required name="suco04" class="form-control" id="suco04" value="<?=$dado['suco04']?>">
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Atualizar  </button>
                                                <a href="excluir-manutencao?idmanutencao=<?=$dado['idmanutencao_pneu']?>" class="btn btn-danger" onclick="return confirm('Deseja Excluir?')">Excluir</a>
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

        <script src="../../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../../assets/js/menu.js"></script>
        <script src="../../assets/js/jquery.mask.js"></script>
        <script>
            $(document).ready(function() {
                $('#pneu').select2();
            });
            
        </script>
    </body>
</html>