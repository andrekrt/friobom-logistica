<?php

session_start();
require("../conexao.php");
include_once "sql.php";

$idModudulo = 21;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $nomeUsuario = $_SESSION['nomeUsuario'];

    // função para pegar as nfs no winthor e registrar no bd online
    registraNf();
    

} else {
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
    header("Location: ../index.php");
    exit();
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

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/icone-nf.png" alt="">
                </div>
                <div class="title">
                    <h2>NF's</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="table-responsive">
                    <table id='tableFat' class='table table-bordered nowrap' style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Tags</th>
                                <th>  ID </th>
                                <th>  Nº NF </th>
                                <th>Data Emissão</th>
                                <th> Data Entrada </th>
                                <th>Nome Emitente </th>
                                <th>CNPJ Emitente</th>
                                <th>Cidade</th>
                                <th>UF</th>
                                <th>Fone</th>
                                <th>CFOP</th>
                                <th>Valor Total (R$)</th>
                                <th>Manifestação</th>
                                <th>Fornecedor</th>
                                <th>Carregamento</th>
                                <th> Status Carga </th>
                                <th>Divergência</th>
                                <th>Obs</th>
                                <th>Situação</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/menu.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    <!-- sweert alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- bibliotecas momentes -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/pt-br.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#tableFat').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pesq_nf.php'
                },
                'columns': [
                    { data: 'tags'},
                    { data: 'idnf' },
                    { data: 'num_nota' },
                    { data: 'data_emissao' },
                    { data: 'data_entrada'},
                    { data: 'nome_emit' },
                    { data: 'cnpj'},
                    { data: 'cidade'},
                    { data: 'uf'},
                    { data: 'fone'},
                    { data: 'cfop'},
                    { data: 'valor_total'},
                    { data: 'situacao_manifest'},
                    { data: 'fornecedor'},
                    { data: 'carregamento'},
                    { data: 'status_carga'},
                    { data: 'divergencia'},
                    { data: 'obs'},
                    { data: 'situacao'}
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                order: [3, 'desc']
            });

            // função para clicar na linha e pegar o id 
            $('#tableFat tbody').on('click', 'tr', function(){
                // verificar se a linha ja estaa selecionado
                let selected = $(this).hasClass('selected');

                // remover a classes 'select' de todas as linha
                $('#tableFat tbody tr.selected').removeClass('selected');

                // se a linha clicada ja for selecionado remove
                if(selected){
                    $('.acao-btn').remove();
                    $('.acao-row').remove();
                    return;
                }

                // Adiciona a classe 'selected' apenas à linha clicada
                $(this).addClass('selected');

                // pegar os valores das celulas
                let cells = $(this).find('td');
                let rowData = [];

                cells.each(function() {
                    rowData.push($(this).text());
                });

                $('.acao-btn').remove();
                $('.acao-row').remove();

                let btn = $(' <button> ').addClass('acao-btn btn btn-primary').text(' Detalhes ');
                let btnObs = $(' <button> ').addClass('acao-btn btn btn-success').text(' Obs. ');
                let btnConf = $('<a href="atualiza-status.php?status=Conferido&id='+ rowData[1]+ '">').addClass('acao-btn btn btn-secondary').text('Conferido');
                let btnCancela = $('<a href="atualiza-status.php?status=Cancelado&id='+ rowData[1]+ '">>').addClass('acao-btn btn btn-danger').text('Cancelar');
        
                btn.on('click', function(){
                    // $('#danfe').modal('show');
                    document.getElementById("modal1").style.display = "block";
                    document.getElementById("modal2").style.display = "block";
                    
                    $.ajax({
                        url:"get_xml.php",
                        data:{id:rowData[1]},
                        type:'post',
                        success: function(data){
                            var json = JSON.parse(data);
                            var produtos = json.produtos;
                            var produtosRef= json.produtosRef;
                            var dataFormat = moment(json.dtEmissao).locale('pt-bt').format('DD/MM/YYYY HH:mm:ss');
                            $('#natOp').text(json.natOp);
                            $('#tipoOp').text(json.tipoOp);
                            $('#chave').text(json.chave);
                            $('#modelo').text(json.modelo);
                            $('#serie').text(json.serie);
                            $('#num').text(json.numNota);
                            $('#dtEmit').text(dataFormat);
                            $('#cnpj').text(json.cnpjEmit);
                            $('#ie').text(json.ieEmit);
                            $('#nome').text(json.razaoEmit);
                            $('#cidade').text(json.cityEmit);
                            $('#uf').text(json.ufEmit);
                            $('#valorNF').text(json.valorNf);
                            $('#chaveRef').text(json.chaveRef);
                           
                            $('#produtos').empty();
                            $('#produtosRef').empty();

                            produtos.forEach(function(produto){
                                $('#produtos').append(
                                    '<tr style="color:'+produto.cor+'">'+
                                        '<td>'+ produto.descricao +'</td>'+
                                        '<td>'+ produto.qtd +'</td>'+
                                        '<td>'+ produto.und +'</td>'+
                                        '<td>'+ produto.vl_unit +'</td>'+
                                        '<td>'+ produto.vlTotal +'</td>'+
                                    '</tr>'
                                );
                            });
                            
                            produtosRef.forEach(function(produtoRef){
                                $('#produtosRef').append(
                                    '<tr>'+
                                        '<td>'+ produtoRef.descricaoRef +'</td>' +
                                        '<td>'+ produtoRef.qtdRef +'</td>' +
                                        '<td>'+ produtoRef.undRef +'</td>' +
                                        '<td>'+ produtoRef.vl_unitRef +'</td>' +
                                        '<td ">'+ (produtoRef.vlTotalRef) +'</td>' +
                                    '</tr>'
                                );
                            });

                        }
                    })
                });

                btnObs.on('click', function(){
                    $('#addObs').modal('show');
                    $.ajax({
                        url:"get_obs.php",
                        data:{id:rowData[1]},
                        type:'post',
                        success: function(data){
                            var json = JSON.parse(data);
                            $('#idNf').val(json.idnf);
                            $('#obs').val(json.obs);                        
                        }
                    })
                });


                if(rowData[18]=='Pendente'){
                    var actionRow = $('<tr>').addClass('acao-row text-center').append($('<td colspan="' + cells.length + '">').append(btn).append(btnObs).append(btnConf).append(btnCancela));
                }else if(rowData[18]=='Finalizado' || rowData[18]=='Conferido' || rowData[18]=='Cancelado'){
                    var actionRow = $('<tr>').addClass('acao-row text-center').append($('<td colspan="' + cells.length + '">').append(btn));
                }
               

                // Insere o botão e a linha de ação após a linha clicada
                $(this).after(actionRow);
            })
           
        });

        function closeModal(modalId) {
            document.getElementById("modal2").style.display = "none";
            document.getElementById("modal1").style.display = "none";
        }
    </script>

<!-- modal add obs -->
<div class="modal fade" id="addObs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar Obs.</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add-obs.php" method="post">
                    <div class="form-row">
                        <input type="hidden" name="idNf" value="" id="idNf">
                        <div class="form-group col-md-12 espaco ">
                            <label for="obs">Obs.</label>
                            <input type="text"  name="obs" class="form-control" id="obs" required>
                        </div>
                    </div>
                   
            </div>
            <div class="modal-footer">
                <div class="text-center">
                        <button type="submit" class="btn btn-primary">Lançar</button>
                    </form> 
                </div>
                <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div id="modalContainer">
  <div id="modal1" class="modalNf">
    <div class="modal-contentNf">
      <span class="close" onclick="closeModal('modal1')">&times;</span>
        <table class="table table-bordered" >
            <fieldset>
                <legend>Dados NFe</legend>
                <tr>
                    <th>Natureza da operação</th>
                    <th>Tipo da operação</th>
                    <th colspan="2">Chave de acesso</th>
                </tr>
                <tr>
                    <td id="natOp"></td>
                    <td id="tipoOp"></td>
                    <td id="chave" colspan="2"></td>
                </tr>
                <tr>
                    <th>Modelo</th>
                    <th>Série</th>
                    <th>Número</th>
                    <th>Data/Hora da Emissão</th>
                </tr>
                <tr>
                    <td id="modelo"></td>
                    <td id="serie"></td>
                    <td id="num"></td>
                    <td id="dtEmit"></td>
                </tr>
            </fieldset>
        </table> 
        <table class="table table-bordered">
            <fieldset>
                <legend>Emitente</legend>
                <tr>
                    <th>CNPJ</th>
                    <th>IE</th>
                    <th>Nome/Razão Social</th>
                </tr>
                <tr>
                    <td id="cnpj"></td>
                    <td id="ie"></td>
                    <td id="nome"></td>
                </tr>
                <tr>
                    <th>Município</th>
                    <th>UF</th>
                </tr>
                <tr>
                    <td id="cidade"></td>
                    <td id="uf"></td>
                </tr>
            </fieldset>
        </table>    
        <table class="table table-bordered">
            <fieldset >
                <legend>Produtos</legend>
                <thead>
                    <tr >
                        <th>Descrição</th>
                        <th>Qtd</th>
                        <th>Unid</th>
                        <th>Valor Unit.</th>
                        <th>Valor Total</th>
                    </tr>
                </thead>
                <tbody id="produtos">

                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Valor NF</th>
                        <td id="valorNF"></td>
                    </tr>
                </tfoot>
            </fieldset>
        </table> 
    </div>
  </div>

  <div id="modal2" class="modalNf">
    <div class="modal-contentNf">
      <span class="close" onclick="closeModal('modal2')">&times;</span>  
        <table class="table table-bordered">
            <fieldset >
                <legend>Produtos NF Referente</legend>
                <thead>
                    <tr>
                        <th> Chave Referente:</th>
                        <th id="chaveRef" colspan="4"></th>
                    </tr>
                    <tr>
                        <th>Descrição</th>
                        <th>Qtd</th>
                        <th>Unid</th>
                        <th>Valor Unit.</th>
                        <th>Valor Total</th>
                    </tr>
                </thead>
                <tbody id="produtosRef">

                </tbody>
                
            </fieldset>
        </table>
    </div>
  </div>
</div>

<!-- msg de sucesso ou erro -->
<?php
    // Verifique se há uma mensagem de confirmação na sessão
    if (isset($_SESSION['msg']) && isset($_SESSION['icon'])) {
        // Exiba um alerta SweetAlert
        echo "<script>
                Swal.fire({
                  icon: '$_SESSION[icon]',
                  title: '$_SESSION[msg]',
                  showConfirmButton: true,
                });
              </script>";

        // Limpe a mensagem de confirmação da sessão
        unset($_SESSION['msg']);
        unset($_SESSION['status']);
    }
?>
</body>
</html>