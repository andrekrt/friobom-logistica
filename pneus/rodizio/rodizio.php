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
        <title>Rodízio</title>
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
                        <h2>Rodízios</h2>
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

                                        $sql = $db->query("SELECT num_fogo FROM pneus ORDER BY num_fogo ASC");
                                        if ($sql->rowCount() > 0):
                                            $dados = $sql->fetchAll();
                                            foreach ($dados as $dado):
                                        ?>
                                            <option value='<?=$dado['num_fogo']?>'><?= $dado['num_fogo'] ?> </option>";
                                        <?php   
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                                </div>
                            </div>
                        </form>
                        <a href="rodizio-xls.php"><img src="../../assets/images/excel.jpg" alt=""></a>
                    </div>
                    <!-- fim filtro por veiculo -->
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">Pneu</th>
                                    <th scope="col" class="text-center text-nowrap">Data Rodízio</th>
                                    <th scope="col" class="text-center text-nowrap">Veículo Anterior</th>
                                    <th scope="col" class="text-center text-nowrap">Km Inicial do Veículo Anterior</th>
                                    <th scope="col" class="text-center text-nowrap">Km Final do Veículo Anterior</th>
                                    <th scope="col" class="text-center text-nowrap">Km Rodado Veículo Anterior</th>
                                    <th scope="col" class="text-center text-nowrap">Veículo Atual</th>
                                    <th scope="col" class="text-center text-nowrap">Km Inicial Veículo Atual</th>
                                    <th scope="col" class="text-center text-nowrap">Lançado por</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                if(isset($_POST['filtro']) && !empty($_POST['pneu'])){

                                    $pneu = filter_input(INPUT_POST, 'pneu');
                                    $sql = $db->prepare("SELECT * FROM rodizio_pneu LEFT JOIN pneus ON rodizio_pneu.pneu = pneus.idpneus LEFT JOIN usuarios ON rodizio_pneu.usuario = usuarios.idusuarios WHERE num_fogo = :pneu");
                                    $sql->bindValue(':pneu', $pneu);
                                    $sql->execute();

                                    $total = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($total / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                   
                                }else{

                                    $sql = $db->query("SELECT * FROM rodizio_pneu LEFT JOIN pneus ON rodizio_pneu.pneu = pneus.idpneus LEFT JOIN usuarios ON rodizio_pneu.usuario = usuarios.idusuarios");

                                    $total = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($total / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                    $sql = $db->query("SELECT * FROM rodizio_pneu LEFT JOIN pneus ON rodizio_pneu.pneu = pneus.idpneus LEFT JOIN usuarios ON rodizio_pneu.usuario = usuarios.idusuarios LIMIT $paginaInicial,$qtdPorPagina");
                                    
                                }

                                $dados = $sql->fetchAll();
                                foreach($dados as $dado):

                                ?>
                                <tr id="<?=$dado['idrodizio']?>">
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['num_fogo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?= date("d/m/Y H:i",strtotime( $dado['data_rodizio'])); ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['veiculo_anterior']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['km_inicial_veiculo_anterior']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['km_final_veiculo_anterior']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['km_rodado_veiculo_anterior']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['novo_veiculo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['km_inicial_novo_veiculo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['nome_usuario']; ?> </td>
                                    
                                    
                                </tr>
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
        <script>
            $(document).ready(function() {
                $('#veiculo_filtrado').select2();
            });
        </script>
    </body>
</html>