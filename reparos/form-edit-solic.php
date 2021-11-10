<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 3 || $_SESSION['tipoUsuario'] == 99) {
    $tipoUsuario = $_SESSION['tipoUsuario'];

    $idPneu = filter_input(INPUT_GET, 'idPneu');
    $pneu = $db->prepare("SELECT * FROM solicitacoes_new WHERE id=:id");
    $pneu->bindValue(':id', $idPneu);
    $pneu->execute();
    $dado = $pneu->fetch();
} else {
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Serviço</title>
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
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/reparos.png" alt="">
                </div>
                <div class="title">
                    <h2>Editar Solicitação</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <form action="atualiza-new.php" method="post">
                    <input type="hidden" name="id" id="id" value="<?=$dado['id']?>">
                    <input type="hidden" name="trid" id="trid" value="">
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
                                    $veiculos = $veiculos->fetchAll();
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
                    <?php endforeach; ?>
                    <div  class="form-row">
                    <?php if($dado['situacao']!="Aprovado"): ?>
                        <div class="form-group col-md-2">
                            <a href="excluir.php?token=<?=$dado['token']; ?>" class="btn btn-danger" onclick="return confirm('Confirmar Exclusão?');"> Excluir </a>
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" name="analisar" class="btn btn-primary">Atualizar</button>
                        </div>
                        <div class="form-group col-md-3">
                        <?php if($dado['situacao']!="Aprovado" && ($tipoUsuario==99 || $tipoUsuario==3))  : ?>
                            <a class="btn btn-success" href="solicitacao-adicional.php?token=<?=$dado['token']?>" >Adiconar Peças/Serviço</a>
                        <?php endif; ?>
                        </div>
                    <?php endif; ?>
                        <div class="form-group col-md-3">
                        <?php if($dado['situacao']=='Aprovado'): ?>
                            <a class="btn btn-secondary" href="gerar-pdf.php?token=<?=$dado['token']?>">Imprimir</a>
                        <?php endif; ?>
                        </div>
                    </div>
                </form> 
                
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>
    <script>
        $(document).ready(function() {
            $('#veiculo').select2();
            $('#localReparo').select2();
            $('#peca').select2();
            $('#motorista').select2();
            $('#rota').select2();
        });
    </script>
</body>

</html>