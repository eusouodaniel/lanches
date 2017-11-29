$(document).ready(function () {

    // Aplica o select2 nos campos definidos
    var applySelect2 = function () {
        $(".select2").select2();
    }

    // Aplica a máscara de money nos campos definidos
    var applyMoney = function () {
        $('.money').maskMoney({prefix: 'R$ ', allowNegative: false, thousands: '.', decimal: ',', affixesStay: false});
    }

    // Inicialização das tabelas
    initiateLaboratories();

    // Adiciona novo item ao horário
    $("#laboratories-add").click(function (e) {
        e.preventDefault();
        addLaboratoriesItem();
        applySelect2();
        applyMoney();
    });

    // Seta a função de exclusão nos itens da tabela de disponibilidade
    $("#laboratory-table tbody").children().each(function () {
        var laboratoryItem = $(this).closest('tr.laboratories-item');
        removeLaboratoriesItem(laboratoryItem);
        addChangeCost(laboratoryItem);
    });

    applySelect2();
    applyMoney();

});

/**
 * Função para inicializar o indice da tabela de disponibilidade
 */
function initiateLaboratories() {
    // Atualiza o indice do prototype com a quantidade de itens disponíveis
    $('#laboratory-table').attr('data-index-count', $(".laboratories-item").length);
}

/**
 * Método que adiciona um novo dia e horário de disponibilidade.
 */
function addLaboratoriesItem() {
    var collectionHolder = $('#laboratory-table');
    var prototype = collectionHolder.attr('data-prototype');
    var count = collectionHolder.attr('data-index-count');
    var newForm = $(prototype.replace(/\_\_name\_\_/g, count));

    collectionHolder.find('tbody').append(newForm);
    collectionHolder.attr('data-index-count', ++count);

    // Adiciona a ação de remover o item
    removeLaboratoriesItem(newForm);

    // Adiciona a ação de remover o item
    addChangeCost(newForm);

    // Adiciona a validação de licenças escolhidas
    //validaLaboratoriesItem(newForm);

    return newForm;
}

/**
 * Remove item da tabela de disponibilidade.
 */
function removeLaboratoriesItem(laboratoryItem) {
    laboratoryItem.find("a.laboratories-remove-item").click(function (e) {
        e.preventDefault();
        var item = this;
        var title = $(this).data('confirm-title');
        var text = $(this).data('confirm-text');
        var line = $(item).closest('tr.laboratories-item');
        $(line).remove();

    });
}

/**
 * Valida se existem dias repetidos na tabela de disponibilidade
 */
function validaLaboratoriesItem(laboratoryItem) {
    laboratoryItem.find(".laboratory").change(function (e) {
        e.preventDefault();
        var laboratory = $(this);
        var choicedLaboratory = $(this).val();
        var choicedId = $(this).attr('id');
        $(".laboratory").each(function (i) {
            if (choicedLaboratory == $(this).val() && ($(this).attr('id') != choicedId)) {
                alert("Este laboratório já foi selecionado!");
                laboratory.val("");
            }
        });
    });
}

/**
 * Função para efetuar cálculo automático
 * */
function addChangeCost(laboratoryItem) {
  laboratoryItem.find(".discountValue").change(function (e) {
    var desconto = $(this);
    var preco = laboratoryItem.find(".factoryValue");
    var precoFinal = laboratoryItem.find(".costValue");

    var descontoVal = desconto.val();
    descontoVal = descontoVal.replace(',', '.');
    desconto.val(descontoVal);

    result = (parseFloat(preco.val().replace(',', '.')) - (parseFloat(preco.val().replace(',', '.')) * (descontoVal / 100)));
    precoFinal.val(new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(result));
  });

}
