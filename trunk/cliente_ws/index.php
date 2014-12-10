<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>SD - WS - BANCO</title>

<style type="text/css">

body {
 background-color: #fff;
 margin: 40px;
 font-family: Lucida Grande, Verdana, Sans-serif;
 font-size: 14px;
 color: #4F5155;
}

a {
 color: #003399;
 background-color: transparent;
 font-weight: normal;
}

h1 {
 color: #444;
 background-color: transparent;
 border-bottom: 1px solid #D0D0D0;
 font-size: 16px;
 font-weight: bold;
 margin: 24px 0 2px 0;
 padding: 5px 0 6px 0;
}

code {
 font-family: Monaco, Verdana, Sans-serif;
 font-size: 12px;
 background-color: #f9f9f9;
 border: 1px solid #D0D0D0;
 color: #002166;
 display: block;
 margin: 14px 0 14px 0;
 padding: 12px 10px 12px 10px;
}

</style>
</head>
<body>

<h1>Bem vindo</h1>

<p>Utilize os campos abaixo para realizar as transações desejadas</p>
<form acion="">
	<div id="div-opcoes">
		<input type="radio" name="opcao" class="saldo"/>Saldo
		<input type="radio" name="opcao" class="depositar" />Depositar
		<input type="radio" name="opcao" class="sacar" />Sacar
		<input type="radio" name="opcao" class="transferir" />Transferir
	</div>

	<div id="div-conta" style="display:none;">
		<span>Conta</span>
		<input type="number" min=1 name="conta" id="conta" required placeholder="000" />
	</div>

	<campos>
	<div id="div-valor" style="display:none">
		<span>Valor</span>
		<input type="number" name="valor" id="valor" required placeholder="00" /> , 
		<input type="number" max=99 name="valor" id="valor_centavos" required placeholder="00" />
	</div>

	<div id="div-transferir" style="display:none">
		<span>Conta Destino</span>
		<input type="number" min=1 name="contaOrigem" id="contaOrigem" required placeholder="000" />
	</div>
<div id="div-button" style="display:none;">
	<input type="submit" value="Concluir" id="enviar_tst" />
</div>
	<div id="retorno">
		<ol>
			
		</ol>
	</div>
</form>
<script src="jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	$('input[type=radio]').click(function (){
		
		var atrib = $(this).attr('class');
		$('#div-conta').show();
		$('#div-button').show();
		if(atrib == "saldo"){
			$('#div-valor, #div-transferir').hide();
			$('#div-valor input , #div-transferir input').removeAttr('required');
		}
		else if(atrib == "depositar" || atrib == "sacar"){
			$('#div-transferir').hide();
			$('#div-transferir input').removeAttr('required');
			$('#div-valor').show();
			$('#div-valor input').attr('required', 'required');
		}
		else if(atrib == "transferir"){
			$('#div-transferir').show();
			$('#div-transferir input').attr('required', 'required');
			$('#div-valor').show();
			$('#div-valor input').attr('required', 'required');
		}
	});
	// Bind a click event to the 'ajax' object id
	$("form").submit(function( evt ){

		// Javascript needs totake over. So stop the browser from redirecting the page
		evt.preventDefault();
		var url = '';
		var valor = $("#valor").val() + "." +$("#valor_centavos").val();
		var operacao = 'com sucesso';
		if($('[name="opcao"]:checked').attr('class') == "saldo"){
			url = "../ws/index.php/api/banco_ws/saldo/conta/"+$('#conta').val()+"/format/json";
			operacao = "Saldo consultado "+operacao;
		}
		else if($('[name="opcao"]:checked').attr('class') == "depositar"){
			url = "../ws/index.php/api/banco_ws/depositar/conta/"+$('#conta').val()+"/valor/"+valor+"/format/json";
			operacao = "Deposito feito "+operacao;
		}
		else if($('[name="opcao"]:checked').attr('class') == "sacar"){
			url = "../ws/index.php/api/banco_ws/sacar/conta/"+$('#conta').val()+"/valor/"+valor+"/format/json";
			operacao = "Saque relizado "+operacao;
		}
		else if($('[name="opcao"]:checked').attr('class') == "transferir"){
			url = "../ws/index.php/api/banco_ws/transferir/conta/"+$('#conta').val()+"/valor/"+valor+"/origem/"+$('#contaOrigem').val()+"/format/json";
			operacao = "Transferencia realizada "+operacao;
		}
		
		
		$.ajax({
			url: url,
			success: function(data, textStatus, jqXHR){
				$('#retorno ol').append("<li>Seu saldo é R$ "+data.saldo+"</li>");
				$('input[type=number]').val('');
			}, 
		
			error: function(data, textStatus, errorThrown){
				obj = JSON.parse(data.responseText);
				$('#retorno ol').append("<li>"+obj.error+"</li>");
			}
		});
	});

});
</script>

</body>
</html>