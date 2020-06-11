<li class="sub-menu">
    <a href="#top" onclick="clicar(this);"  title="">
        <span><?php echo Yii::t("smith",'1 - Política de privacidade') ?></span>
    </a>
</li>
<li id="registro" class="sub-menu">
    <a href="#registro-content" onclick="clicar(this);" title="">
        <span><?php echo Yii::t("smith",'2 - Registo de dados pessoais') ?></span>
    </a>
</li>
<li id="informacao" class="sub-menu">
    <a href="#informacao-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'3 - Informação recolhida') ?></span>
    </a>
</li>

<li id="finalidade" class="sub-menu">
    <a href="#finalidade-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'4 - Finalidade') ?></span>
    </a>
</li>
<li id="seguranca" class="sub-menu">
    <a href="#seguranca-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'5 - Medidas de segurança') ?></span>
    </a>
</li>
<li id="ceder" class="sub-menu">
    <a href="#ceder-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'6 - Cessão a terceiros') ?></span>
    </a>
</li>
<li id="modificacoes" class="sub-menu">
    <a href="#modificacoes-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'7 - Modificações') ?></span>
    </a>
</li>
<li id="hiperlinks" class="sub-menu">
    <a href="#hiperlinks-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'8 - Política de Hiperlinks') ?></span>
    </a>
</li>

<script>
    function clicar(obj){
        var url = $(obj).attr('href');
        window.location.href = url;
    }
</script>