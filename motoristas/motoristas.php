<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==1 || $_SESSION['tipoUsuario'] == 99 || $_SESSION['tipoUsuario'] == 4){

    $nomeUsuario = $_SESSION['nomeUsuario'];

    $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM motoristas WHERE ativo = 1");
    
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
        <title>Motoristas</title>
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
                        <img src="../assets/images/icones/motoristas.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Motoristas Cadastrados</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <div class="filtro">
                        <form class="form-inline" action="" method="post">
                            <div class="form-row">
                                <select name="motorista" id="motorista" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    
                                    $consulta = $db->query("SELECT cod_interno_motorista, nome_motorista FROM motoristas WHERE ativo = 1");
                                    $dados = $consulta->fetchAll();
                                    foreach($dados as $dado){
                                    ?>
                                    <option value="<?php echo $dado['cod_interno_motorista'] ?>"><?php echo $dado['nome_motorista'] ?></option>    
                                    <?php    
                                    }

                                    ?>
                                </select>
                                <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                            </div>
                        </form>
                        <a href="motoristas-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                    </div>
                    <div class="table-reponsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center"> Código Motorista </th>
                                    <th scope="col" class="text-center"> Nome Motorista </th>
                                    <th scope="col" class="text-center"> CNH </th>
                                    <th scope="col" class="text-center"> Vencimento CNH </th>
                                    <th scope="col" class="text-center"> Toxicológico </th>
                                    <th scope="col" class="text-center"> Validade Toxicológico </th>
                                    <th scope="col" class="text-center">Salário</th>
                                    <th scope="col" class="text-center"> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(isset($_POST['filtro']) && !empty($_POST['motorista'])) {
                                    $motorista = filter_input(INPUT_POST, 'motorista');
                                    $sql = $db->prepare("SELECT * FROM motoristas WHERE ativo = 1 AND cod_interno_motorista = :codMotorista ");
                                    $sql->bindValue(':codMotorista', $motorista);
                                    $sql->execute();

                                    $totalMotorista = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($totalMotorista/$qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina*$pagina)-$qtdPorPagina;

                                }else{
                                    $sql = $db->query("SELECT * FROM motoristas WHERE ativo = 1 ");

                                    $totalMotorista = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($totalMotorista/$qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina*$pagina)-$qtdPorPagina;

                                    $sql= $db->query("SELECT * FROM motoristas WHERE ativo = 1 ORDER BY nome_motorista ASC LIMIT $paginaInicial, $qtdPorPagina");

                                }
                                $dados = $sql->fetchAll();
                                foreach($dados as $dado):
                                ?>
                                    <tr>
                                        <td scope="col" class="text-center"> <?php echo $dado['cod_interno_motorista']; ?> </td>
                                        <td scope="col" class="text-center"> <?php echo $dado['nome_motorista']; ?> </td>
                                        <td scope="col" class="text-center"> <?php echo $dado['cnh']; ?> </td>
                                        <td scope="col" class="text-center"> <?php echo date("d/m/Y", strtotime($dado['validade_cnh'])) ; ?> </td>
                                        <td scope="col" class="text-center"> <?php echo $dado['toxicologico']; ?> </td>
                                        <td scope="col" class="text-center"> <?php echo date("d/m/Y", strtotime($dado['validade_toxicologico'])); ?> </td>
                                        <td scope="col" class="text-center"> <?php echo "R$ ". number_format($dado['salario'],2,",", "." )?> </td>
                                        <td scope="col" class="text-center">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?php echo $dado['cod_interno_motorista']; ?>" data-whatever="@mdo" value="<?php echo $dado['cod_interno_motorista']; ?>" name="idSolic" >Visualisar</button>
                                        </td>
                                    </tr>
                                    <!-- INICIO MODAL -->
                                    <div class="modal fade" id="modal<?php echo $dado['cod_interno_motorista']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Motorista</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="atualiza.php" method="post">
                                                        <div class="form-row">
                                                            <input type="hidden" name="idSolicitacao" value="<?php echo $dado['cod_interno_motorista']; ?>" >
                                                            <div class="form-group col-md-12">
                                                                <label for="codMotorista" class="col-form-label">Código Motorista</label>
                                                                <input type="text" name="codMotorista" class="form-control"  id="codMotorista" Readonly value="<?php echo $dado['cod_interno_motorista'];  ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label for="nomeMotorista" class="col-form-label">Nome Motorista</label>
                                                                <input type="text" class="form-control" name="nomeMotorista"  id="nomeMotorista" value="<?php echo $dado['nome_motorista'] ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-5">
                                                                <label for="cnh" class="col-form-label">CNH</label>
                                                                <input type="text" class="form-control" name="cnh" id="cnh" value="<?php echo $dado['cnh'] ?>">
                                                            </div>
                                                            <div class="form-group col-md-5">
                                                                <label for="vencimentoCnh" class="col-form-label">Vencimento CNH</label>
                                                                <input type="date" class="form-control" name="vencimentoCnh" id="vencimentoCnh" value="<?php echo  $dado['validade_cnh']; ?>">
                                                            </div>
                                                        </div>   
                                                        <div class="form-row">
                                                            <div class="form-group col-md-5">
                                                                <label for="'toxicologico'" class="col-form-label">Toxicológico</label>
                                                                <select name="toxicologico" id="toxicologico" class="form-control">
                                                                    <option value="<?=$dado['toxicologico']?>"><?=$dado['toxicologico']?></option>
                                                                    <option value="Aguardando">Aguardando</option>
                                                                    <option value="OK">OK</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-5">
                                                                <label for="vencimentoToxicologico" class="col-form-label">Vencimento Toxicológico</label>
                                                                <input type="date" class="form-control" name="vencimentoToxicologico" id="vencimentoToxicologico" value="<?php echo  $dado['validade_toxicologico']; ?>">
                                                            </div>
                                                        </div>  
                                                        <div class="form-row">
                                                            <div class="form-group form-check">
                                                                <input type="checkbox" class="form-check-input" id="ativo" name="ativo">
                                                                <label for="ativo">Desativar</label>
                                                            </div>
                                                        </div>    
                                                </div>
                                                <div class="modal-footer">
                                                        <a href="excluir.php?codMotorista=<?php echo $dado['cod_interno_motorista']; ?>" class="btn btn-danger" > Excluir </a>
                                                        <button type="submit" name="analisar" class="btn btn-primary">Atualizar</button>
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
                                    echo "<a class='page-link' href='motoristas.php?pagina=$paginaAnterior' aria-label='Anterior'>
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
                                    echo "<li class='page-item'><a class='page-link' href='motoristas.php?pagina=$i'>$i</a></li>";
                                }
                            ?>
                            <li class="page-item">
                            <?php
                                if($paginaPosterior <= $numPaginas){
                                    echo " <a class='page-link' href='motoristas.php?pagina=$paginaPosterior' aria-label='Próximo'>
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

        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
    </body>
</html>