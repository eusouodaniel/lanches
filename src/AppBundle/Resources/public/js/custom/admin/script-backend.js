
jQuery(document).ready(function ($) {

    //Inclui a função on click ao excluir um registro
    $('.delete_item').click(function (event) {
        event.preventDefault();
        $('#modal-remove').modal('show');//Exibe o modal na tela
        var data = $(this).data('url');// url para remover o item
        
        $('.btn-confirm').on('click', function () {//botao para confirmar a ação de remover
            $('#modal-remove').modal('hide');//Fecha o modal na tela
            window.location.href = data;//redireciona para o método de remover o cadastro

        });
        /*
         var user = confirm("Tem certeza que deseja excluir esse registro?");
         if (user === true) {
         window.location.href = $(this).data('url');
         }*/
    });

    //Inclui a função on click ao cancelar um registro
    $('.cancel_item').click(function (event) {
        event.preventDefault();

        var user = confirm("Tem certeza que deseja cancelar esse registro?");

        if (user === true) {
            window.location.href = $(this).data('url');
        }
    });

    $(".select2").select2();

    //Máscara de telefone com o nono dígito
    $("input.telefone")
            .mask("(99) 9999-9999?9")
            .focusout(function (event) {
                var target, phone, element;
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                phone = target.value.replace(/\D/g, '');
                element = $(target);
                element.unmask();
                if (phone.length > 10) {
                    element.mask("(99) 99999-999?9");
                } else {
                    element.mask("(99) 9999-9999?9");
                }
            });
    //Máscara de CPF com validação assim que o campo perde o foco
    $("input.cpf").mask("999.999.999-99")
            .focusout(function (event) {
                var target, cpf, element;
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                cpf = target.value.replace(/\D/g, '');
                element = $(target);
                if (cpf == "") {
                    return true;
                }
                // Elimina CPFs invalidos conhecidos
                if (cpf.length != 11 ||
                        cpf == "00000000000" ||
                        cpf == "11111111111" ||
                        cpf == "22222222222" ||
                        cpf == "33333333333" ||
                        cpf == "44444444444" ||
                        cpf == "55555555555" ||
                        cpf == "66666666666" ||
                        cpf == "77777777777" ||
                        cpf == "88888888888" ||
                        cpf == "99999999999") {
                    alert("CPF inválido");
                    element.val("");
                    return false;
                }
                // Valida 1o digito
                add = 0;
                for (i = 0; i < 9; i ++)
                    add += parseInt(cpf.charAt(i)) * (10 - i);
                rev = 11 - (add % 11);
                if (rev == 10 || rev == 11)
                    rev = 0;
                if (rev != parseInt(cpf.charAt(9))) {
                    alert("CPF inválido");
                    element.val("");
                    return false;
                }
                // Valida 2o digito
                add = 0;
                for (i = 0; i < 10; i ++)
                    add += parseInt(cpf.charAt(i)) * (11 - i);
                rev = 11 - (add % 11);
                if (rev == 10 || rev == 11)
                    rev = 0;
                if (rev != parseInt(cpf.charAt(10))) {
                    alert("CPF inválido");
                    element.val("");
                    return false;
                }
                return true;
		    
            });

    //Máscara de CNPJ com validação assim que o campo perde o foco
    $("input.cnpj").mask("99.999.999/9999-99")
            .focusout(function (event) {
                var target, cnpj, element;
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                cnpj = target.value.replace(/\D/g, '');
                element = $(target);
                if (cnpj == "") {
                    return true;
                }
                // Elimina CPFs invalidos conhecidos
                if (cnpj.length != 14 ||
                        cnpj == "00000000000000" ||
                        cnpj == "11111111111111" ||
                        cnpj == "22222222222222" ||
                        cnpj == "33333333333333" ||
                        cnpj == "44444444444444" ||
                        cnpj == "55555555555555" ||
                        cnpj == "66666666666666" ||
                        cnpj == "77777777777777" ||
                        cnpj == "88888888888888" ||
                        cnpj == "99999999999999") {
                    alert("CNPJ inválido");
                    element.val("");
                    return false;
                }

                // Valida DVs
                tamanho = cnpj.length - 2
                numeros = cnpj.substring(0, tamanho);
                digitos = cnpj.substring(tamanho);
                soma = 0;
                pos = tamanho - 7;
                for (i = tamanho; i >= 1; i--) {
                    soma += numeros.charAt(tamanho - i) * pos--;
                    if (pos < 2)
                        pos = 9;
                }
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado != digitos.charAt(0)) {
                    alert("CNPJ inválido");
                    element.val("");
                    return false;
                }

                tamanho = tamanho + 1;
                numeros = cnpj.substring(0, tamanho);
                soma = 0;
                pos = tamanho - 7;
                for (i = tamanho; i >= 1; i--) {
                    soma += numeros.charAt(tamanho - i) * pos--;
                    if (pos < 2)
                        pos = 9;
                }
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                console.log(resultado);
                if (resultado != digitos.charAt(1)) {
                    alert("CNPJ inválido");
                    element.val("");
                    return false;
                }

                return true;
			    
            });

    //Máscara de CEP
    $("input.cep").mask("99999-999");
    //Máscara de porcentagem
    $('.percent').mask('99%');
    //Máscara de money
    $('.money').maskMoney({prefix: 'R$ ', allowNegative: false, thousands: '.', decimal: ',', affixesStay: false});
    //Aplica o colorpicker ao component
    $('.colorpicker-component').colorpicker();
    //Aplica o colorpicker ao input
    $('.colorpicker-input').colorpicker();

    /* counterUp */
    $('.counter').counterUp({
        delay: 10,
        time: 900
    });

    /* dismiss alert */
    $("#alert-dismissable").alert();
    $("#alert-dismissable").fadeTo(3000, 1000).slideUp(1000, function () {
        $("#alert-dismissable").alert('close');
    });

});
