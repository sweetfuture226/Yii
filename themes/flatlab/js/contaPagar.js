$(function() {  

    var parcelas = $("#parcelas");
    var parcelados = $("#parcelados");
    var qtd = $("#qtd");
    var qnt_parcelas = qtd.val();
    var valor = $("#ContaPagar_valor");
    var vencimento = $("#ContaPagar_data_vencimento");

    valor.blur(function(){

        qnt_parcelas = qtd.val();

        if(qnt_parcelas > 1){
            value = valor.val();
            value = string2float(value);
            renderiza(qnt_parcelas,parcelados,value);
        }

    });

    qtd.change(function(){
        qnt_parcelas = qtd.val();
        value = valor.val();
        value = string2float(value);
        renderiza(qnt_parcelas,parcelados,value);
    });

    $("#conta-pagar-form").submit(function(){

       if(qnt_parcelas > 1){

            var confirma;
            var message = "";
            var title = "Resumo das parcelas";
            var p;
            var total = string2float($("input[name='ContaPagar[valor]']").val());
            var v = parseFloat('0');
            var vpc = parseFloat('0');
            var i = 0;

            var ln = '\n_____________________________________________________\n\n';
            message += title+ln;
            
            for(i=0;i<qnt_parcelas;i++){
                p = parseFloat(string2float($("input[name^='ContaPagar[parcelados]["+i+"][valor]']").val())).toFixed(2);
                v = v + parseFloat(p);
                message += "Parcela "+(i+1)+": R$ "+float2string(p)+"\n";
            }

            v = v.toFixed(2);
            message += "\nSoma: R$ "+float2string(v);

            total = parseFloat(total).toFixed(2);
            //total = toUS(total).toFixed(2);
            
            if(v != total)
               message+=ln+"Soma não condiz com o valor total. Prosseguir?";
                
            confirma = confirm(message, title);

            return confirma;

       }

    });
});
   
function renderiza(qnt_parcelas,parcelados,valor){
    parcelados.html("");
    if(qnt_parcelas < 13 && qnt_parcelas > 1){
        var index;
        var i = 0;

        var valores = new Array();
        var valorAux = parseFloat('0');
        var valorDiferenca = parseFloat('0');

        if (isNaN(valor))
        {
            for(i = 0; i < qnt_parcelas;i++)
            {
                valores[i] = parseFloat('0');
                valorAux = parseInt(parseFloat(valorAux) + parseFloat(valores[i]*100));
            }
        }
        else 
        {
            for(i = 0; i < qnt_parcelas;i++)
            {
                valores[i] = (Math.round(valor * 100.0 / qnt_parcelas) / 100).toFixed(2);
                valorAux = parseInt(parseFloat(valorAux) + parseFloat(valores[i]*100));
            }
        }

        valorDiferenca = parseFloat(valor*100) - parseFloat(valorAux);

        if(isFinite(valorDiferenca))
            valores[qnt_parcelas-1] = (parseFloat(valores[qnt_parcelas-1]*100 + valorDiferenca)/100).toFixed(2);

        for(i = 0; i < qnt_parcelas;i++){

            $("#index").attr("value",i);
            index = $("#index").val();
            var a = parseInt(index)+1;
            var valorParcela = valores[i];

            valorParcela = float2string(valorParcela);

            var evento = '';
            if(index == 0)
                evento = 'onchange=changeDates(this.value);';

            var template = '\
                    <div style="clear:both"></div>\n\
                    <div class="form-group  col-lg-12" style="padding: 0">\n\
                        <div class="form-group  col-lg-2">\n\
                            <label for="ContaPagar_valor_'+index+'">Valor da  '+a+'ª parcela</label>\n\
                            <input class="dinheiro form-control" name="ContaPagar[parcelados]['+index+'][valor]" id="ContaPagar_valor_'+index+'" class="valor text" type="text" value="'+valorParcela+'">\n\
                        </div>\n\
                        <div class="form-group  col-lg-2">\n\
                            <label for="ContaPagar_'+index+'_data_vencimento">Data de vencimento</label>\n\
                            <input class="date form-control" name="ContaPagar[parcelados]['+index+'][data_vencimento]" id="ContaPagar_'+index+'_data_vencimento" type="text" size="10" '+evento+'>\n\
                        </div>\n\
                    </div>\n\
                    ';

            parcelados.append(template);

            $("#index").removeAttr("value");
            $("#index").attr("value",a);

        }

        $(".dinheiro").autoNumeric('destroy');
        $(".dinheiro").autoNumeric('init',{mDec: 5, aSep: '.', aDec: ',', aPad: 2, vMax: 9999999999999999999999999999999999999999999.99999});
        
        $(".date").datepicker({ 
            autoSize: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1940:2028',
            dateFormat: 'dd/mm/yy'
        });	
   }

}

//Modifica datas subsequentes a partir da data da primeira parcela
function changeDates(data)
{
    var qtd = $("#qtd").val();
    if(data != '')
    {
        var datas = data.split('/');
        var dia = datas[0];
        dia = parseInt(dia);
        var mes = datas[1];
        mes = parseInt(mes);
        var ano = datas[2];
        ano = parseInt(ano);

        for(i=1; i<qtd;i++)
        {
            if(mes != 12)
            {
                mes = mes + 1;
            }
            else
            {
                mes = 1;
                ano = ano+1;
            }

            novo_dia = dia;
            novo_mes = mes;

            if(dia > 28)
            {
                if(mes == 2)
                {
                    novo_dia = lastDayOfMonth(ano,mes);
                }
                else if (dia == 31)
                {
                    novo_dia = lastDayOfMonth(ano,mes);
                }
            }

            if(mes < 10)
                novo_mes = "0"+mes;

            if(dia < 10)
                novo_dia = "0"+dia;

            var nova_data = novo_dia+"/"+novo_mes+"/"+ano;
            $('#ContaPagar_'+i+'_data_vencimento').val(nova_data);
        }
    }
}

function lastDayOfMonth(Year, Month)
{
    if(Month == 12)
    {
        return 31;
    }
    return(new Date((new Date(Month+1 +"/"+ 1+"/"+ Year ))-1)).getDate();
}

//Modifica o percentual da parcela de acordo com o valor informado
function parcelapercent(id,valor_parcela) {

    var id_valor = id.substr(17);
    var valor_total = $("#ContaPagar_valor").val();
    valor_total = string2float(valor_total);
    valor_parcela =  valor_parcela.substr(3);
    valor_parcela = string2float(valor_parcela);
    var valor_percentual = (100*valor_parcela/valor_total).toFixed(2);
    if(!isFinite(valor_percentual))
        valor_percentual = '';

    document.getElementById('percentual_'+id_valor).value = valor_percentual;
      
    return false;
}

//Modifica o valor da parcela de acordo com o percentual informado
function parcelavalor(id,valor_percentual)
{
    var id_percentual = id.substr(11);
    var percent_aux = parseFloat('0');
    var qtd = $("#qtd").val();

    for(i=0; i < qtd;i++)
    {
        aux = parseInt(string2float(document.getElementById('percentual_'+i).value)*100);
        percent_aux = parseFloat(percent_aux) + parseFloat(aux);
    }
    percent_aux = percent_aux/100;

    var valor_aux = parseFloat('0');

    if(percent_aux == 100)
    {
        for(i=0; i < qtd;i++)
        {
            if(i != id_percentual)
            {
                var aux = string2float(document.getElementById('ContaPagar_valor_'+i).value);
                valor_aux = parseFloat(valor_aux) + parseFloat(aux);
            }
        }
    }

    var valor_total = document.getElementById("ContaPagar_valor").value;
    valor_total = string2float(valor_total);
    valor_percentual = string2float(valor_percentual);
    var valor_parcela = (valor_total*valor_percentual/100).toFixed(2);

    if((percent_aux == 100) && (valor_aux != valor_total) && (isFinite(valor_aux)))
    {
        var diferenca = parseFloat(valor_total) - parseFloat(valor_aux);
        valor_parcela = parseFloat(diferenca).toFixed(2);
    }

    if(!isFinite(valor_parcela))
        valor_parcela = '';
    else
        valor_parcela = float2string(valor_parcela);

    document.getElementById('ContaPagar_valor_'+id_percentual).value = valor_parcela;
      
    return false;
}

//Converte 10.000,50 para 10000.50
function string2float(string) {
    string = string.replace(/\./g,"");
    string = string.replace(",",".");
    string = parseFloat(string);

    return string;
}
