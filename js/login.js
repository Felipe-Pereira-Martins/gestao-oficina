// js/login.js - Scripts para a tela de login

$(document).ready(function() {
    // Loading no botão de login (APENAS para o form de login)
    $('form[action="autenticar.php"]').submit(function(event) {
        var $btn = $(this).find('.btn-login');
        $btn.addClass('loading');
        return true;
    });
    
    // Recuperar senha - SIMPLES, sem firulas (é rápido mesmo)
    $('#form-recuperar').submit(function(event) {
        event.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: "recuperar.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                var msgDiv = $('#mensagem');
                msgDiv.removeClass('text-success text-danger');
                
                if (mensagem.trim() === "Sua senha foi Enviada para seu Email!") {
                    msgDiv.addClass('text-success');
                    setTimeout(function() {
                        $('#modalRecuperar').modal('hide');
                        msgDiv.empty();
                    }, 2000);
                } else {
                    msgDiv.addClass('text-danger');
                }
                
                msgDiv.html('<i class="fas fa-info-circle"></i> ' + mensagem);
            },
            error: function() {
                $('#mensagem').addClass('text-danger')
                    .html('<i class="fas fa-exclamation-circle"></i> Erro ao processar solicitação');
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
});