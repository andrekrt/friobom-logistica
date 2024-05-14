<?php

session_start();
require("../conexao.php");

$idModudulo = 12;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idUsuario = $_SESSION['idUsuario'];
    $nomeUsuario = $_SESSION['nomeUsuario'];
    $tipoUsuario = $_SESSION['tipoUsuario'];
    $filial = $_SESSION['filial'];
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
    <title>Cadastro de Pneu</title>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
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
                    <h2>Cadastro de Pneu</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <form action="add-pneus.php" class="despesas" enctype="multipart/form-data" method="post" id="">
                    <div id="formulario">
                         
                        <div class="form-row">
                            <div class="form-grupo col-md-1 espaco">
                                <label for="nFogo">Nº Fogo</label>
                                <input type="text" name="nFogo" id="nFogo" class="form-control" required>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="medida">Medida</label>
                                <input type="text" name="medida" id="medida" class="form-control" required>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="calibMax">Calibragem Máxima</label>
                                <input type="text" required name="calibMax" id="calibMax" class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco ">
                                <label for="marca">Marca</label>
                                <input type="text" required name="marca" id="marca" class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco ">
                                <label for="modelo">Modelo</label>
                                <input type="text" required name="modelo" id="modelo" class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco ">
                                <label for="nSerie">Nº Série</label>
                                <input type="text" required name="nSerie" id="nSerie" class="form-control">
                            </div>
                            <div class="form-group col-md-1 espaco ">
                                <label for="vida">Vida </label>
                                <input type="text" required name="vida" id="vida" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="posicao">Posição Início</label>
                                <input type="text" required name="posicao" class="form-control" id="posicao">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="veiculo">Veículo</label>
                                <select class="form-control" name="veiculo" required id="veiculo">
                                    <option value=""></option>
                                    <?php
                                    $sql = $db->query("SELECT placa_veiculo FROM veiculos WHERE filial = $filial ORDER BY placa_veiculo ASC");
                                    $dados = $sql->fetchAll();
                                    foreach ($dados as $dado):
                                    ?>
                                    <option value=<?=$dado['placa_veiculo']?>><?= $dado['placa_veiculo']?>  </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-1 espaco">
                                <label for="kmVeiculo">Km Veículo</label>
                                <input type="text" required name="kmVeiculo" class="form-control" id="kmVeiculo">
                            </div>
                            <div class="form-group col-md-1 espaco">
                                <label for="situacao">Situação</label>
                                <input type="text" required name="situacao" class="form-control" id="situacao">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="localizacao">Localização</label>
                                <input type="text" required name="localizacao" class="form-control" id="localizacao">
                            </div>
                            <div class="form-group col-md-1 espaco">
                                <label for="suco01">Suco 01</label>
                                <input type="text" required name="suco01" class="form-control" id="suco01">
                            </div>
                            <div class="form-group col-md-1 espaco">
                                <label for="suco02">Suco 02</label>
                                <input type="text" required name="suco02" class="form-control" id="suco02">
                            </div>
                            <div class="form-group col-md-1 espaco">
                                <label for="suco03">Suco 03</label>
                                <input type="text" required name="suco03" class="form-control" id="suco03">
                            </div>
                            <div class="form-group col-md-1 espaco">
                                <label for="suco04">Suco 04</label>
                                <input type="text" required name="suco04" class="form-control" id="suco04">
                            </div>
                        </div>
                    
                    </div>
                    <div class="form-group">
                        <button type="submit" name="entradas" class="btn btn-primary"> Enviar Solicitação</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>
    <script src="../assets/js/jquery.mask.js"></script>
    <!-- sweert alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#veiculo').select2({
                theme: 'bootstrap4'
            });
            $('#nFogo').mask('000000');
            $('#calibragem').mask('0000');
            $('#vida').mask('0');
        });
    </script>

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