function addcart(valor) {
	var quantidade = $("#quantidade-"+valor).val();
    if(quantidade == ''){
        alert("Digite uma quantidade para esse produto");
    }else{
        $.ajax({
            url: "/sistema/web/backend/product/shop/add/"+valor+"/"+quantidade,
            dataType: "html",
            beforeSend: function(){
                document.getElementById('addtocart-'+valor).innerHTML = 'Adicionando...';
            },
            success: function(){
                document.getElementById('addtocart-'+valor).innerHTML = 'Remover do carrinho';
                document.getElementById('quantidade-'+valor).disabled = true;
                $("#quantidade-"+valor).val("");
                document.getElementById('addtocart-'+valor).className = 'btn btn-danger btn-group';
                document.getElementById('listproduct').innerHTML = 'Adicionado com sucesso';
                $("#listproduct").fadeOut(3000);
                $("#addtocart-"+valor).attr("onclick","javascript:removecart("+valor+","+valor+")");
            },
            error: function(){
                document.getElementById('addtocart-'+valor).innerHTML = 'Remover do carrinho';
                document.getElementById('quantidade-'+valor).disabled = true;
                $("#quantidade-"+valor).val("");
                document.getElementById('addtocart-'+valor).className = 'btn btn-danger btn-group';
                document.getElementById('listproduct').innerHTML = 'Adicionado com sucesso';
                $("#listproduct").fadeOut(3000);
                $("#addtocart-"+valor).attr("onclick","javascript:removecart("+valor+","+valor+")");
            },
        });
    }
}