<?php

session_start();
require("../conexao.php");

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

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
    <title>FRIOBOM - TRANSPORTE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- arquivos para datatable -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.css"/>

    <!-- select02 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/almoxerifado.png" alt="">
                </div>
                <div class="title">
                    <h2>Ordem de Serviço</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalOrdemServico" data-whatever="@mdo" name="idOrdemServico">Nova Ordem de Serviço</button>
                    </div>
                    <a href="os-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a> 
                </div>
                <div class="table-responsive">
                    <table id='tableOS' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">ID</th>
                                <th scope="col" class="text-center text-nowrap">Data Abertura</th>
                                <th scope="col" class="text-center text-nowrap">Placa</th>
                                <th scope="col" class="text-center text-nowrap">Descrição Problema</th>
                                <th scope="col" class="text-center text-nowrap">Corretiva</th>
                                <th scope="col" class="text-center text-nowrap">Preventiva</th>
                                <th scope="col" class="text-center text-nowrap">Manutenção Externa</th>
                                <th scope="col" class="text-center text-nowrap">Troca de Óleo</th>
                                <th scope="col" class="text-center text-nowrap">Higienização</th>
                                <th scope="col" class="text-center text-nowrap"> Agente Causador </th>
                                <th scope="col" class="text-center text-nowrap"> Nº NF </th>
                                <th scope="col" class="text-center text-nowrap"> Observações </th>
                                <th scope="col" class="text-center text-nowrap"> Situação  </th>
                                <th scope="col" class="text-center text-nowrap"> Data Encerramento  </th>
                                <th scope="col" class="text-center text-nowrap"> Usuário Lançou   </th>
                                <th scope="col" class="text-center text-nowrap"> Ações  </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#tableOS').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_os.php'
                },
                'columns': [
                    { data: 'idordem_servico' },
                    { data: 'data_abertura' },
                    { data: 'placa' },
                    { data: 'descricao_problema' },
                    { data: 'corretiva' },
                    { data: 'preventiva' },
                    { data: 'externa' },
                    { data: 'oleo' },
                    { data: 'higienizacao' },
                    { data: 'causador' },
                    { data: 'num_nf' },
                    { data: 'obs' },
                    { data: 'situacao' },
                    { data: 'data_encerramento' },
                    { data: 'nome_usuario' },
                    { data: 'acoes' },
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[13]}
                ],
            });
        });
    </script>

<!-- MODAL CADASTRO DE ordem de serviço -->
<div class="modal fade" id="modalOrdemServico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ordem de Serviço</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add-ordemservico.php" method="post">
                    <div id="formulario">
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco ">
                                <label for="descricao">Placa </label>
                                <select required name="placa" id="placa" class="form-control">
                                    <option value=""></option>
                                    <?php $pecas = $db->query("SELECT * FROM veiculos");
                                    $pecas = $pecas->fetchAll();
                                    foreach($pecas as $peca):
                                    ?>
                                    <option value="<?=$peca['placa_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-10 espaco ">
                                <label for="problema"> Descrição Problema </label>
                                <input type="text" required name="problema" id="problema" class="form-control">
                            </div>
                        </div>   
                        <div class="form-row">
                            <div class="form-group espaco form-check check-os">
                                <input type="checkbox" class="form-check-input" id="corretiva" name="corretiva">
                                <label class="form-check-label" for="corretiva">Corretiva</label>
                            </div>
                            <div class="form-group  espaco form-check check-os">
                                <input type="checkbox" class="form-check-input" id="preventiva" name="preventiva">
                                <label class="form-check-label" for="preventiva">Preventiva</label>
                            </div>
                            <div class="form-group  espaco form-check check-os">
                                <input type="checkbox" class="form-check-input" id="externa" name="externa">
                                <label class="form-check-label" for="externa">Manutenção Externa</label>
                            </div>
                            <div class="form-group  espaco form-check check-os">
                                <input type="checkbox" class="form-check-input" id="higienizacao" name="higienizacao">
                                <label class="form-check-label" for="higienizacao">Higienização</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4 espaco ">
                                <label for="causador"> Agente Causador </label>
                                <input type="text" name="causador" id="causador" class="form-control">
                            </div>
                            <div class="form-group col-md-3 espaco ">
                                <label for="nf"> NF </label>
                                <input type="text" name="nf" class="form-control" id="nf">
                            </div>
                            <div class="form-group col-md-5 espaco ">
                                <label for="obs">Obs. </label>
                                <input type="text" name="obs" class="form-control" id="obs">
                            </div>
                        </div>  
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco ">
                                <label for="servico"> Serviço </label>
                                <select required name="servico[]" id="servico" class="form-control">
                                    <option value=""></option>
                                    <?php $servicos = $db->query("SELECT * FROM servicos_almoxarifado");
                                    $servicos = $servicos->fetchAll();
                                    foreach($servicos as $servico):
                                    ?>
                                    <option value="<?=$servico['idservicos']?>"><?=$servico['descricao']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco ">
                                <label for="peca"> Peça </label>
                                <select name="peca[]" id="peca" class="form-control">
                                    <option value=""></option>
                                    <?php $pecas = $db->query("SELECT * FROM peca_estoque");
                                    $pecas = $pecas->fetchAll();
                                    foreach($pecas as $peca):
                                    ?>
                                    <option value="<?=$peca['idpeca']?>"><?=$peca['idpeca']." - ". $peca['descricao_peca']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco ">
                                <label for="qtd"> Qtd </label>
                                <input type="text" name="qtd[]" id="qtd" class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco ">
                                <label for="requisicao"> Nº Requisição de Peça </label>
                                <input type="text" name="requisicao[]" id="requisicao" class="form-control">
                            </div>
                            <div style="margin: auto; margin-left: 0;">
                                <button type="button" class="btn btn-danger" id="add-peca">Adicionar Peça</button>
                            </div>
                        </div>
                    </div>
                    
            </div>
            <div class="modal-footer">
                <button type="submit" name="analisar" class="btn btn-primary">Lançar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- FIM MODAL CADASTRO DE ordem de serviço-->

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){

        var cont = 1;
        $('#add-peca').click(function(){
            
            cont++;

            $('#formulario').append('<div class="form-row"> <div class="form-group col-md-3 espaco "> <label for="servico"> Serviço </label> <select required name="servico[]" id="servico" class="form-control"> <option value=""></option> <?php $servicos = $db->query("SELECT * FROM servicos_almoxarifado"); $servicos = $servicos->fetchAll(); foreach($servicos as $servico): ?> <option value="<?=$servico['idservicos']?>"><?=$servico['descricao']?></option> <?php endforeach; ?> </select> </div> <div class="form-group col-md-3 espaco "> <label for="peca"> Peça </label> <select required name="peca[]" id="peca" class="form-control"> <option value=""></option> <?php $pecas = $db->query("SELECT * FROM peca_estoque"); $pecas = $pecas->fetchAll(); foreach($pecas as $peca):?> <option value="<?=$peca['idpeca']?>"><?=$peca['idpeca']." - ". $peca['descricao_peca']?></option> <?php endforeach; ?> </select> </div> <div class="form-group col-md-2 espaco "> <label for="qtd"> Qtd </label> <input type="text" name="qtd[]" id="qtd" class="form-control"> </div> <div class="form-group col-md-2 espaco "> <label for="requisicao"> Nº Requisição de Peça </label> <input type="text" name="requisicao[]" id="requisicao" class="form-control"> </div>  </div>');

        });

        $('#placa').select2({
            width: '100%',
            dropdownParent:"#modalOrdemServico"
        });
        // $('#placaEdit').select2({
        //     width: '100%',
        //     dropdownParent:"#modalEditar"
        // });
        
    });
</script>
</body>
</html>