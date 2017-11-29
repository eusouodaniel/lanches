function removecart(id,valor) {
    $.ajax({
        url: "/sistema/web/backend/product/shop/remove/"+valor,
        dataType: "html",
        beforeSend: function(){
            document.getElementById('addtocart-'+valor).innerHTML = 'Removendo...';
        },
        success: function(){
            document.getElementById('addtocart-'+valor).innerHTML = 'Adicionar ao carrinho';
            document.getElementById('quantidade-'+valor).disabled = false;
            $("#quantidade-"+valor).val("");
            document.getElementById('addtocart-'+valor).className = 'btn btn-success btn-group';
            document.getElementById('listproduct').innerHTML = 'Removido com sucesso';
            $("#listproduct").fadeOut(3000);
            $("#addtocart-"+valor).attr("onclick","javascript:addcart("+valor+")");
        },
        error: function(){
            document.getElementById('addtocart-'+valor).innerHTML = 'Adicionar ao carrinho';
            document.getElementById('quantidade-'+valor).disabled = false;
            $("#quantidade-"+valor).val("");
            document.getElementById('addtocart-'+valor).className = 'btn btn-success btn-group';
            document.getElementById('listproduct').innerHTML = 'Removido com sucesso';
            $("#listproduct").fadeOut(3000);
            $("#addtocart-"+valor).attr("onclick","javascript:addcart("+valor+")");
        },
    });
}