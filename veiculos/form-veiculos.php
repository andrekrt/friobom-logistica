<?php 

session_start();
require("../conexao.php");

$idModudulo = 1;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
    
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
        <title>Cadastro de Veículo</title>
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
                            <img src="../assets/images/icones/veiculo.png" alt="">
                    </div>
                    <div class="title">
                            <h2>Cadastrar Novo Veículo</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="add-veiculos.php" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-12 espaco ">
                                <label for="codVeiculo"> Código do Veículo </label>
                                <input type="text" required name="codVeiculo" class="form-control" id="codVeiculo">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 espaco ">
                                <label for="tipoVeiculo"> Tipo do Veículo </label>
                                <input type="text" required name="tipoVeiculo" class="form-control" id="tipoVeiculo">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 espaco ">
                                <label for="placaVeiculo"> Placa do Veículo </label>
                                <input type="text" required name="placaVeiculo" class="form-control" id="placaVeiculo">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 espaco ">
                                <label for="categoria"> Categoria </label>
                                <select name="categoria" required class="form-control" id="categoria">
                                    <option value=""></option>
                                    <option value="Truck">Truck</option>
                                    <option value="Toco">Toco</option>
                                    <option value="Mercedinha">Mercedinha</option>
                                    <option value="Frota Leve">Frota Leve</option>
                                    <option value="Estoque">Estoque</option>
                                    <option value="Oficina">Oficina</option>
                                    <option value="Serviço">Serviço</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 espaco">
                                <label for="marca"> Marca </label>
                                <select name="marca" id="marca" required class="form-control">
                                    <option value=""></option>
                                    <option value="VOLVO">VOLVO</option>
                                    <option value="MERCEDS">MERCEDS</option>
                                    <option value="VOLKSWAGEM">VOLKSWAGEM</option>
                                    <option value="FIAT">FIAT</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 espaco">
                                <label for="base">Cidade Base</label>
                                <select name="base" id="base" required class="form-control">
                                    <option value=""></option>
                                    <option value="São Luís">São Luís</option>
                                    <option value="Bacabal">Bacabal</option>
                                    <option value="Timon">Timon</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4 espaco">
                                <label for="peso">Peso Máximo(Kg)</label>
                                <input type="text" required name="peso" id="peso" class="form-control">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="cubagem">Cubagem(m3)</label>
                                <input type="text" required name="cubagem" id="cubagem" class="form-control">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="metaCombustivel">Meta de Combustível</label>
                                <input type="text" required name="metaCombustivel" id="metaCombustivel" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"> Cadastrar </button>
                    </form>
                </div>
            </div>
        </div>

        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <!-- sweert alert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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