
// ========  verifica se existe algum CEP descrito =======//
function checkCEP(){
        if($('#Endereco_cep').val() == ''){
            alert('Favor preencher o campo CEP corretamente.');
        }
    }
    
// ======= busca o logradouro, bairro, cidade e UF do site republicavirtual e joga nos campos respectivos
function getEndereco() {
    // Se o campo CEP não estiver vazio
    if($.trim($("#Endereco_cep").val()) != ""){
            $.getScript("http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#Endereco_cep").val(), function(){
                    // o getScript dá um eval no script, então é só ler!
                    //Se o resultado for igual a 1
                    if(resultadoCEP["resultado"]==1){
                            // troca o valor dos elementos
                            if(resultadoCEP["tipo_logradouro"]){
                                $("#Endereco_logradouro").val(unescape(resultadoCEP["tipo_logradouro"])+" "+unescape(resultadoCEP["logradouro"]));
                            }
                            else{
                                $("#Endereco_logradouro").val(unescape(''));
                            } 
                            $("#Endereco_bairro").val(unescape(resultadoCEP["bairro"]));
                            $("#Endereco_cidade").val(unescape(resultadoCEP["cidade"]));
                            $("#Endereco_estado").val(unescape(getEstado(resultadoCEP["uf"])));
                            /*if(resultadoCEP["uf"]){
                                $("#pais").val("Brasil");
                            }
                            else{
                                $("#pais").val("");
                            } */
                    }else{
                        if($("#Endereco_cep").val() != "")
                            alert("Endereço não encontrado.");
                    }
            });
    }
}

// ======== pega a UF e retorna o nome do estado respectivo
function getEstado(uf){
    switch (uf) {
        case 'AC':
            return 'Acre';
        case 'AL':
            return 'Alagoas';
        case 'AP':
            return 'Amapá';
        case 'AM':
            return 'Amazonas';
        case 'BA':
            return 'Bahia';
        case 'CE':
            return 'Ceará';
        case 'DF':
            return 'Distrito Federal ';
        case 'ES':
            return 'Espírito Santo';
        case 'GO':
            return 'Goiás';
        case 'MA':
            return 'Maranhão';
        case 'MT':
            return 'Mato Grosso';
        case 'MS':
            return 'Mato Grosso do Sul';
        case 'MG':
            return 'Minas Gerais';
        case 'PA':
            return 'Pará';
        case 'PB':
            return 'Paraíba';
        case 'PR':
            return 'Paraná';
        case 'PE':
            return 'Pernambuco';
        case 'PI':
            return 'Piauí';
        case 'RJ':
            return 'Rio de Janeiro';
        case 'RN':
            return 'Rio Grande do Norte';
        case 'RS':
            return 'Rio Grande do Sul';
        case 'RO':
            return 'Rondônia';
        case 'RR':
            return 'Roraima';
        case 'SC':
            return 'Santa Catarina';
        case 'SP':
            return 'São Paulo';
        case 'SE':
            return 'Sergipe';
        case 'TO':
            return 'Tocantins';
        default:
            return '';
    }
}

function boxy_view(){
    $('.view').unbind("click");
    $('.view').boxy({title:'Dados'});
}

function input_valor(){
    $(".valor").each(function(){
        $(this).maskMoney({
            symbol:'R$ ',
            showSymbol:true,
            thousands:'.',
            decimal:',',
            symbolStay: false
        });
        $(this).removeClass("valor");
    });
}

$(function() {
        //===== loading =====//
        $("body").on({
            ajaxStart: function() { 
                $(this).addClass("loading"); 
            },
            ajaxStop: function() { 
                $(this).removeClass("loading"); 
            }    
        });
		
        //===== Light box - boxy =====//
        
        $('.view').boxy({title:'Dados'});
        $(document).keyup(function(event) {
            if((Boxy.isModalVisible()) && (boxy != undefined) && (event.keyCode==27)){
                boxy.hide();
            }
        });
    
    //============= Chosen Select =====================//
    $(".chzn-select").chosen({no_results_text: "Não encontrado"}); 
    $(".chzn-select-deselect").chosen({allow_single_deselect:true});
    
    //===== Form elements masks =====//
	
        $(".telefone").mask("(99)9999-9999",{placeholder:" "});
        $(".cpf").mask("999.999.999-99",{placeholder:" "}); 
        $(".cep").mask("99999-999",{placeholder:" "}); 
        $(".cnpj").mask("99.999.999/9999-99",{placeholder:" "}); 
        $(".data").mask("99/99/9999",{placeholder:" "}); 
        $(".hora").mask("99:99",{placeholder:" "}); 
        $(".previsto").mask("99:99:99",{placeholder:" "});
        
        input_valor();
	    
    //===== Form validation engine =====//

	$(".valid").validationEngine();
        
        $("form").submit(function(){
            $(".validate_select").each(function(){
               var id = $(this).attr("id");
               if (($(this).hasClass("validate[required]")) && (($(this).val()=="") || ($(this).val()==null))) {
                   $("#"+id+"_chzn").validationEngine("showPrompt", "* Campo obrigatótio", "error", "", true);
                   return false;
               }else{
                   $("#"+id+"_chzn").validationEngine("hide");
               }
            });
        });
	
        //===== Datepickers =====//
                
	$(".date").datepicker({ 
		autoSize: true,
                changeMonth: true,
                changeYear: true,
                yearRange: '1940:2030',
		dateFormat: 'dd/mm/yy'
	});	
	
	$(".month").datepicker({ 
		autoSize: true,
        changeMonth: true,
        changeYear: false,
        yearRange: '2013:2030',
		dateFormat: 'mm'
	});	


	//===== Tabs =====//
        
        $(".div_tabs").createTabs();
        
        
});

function hideIconsExportPrint(){
    $("#printButton").css("display","none");
    $("#exportButton").css("display","none");
}
