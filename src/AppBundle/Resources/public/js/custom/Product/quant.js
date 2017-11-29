var variavel_gobal;

function feedbackItem(id) {
	variavel_gobal = id;
	$('#modal-quant').modal('show');
}

function feedItem(id) {
	variavel_gobal = id;
	$('#modal-quant-laboratory').modal('show');
}

function feedbackSendItem() {
	var feedback = document.getElementById("quantidade").value;
	var url_atual = window.location.href;
	location.href = url_atual+"/shop/remove/"+variavel_gobal+"/quantidade/"+feedback;
	variavel_gobal = null;
	feedback = null;
}

function feedSendItem() {
	var feedback = document.getElementById("quantidade1").value;
	var url_atual = window.location.href;
	var url_atual = url_atual.substr(0,(url_atual.length - 2));
	location.href = url_atual+"/shop/remove/"+variavel_gobal+"/quantidade/"+feedback+"/laboratory";
}