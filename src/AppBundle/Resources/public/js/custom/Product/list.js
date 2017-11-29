$(document).ready(function () {
    checAll();
    selectItem();
    cartItem();
    
       //Inclui a função on click ao excluir um registro
    $('.delete_all_products').click(function (event) {
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
});

/* ==== Função para selecionar todos os checkbox ==== */
function checAll() {
    var check = $('input[id="check-all"]'); //checkbox para selecionar todos
    var checkItens = $('input[class="checkItem"]'); //itens a serem selecionados
    check.on('click', function () {
        checkItens.each(function () {//percorre todos os checkbox
            if ($(this).prop("checked"))//verifica se o item esta marcado
                $(this).prop("checked", false);
            else
                $(this).prop("checked", true);
        });
    });
}

/* ==== Função para selecionar os inputs que estão marcados ==== */
function selectItem() {
    var checkItem = $('input[name="deleteProduct"]'); //itens a serem removidos
    var btnClick = $('.delete_item_products'); //botao para startar a acao
    btnClick.on('click', function (event) {
        event.preventDefault();
        var itens = [];
        if ($('td').find("input[name='deleteProduct']:checked").length > 0) {//verifica se ha um produto selecionado
            checkItem.each(function () {//percorre os items para serem removidos
                if ($(this).is(':checked')) {//verifica se o item esta marcado
                    itens.push($(this).val());
                }
            });
            removeItem(itens);
        } else {
            alert('Erro. Selecione os produtos que você deseja remover.');
        }
    });
}

/* ==== Função para selecionar os inputs que estão marcados(carrinho de compras) ==== */
function cartItem() {
    var checkItem = $('input[name="shopProduct"]');
    var quantItem = $('input[name="quantidade"]');
    var btnClick = $('.add_shop'); //botao para startar a acao
    btnClick.on('click', function (event) {
        event.preventDefault();
        var itens;
        var quant;
        var min = document.getElementById("quantidade").min;
        var max = document.getElementById("quantidade").max;
        checkItem.each(function (){//percorre os items para serem removidos
            itens = $(this).val();
            quant = quantItem.val();
        });
        sendItem(quant,itens);
    });
}

/* ==== Funcao para comprar os produtos selecionados ==== */
function sendItem(quant,data) {
    if (data.length > 0) {
        $('input[id="id-item"]').val(data);
        $('input[id="quant-item"]').val(quant);
        $('form[id="form-shop"]').submit();
    } else {
        alert('Erro. Selecione os produtos que você deseja remover.');
    }
}

/* ==== Funcao para remover os produtos selecionados ==== */
function removeItem(data) {
    if (data.length > 0) {
        $('input[id="id-item"]').val(data);//envia o array para o form criado para deletar na pagina principal
        $('#modal-remove').modal('show');//Exibe o modal na tela
        $('.btn-confirm').on('click', function () {//botao para confirmar a ação de remover
            $('#modal-remove').modal('hide');//Fecha o modal na tela
            $('form[id="form-delete"]').submit();//efetua o submit do formulario para deletar os arquivos
        });
    } else {
        alert('Erro. Selecione os produtos que você deseja remover.');
    }
}
