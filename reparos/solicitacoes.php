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
        <title>Solicitações</title>
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
                        <img src="../assets/images/icones/reparos.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Solicitações Realizadas</h2>
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
                                    <select name="veiculo_filtrado" id="veiculo_filtrado" class="form-control">
                                        <option></option>
                                        <option value="Estoque">Estoque</option>
                                        <option value="Serviços">Serviços</option>
                                        <option value="Oficina">Oficina</option>
                                        <?php

                                        $sql = $db->query("SELECT placa_veiculo FROM veiculos ORDER BY placa_veiculo ASC");
                                        if ($sql->rowCount() > 0) {
                                            $dados = $sql->fetchAll();
                                            foreach ($dados as $dado) {
                                                echo "<option value='$dado[placa_veiculo]'>" . $dado['placa_veiculo'] . "</option>";
                                            }
                                        }

                                        ?>
                                    </select>
                                    <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                                </div>
                            </div>
                        </form>
                        <a href="solicitacoes-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                    </div>
                    <!-- fim filtro por veiculo -->
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">ID</th>
                                    <th scope="col" class="text-center text-nowrap">Data Solicitação</th>
                                    <th scope="col" class="text-center text-nowrap">Veículo</th>
                                    <th scope="col" class="text-center text-nowrap">Motorista</th>
                                    <th scope="col" class="text-center text-nowrap">Rota</th>
                                    <th scope="col" class="text-center text-nowrap">Problema</th>
                                    <th scope="col" class="text-center text-nowrap">Local Reparo</th>
                                    <th scope="col" class="text-center text-nowrap">Tipos Peça/Serviço</th>
                                    <th scope="col" class="text-center text-nowrap">Qtd</th>
                                    <th scope="col" class="text-center text-nowrap">Valor Total</th>
                                    <th scope="col" class="text-center text-nowrap">Situação</th>
                                    <th scope="col" class="text-center text-nowrap">Solicitante</th>
                                    <th scope="col" class="text-center text-nowrap">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                if(isset($_POST['filtro']) && !empty($_POST['veiculo_filtrado'])){

                                    $veiculo = filter_input(INPUT_POST, 'veiculo_filtrado');
                                    $sql = $db->prepare("SELECT *, COUNT(peca_servico) as peca, SUM(qtd) as qtd, GROUP_CONCAT('R$ ', vl_unit) as vlUnit, SUM(vl_total) as vlTotal FROM `solicitacoes_new` LEFT JOIN peca_reparo ON solicitacoes_new.peca_servico = peca_reparo.id_peca_reparo LEFT JOIN usuarios ON solicitacoes_new.usuario = usuarios.idusuarios WHERE placa = :placa GROUP BY token ORDER BY token DESC");
                                    $sql->bindValue(':placa', $veiculo);
                                    $sql->execute();

                                    $total = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($total / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                   
                                }else{

                                    $sql = $db->query("SELECT *, COUNT(peca_servico) as peca, SUM(qtd) as qtd, GROUP_CONCAT('R$ ', vl_unit) as vlUnit, SUM(vl_total) as vlTotal FROM `solicitacoes_new` LEFT JOIN peca_reparo ON solicitacoes_new.peca_servico = peca_reparo.id_peca_reparo LEFT JOIN usuarios ON solicitacoes_new.usuario = usuarios.idusuarios GROUP BY token ORDER BY token DESC");

                                    $total = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($total / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                    $sql = $db->query("SELECT *, COUNT(peca_servico) as peca, SUM(qtd) as qtd, GROUP_CONCAT('R$ ', vl_unit) as vlUnit, SUM(vl_total) as vlTotal FROM `solicitacoes_new` LEFT JOIN peca_reparo ON solicitacoes_new.peca_servico = peca_reparo.id_peca_reparo LEFT JOIN usuarios ON solicitacoes_new.usuario = usuarios.idusuarios GROUP BY token ORDER BY token DESC LIMIT $paginaInicial,$qtdPorPagina");
                                    
                                }

                                $dados = $sql->fetchAll();
                                foreach($dados as $dado):

                                ?>
                                <tr id="<?=$dado['token']?>">
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['token']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?= date("d/m/Y",strtotime( $dado['data_atual'])); ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['placa']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['motorista']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['rota']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['problema']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['local_reparo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?= str_replace(",", "<br>",$dado['peca'] ) ; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?= str_replace(".", ",",str_replace(",", "<br>",$dado['qtd']))  ; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?="R$ ". str_replace(".", ",",str_replace(',','<br>',$dado['vlTotal']) )  ; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['situacao']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['nome_usuario']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle" >
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?=$dado['token']; ?>" data-whatever="@mdo" value="<?=$dado['token']; ?>" name="token"> Visualisar </button>       
                                    </td>
                                </tr>
                                <!-- INICIO MODAL Editar-->
                                <div class="modal fade" id="modal<?=$dado['token'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Peças/Serviços</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="atualiza-new.php" method="post">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-1">
                                                            <label for="token" class="col-form-label">ID</label>
                                                            <input type="text" readonly class="form-control" name="token" id="token" value="<?=$dado['token']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="placa" class="col-form-label">Placa</label>
                                                            <select name="veiculo" required id="veiculo" class="form-control">
                                                                <option value="<?=$dado['placa']?>"><?=$dado['placa']?></option>
                                                                <option value="Estoque" >Estoque</option>
                                                                <?php

                                                                $veiculos = $db->query("SELECT placa_veiculo FROM veiculos ORDER BY placa_veiculo ASC");
                                                                if ($veiculos->rowCount() > 0) {
                                                                    $dados = $sql->fetchAll();
                                                                    foreach ($veiculos as $veiculo) {
                                                                        echo "<option value='$veiculo[placa_veiculo]'>" . $veiculo['placa_veiculo'] . "</option>";
                                                                    }
                                                                }

                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="motorista" class="col-form-label">Motorista</label>
                                                            <select name="motorista" id="motorista" class="form-control">
                                                                <option value="<?=$dado['motorista']?>"><?=$dado['motorista']?></option>
                                                                <?php

                                                                $sql = $db->query("SELECT * FROM motoristas");
                                                                $motoristas = $sql->fetchAll();
                                                                foreach ($motoristas as $motorista):

                                                                ?>
                                                                    <option value="<?=$motorista['nome_motorista'] ?>"><?=$motorista['nome_motorista'] ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="rota" class="col-form-label">Rota</label>
                                                            <select name="rota" id="rota" class="form-control">
                                                                <option value="<?=$dado['rota']?>"><?=$dado['rota']?></option>
                                                                <?php

                                                                $sql = $db->query("SELECT * FROM rotas");
                                                                $rotas = $sql->fetchAll();
                                                                foreach ($rotas as $rota):

                                                                ?>
                                                                    <option value="<?=$rota['nome_rota'] ?>"><?=$rota['nome_rota'] ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label class="col-form-label" for="descricao">Descrição do Problema</label>
                                                            <input type="text" required value="<?=$dado['problema']?>" name="descricao" class="form-control" id="descricao">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="localReparo">Local Reparo</label>
                                                            <select name="localReparo" class="form-control" id="localReparo">
                                                                <option value="<?=$dado['local_reparo']?>"><?=$dado['local_reparo']?></option>
                                                                <?php

                                                                $sql = $db->query("SELECT * FROM local_reparo");
                                                                $categorias = $sql->fetchAll();
                                                                foreach ($categorias as $categoria):
                                                                ?>
                                                                    <option value="<?=$categoria['nome_local'] ?>"><?=$categoria['nome_local'] ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php 
                                                $solicitacoes = $db->prepare("SELECT * FROM solicitacoes_new LEFT JOIN peca_reparo ON solicitacoes_new.peca_servico = peca_reparo.id_peca_reparo WHERE token = :token ");
                                                $solicitacoes->bindValue(':token', $dado['token']);
                                                $solicitacoes->execute();
                                                $solicitacoes=$solicitacoes->fetchAll();
                                                foreach($solicitacoes as $solicitacao):
                                                ?>
                                                    <div class="form-row">
                                                        <input type="hidden" name="id[]" value="<?=$solicitacao['id']?>">
                                                        <div class="form-group col-md-4">
                                                            <label for="peca" class="col-form-label">Peça/Serviço</label>
                                                            <select name="peca[]" class="form-control" id="peca">
                                                                <option value="<?=$solicitacao['peca_servico']?>"> <?=$solicitacao['peca_servico']?> - <?=$solicitacao['descricao']?> </option>
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
                                                        <div class="form-group col-md-1">
                                                            <label for="qtd" class="col-form-label">Qtd</label>
                                                            <input type="text" class="form-control" name="qtd[]" id="qtd" value="<?=$solicitacao['qtd']?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="medida" class="col-form-label">Medida</label>
                                                            <input type="text" readonly class="form-control" id="medida" name="medida" value="<?=$solicitacao['un_medida']?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="vlUnit" class="col-form-label">Valor Unit.</label>
                                                            <input type="text" class="form-control" name="vlUnit[]" id="vlUnit" value="<?=$solicitacao['vl_unit']?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="vlTotal" class="col-form-label">Valor Total</label>
                                                            <input type="text" readonly class="form-control" name="vlTotal[]" id="vlTotal" value="<?=$solicitacao['vl_total']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="anexo" class="col-form-label">Anexos</label>
                                                            <?php if(empty($solicitacao['imagem'])==false): ?>
                                                                <a target="_blank" href="uploads/<?=$solicitacao['imagem']?>" class="form-control" >Anexo</a>
                                                            <?php else: ?>
                                                                <input type="text" class="form-control" value="Sem Anexo">
                                                            <?php endif; ?>
                                                        </div>
                                                        <div style="margin-left: 0; margin-top: 37px;">
                                                            <a href="excluir-peca-solicitacao.php?idSolic=<?=$solicitacao['id']?>"  class="btn btn-danger"> Excluir </a>
                                                        </div>
                                                            
                                                        
                                                    </div>
                                                <?php
                                                endforeach;
                                                if($tipoUsuario==4):
                                                ?>
                                                <div class="form-row">
                                                    <div class="form-group col-md-2">
                                                        <label for="situacao" class="col-form-label">Situação</label>
                                                        <select class="form-control" name="situacao" id="situacao">
                                                            <option value="<?=$dado['situacao']?>"><?=$dado['situacao']?></option>
                                                            <option value="Reprovado">Reprovado</option>
                                                            <option value="Aprovado">Aprovado</option>
                                                            <option value="Em análise"> Em Análise</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-10">
                                                        <label for="obs" class="col-form-label">Observações</label>
                                                        <input type="text" id="obs" name="obs" class="form-control">
                                                    </div>
                                                </div>
                                                <?php
                                                endif;
                                                ?>
                                                    
                                            </div>
                                            <div class="modal-footer">
                                                <?php if($dado['situacao']!="Aprovado"): ?>
                                                    <a href="excluir.php?token=<?=$dado['token']; ?>" class="btn btn-danger" onclick="return confirm('Confirmar Exclusão?');"> Excluir </a>
                                                    <button type="submit" name="analisar" class="btn btn-primary">Atualizar</button>
                                                <?php endif; ?>
                                                </form>
                                                <?php if($dado['situacao']!="Aprovado" && ($tipoUsuario==99 || $tipoUsuario==3))  : ?>
                                                    <a class="btn btn-success" href="solicitacao-adicional.php?token=<?=$dado['token']?>" >Adiconar Peças/Serviço</a>
                                                <?php endif; ?>
                                                <?php if($dado['situacao']=='Aprovado'): ?>
                                                    <a class="btn btn-secondary" href="gerar-pdf.php?token=<?=$dado['token']?>">Imprimir</a>
                                                <?php endif; ?>
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
                $('#veiculo_filtrado').select2();
            });
        </script>
    </body>
</html>