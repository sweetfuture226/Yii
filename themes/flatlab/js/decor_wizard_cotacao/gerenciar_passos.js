jQuery.fn.extend({
    exists: function(){
        return this.length>0;
    }
});

function ifSelectNotEmpty(field, rules, i, options){
  if ($(field).find("option").length > 0 && 
      $(field).find("option:selected").length == 0) {
     // this allows the use of i18 for the error msgs
     return "* Campo obrigatório";
  }
}

function salvarTipologia(step_num){
    var valid = false;
    $.ajax({
        url: baseUrl + "/decorCotacao/salvarTipologia",
        type: 'POST',
        async: false,
        data: $("#decor-tipologia-"+step_num+"-form").serialize(),
        success: function(data){
            valid = true;
        },
        error: function(){
            document.getElementById('message').innerHTML = "Tipologia não pôde ser salva.";
            $('#btn_modal_open').click();
            valid = false;
        }
    });
    return valid;
}

function salvarPassoAtual(step_num){
    var valid = false;
    $.ajax({
        url: baseUrl + "/decorCotacao/salvarPassoAtual",
        type: 'POST',
        async: false,
        data: $("#decor-tipologia-"+step_num+"-form").serialize(),
        success: function(data){
            valid = true;
        },
        error: function(){
            document.getElementById('message').innerHTML = "Tipologia não pôde ser salva.";
            $('#btn_modal_open').click();
            valid = false;
        }
    });
    return valid;
}