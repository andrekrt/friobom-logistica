$(document).ready(function(){
    $("input[name='codMotorista']").blur(function(){
        var $motorista = $("input[name='motorista']");
        var codMotorista = $(this).val();

        $.getJSON('pesquisaMotorista.php', {codMotorista},
            function(retorno){
                $motorista.val(retorno.motorista);
            }
        );
    });
});