$(function() {
    //===== Datepicker =====//
    //$(".date").datepicker();
    $('.date').datepicker()
            .on('changeDate', function(ev) {
        $(this).datepicker('hide');
    });

    //===== Form validation =====//
    $(".validate_form").validate({
        rules: {
            // tela de edicao do perfil
            current_password: "required",
            "UserGroupsUser[password]": "required",
            "UserGroupsUser[password_again]": {
                equalTo: "#UserGroupsUser_password"
            },
            // tela de criacao de conta a pagar
            "ContaPagar[centro_custo_id]": "required",
            "ContaPagar[subcategoria_centro_custo_id]": "required",
            "ContaPagar[valor]": "required",
            // tela de criacao de conta a receber
            "ContaReceber[centro_custo_id]": "required",
            "ContaReceber[valor_bruto]": "required",
            // tela de criacao de nota fiscal
            "NotaFiscal[cliente_id]": "required",
            "NotaFiscal[numeracao]": "required",
            "NotaFiscal[data_emissao]": "required",
            "NotaFiscal[valor_bruto]": "required",
            "NotaFiscal[valor_liquido]": "required",
            // tela de criacao de conta financeira
            "ContaFinanceira[banco_id]": "required",
            "ContaFinanceira[agencia]": "required",
            "ContaFinanceira[conta]": "required",
            // tela de criacao de funcionario
            "Funcionario[nome]": "required",
            "Funcionario[cpf]": "required",
            "Funcionario[data_admissao]": "required",
            "Funcionario[salario]": "required",
            // tela de criacao de ambientes
            "DecorTipoAmbiente[nome]": "required",
            // tela de criacao de usuario
            "UserGroupsUser[username]": "required",
            "UserGroupsUser[password]": "required",
                    "UserGroupsUser[password_confirm]": {
                required: true,
                equalTo: "#UserGroupsUser_password"
            },
            // tela de criacao de Subcategoria de Centro de Custo
            "SubcategoriaCentroCusto[nome]": "required",
            "SubcategoriaCentroCusto[centro_custo_id]": "required",
            // tela de criacao de Tipo de Documento
            "TipoDocumento[nome]": "required",
            // tela de criacao de produto
            "DecorProduto[nome]": "required",
            "DecorProduto[tipo_tipologia_id]": "required",
            "DecorProduto[fornecedor_id]": "required",
            // tela de criacao de empresa
            "Empresa[nome]": "required",
        }
    });

});
