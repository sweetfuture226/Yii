<li class="sub-menu">
    <a href="#top" onclick="clicar(this);"  title="">
        <span><?php echo Yii::t("smith",'1 - Termos e Condições') ?></span>
    </a>
</li>
<li id="aceitacao" class="sub-menu">
    <a href="#aceitacao-content" onclick="clicar(this);" title="">
        <span><?php echo Yii::t("smith",'2 - Aceitação') ?></span>
    </a>
</li>
<li id="alteracoes" class="sub-menu">
    <a href="#alteracoes-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'3 - Alterações') ?></span>
    </a>
</li>

<li id="capacidadeLegal" class="sub-menu">
    <a href="#capacidadeLegal-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'4 - Capacidade Legal') ?></span>
    </a>
</li>
<li id="descricao" class="sub-menu">
    <a href="#descricao-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'5 - Descrição dos serviços oferecidos pelo Vivasmith') ?></span>
    </a>
</li>
<li id="contaRegistro" class="sub-menu">
    <a href="#contaRegistro-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'6 - Conta de Registro') ?></span>
    </a>
</li>
<li id="faculdadesReservadas" class="sub-menu">
    <a><span><?php echo Yii::t("smith",'7 - Faculdade reservadas') ?></span></a>
    <ul class="sub">
        <li><a href="#faculdadesUtilizadores-content" title=""><?php echo Yii::t("termos",'Faculdades reservadas aos Utilizadores registrados') ?></a></li>
        <li><a href="#faculdadesVivaSmith-content" title=""><?php echo Yii::t("termos",'Faculdades reservadas ao Vivasmith') ?></a></li>
    </ul>
</li>
<li id="licencasUtilizacao" class="sub-menu">
    <a href="#licencas-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'8 - Licenças de Utilização') ?></span>
    </a>
</li>

<li id="privacidade" class="sub-menu">
    <a href="#privacidade-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'9 - Privacidade') ?></span>
    </a>
</li>

<li id="garantias" class="sub-menu">
    <a href="#garantias-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'10 - Ausência de garantias') ?></span>
    </a>
</li>
<li id="responsabilidade" class="sub-menu">
    <a href="#responsabilidade-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'11 - Limitação de responsabilidade') ?></span>
    </a>
</li>
<li id="miscelânea" class="sub-menu">
    <a href="#miscelanea-content" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'12 - Miscelânea') ?></span>
    </a>
</li>
<li id="jurisdicao" class="sub-menu">
    <a href="#jurisdicao-content" title="">
        <span><?php echo Yii::t("smith",'13 - Jurisdição e lei aplicável') ?></span>
    </a>
</li>

<script>
    function clicar(obj){
        var url = $(obj).attr('href');
        window.location.href = url;
    }
</script>