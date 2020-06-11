$(document).ready(function(){
    var form  = $("#movimentacao_upload_csv-form");
    
    form.submit(function(){
        var centro_custos = $('select[id$="_centro_custo_id"]');
        var checkBoxs = $('input[id^="ck_"]');
        var valido = true;
        var tmh = centro_custos.length;
        
        for(var i = 0; i < tmh;i++){
            var id_ck = checkBoxs[i].id;
            var chk = $("#"+id_ck);
            var ck = chk.prop('checked');
            var id = centro_custos[i].id;
            var cc = $("#"+id+"_chzn");

            if(ck == 1){
                var centro_custo = centro_custos[i].value;
                if(centro_custo == ""){
                    var style = cc.attr("style");
                    cc.attr("style",style+" border: 1px solid red;");
                    valido = false;
                }
            }
            else{
                cc.removeAttr("style");
                cc.attr("style","width: 248px;");
                valido = false;
            }
            
        }
        
        if(!valido){
            var msg = "Centro de Custo é um campo obrigatório, por favor preencha-o ";
            alert(msg);
            return false;
        }
        
        return true;
    });
    
});