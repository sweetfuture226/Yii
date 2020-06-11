<li>
    <a href="<?= Yii::app()->request->baseUrl ?>/help/Sobre" onclick="clicar(this);"  title="">
        <span><?php echo Yii::t("smith",'1 - Sobre este Guia de Usuário') ?></span>
    </a>
</li>
<li id="visaoGeral" class="sub-menu">
    <a   href="<?= Yii::app()->request->baseUrl ?>/help/VisaoGeral" onclick="clicar(this);" title="">
        <span><?php echo Yii::t("smith",'2 - Visão geral do Viva Smith') ?></span>
    </a>
    <ul class="sub">
        <li><a href="#subitem2-1" title=""><?php echo Yii::t("help",'Quero acompanhar métricas de produtividade') ?></a></li>
        <li><a href="#subitem2-2" title=""><?php echo Yii::t("help",'Quero gerenciar projetos') ?></a></li>
    </ul>
</li>
<li id="preparacao" class="sub-menu">
    <a href="<?= Yii::app()->request->baseUrl ?>/help/Preparacao" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'3 - Preparando a instituição') ?></span>
    </a>
    <ul class="sub">
        <li><a href="#subitem3-1" title=""><?php echo Yii::t("help",'Ritual de implantação') ?></a></li>
        <li><a href="#subitem3-2" title=""><?php echo Yii::t("help",'Treinamento de usuários') ?></a></li>
    </ul>
</li>

<li id="instalacao" class="sub-menu">
    <a href="<?= Yii::app()->request->baseUrl ?>/help/Instalacao" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'4 - Instalação') ?></span>
    </a>
    <ul class="sub">
        <li><a href="#subitem4-1" title=""><?php echo Yii::t("help",'Requisitos das estações de trabalho e rede') ?></a></li>
        <li><a href="#subitem4-2" title=""><?php echo Yii::t("help",'Backup de informações') ?></a></li>
        <li><a href="#subitem4-3" title=""><?php echo Yii::t("help",'Atualizações de funcionalidades e correções') ?></a></li>
    </ul>
</li>
<li id="conhecendo" class="sub-menu">
    <a href="<?= Yii::app()->request->baseUrl ?>/help/Conhecendo" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'5 - Conhecendo o Viva Smith') ?></span>
    </a>
    <ul class="sub">
        <li><a href="#subitem5-1" title=""><?php echo Yii::t("help",'Página inicial do Viva Smith') ?></a></li>
        <li><a href="#subitem5-2" title=""><?php echo Yii::t("help",'Relatório de Atividades em Tempo Real') ?></a></li>
    </ul>
</li>
<li id="moduloProdutividade" class="sub-menu">
    <a href="<?= Yii::app()->request->baseUrl ?>/help/ModuloProdutividade" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'6 - Módulo de Produtividade') ?></span>
    </a>
    <ul class="sub">
        <li><a href="#subitem6-1" title=""><?php echo Yii::t("help",'Relatório por equipe') ?></a></li>
        <li><a href="#subitem6-2" title=""><?php echo Yii::t("help",'Relatório individual diário') ?></a></li>
        <li><a href="#subitem6-3" title=""><?php echo Yii::t("help",'Relatório em dias') ?></a></li>
        <li><a href="#subitem6-4" title=""><?php echo Yii::t("help",'Relatório por custo') ?></a></li>
        <li><a href="#subitem6-5" title=""><?php echo Yii::t("help",'Relatório de ranking') ?></a></li>
        <li><a href="#subitem6-6" title=""><?php echo Yii::t("help",'Relatório de consumo de energia') ?></a></li>
        <li><a href="#subitem6-7" title=""><?php echo Yii::t("help",'Relatório de hora extra') ?></a></li>
        <li><a href="#subitem6-8" title=""><?php echo Yii::t("help",'Relatório de ponto') ?></a></li>
        <li><a href="#subitem6-9" title=""><?php echo Yii::t("help",'Relatório de dias sem produtividade') ?></a></li>
    </ul>
</li>
<li id="moduloProgramasSites" class="sub-menu">
    <a href="<?= Yii::app()->request->baseUrl ?>/help/ModuloProgramasSites" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'7 - Módulo de Programas e Sites') ?></span>
    </a>
    <ul class="sub">
        <li><a href="#subitem7-1" title=""><?php echo Yii::t("help",'Relatório geral') ?></a></li>
        <li><a href="#subitem7-2" title=""><?php echo Yii::t("help",'Relatório individual') ?></a></li>
        <li><a href="#subitem7-3" title=""><?php echo Yii::t("help",'Relatório de programas blacklist') ?></a></li>
    </ul>
</li>
<li id="moduloMetricas" class="sub-menu">
    <a href="<?= Yii::app()->request->baseUrl ?>/help/ModuloMetricas" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'8 - Módulo de Métricas') ?></span>
    </a>
    <ul class="sub">
        <li><a href="#subitem8-1" title=""><?php echo Yii::t("help",'Gerenciar Métricas') ?></a></li>
        <li><a href="#subitem8-2" title=""><?php echo Yii::t("help",'Cadastrar Métricas') ?></a></li>
    </ul>
</li>
<li id="moduloContratos" class="sub-menu">
    <a href="<?= Yii::app()->request->baseUrl ?>/help/ModuloContratos" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'9 - Módulo de Contratos') ?></span>
    </a>
    <ul class="sub">
        <li><a href="#subitem9-1" title=""><?php echo Yii::t("help",'Gerenciar Contratos') ?></a></li>
        <li><a href="#subitem9-2" title=""><?php echo Yii::t("help",'Cadastrar Contrato') ?></a></li>
        <li><a href="#subitem9-3" title=""><?php echo Yii::t("help",'Relatório de contratos') ?></a></li>
        <li><a href="#subitem9-4" title=""><?php echo Yii::t("help",'Relatório de produtividade') ?></a></li>
        <li><a href="#subitem9-5" title=""><?php echo Yii::t("help",'Relatório de acompanhamento') ?></a></li>
        <li><a href="#subitem9-6" title=""><?php echo Yii::t("help",'Atividades externas') ?></a></li>
        <li><a href="#subitem9-7" title=""><?php echo Yii::t("help",'Disciplinas/Fases') ?></a></li>
    </ul>
</li>
<li id="moduloConfiguracoes" class="sub-menu">
    <a href="<?= Yii::app()->request->baseUrl ?>/help/ModuloConfiguracoes" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'10 - Módulo de Configurações') ?></span>
    </a>
    <ul class="sub">
        <li><a href="#subitem10-1" title=""><?php echo Yii::t("help",'Colaboradores') ?></a></li>
        <li><a href="#subitem10-2" title=""><?php echo Yii::t("help",'Equipes') ?></a></li>
        <li><a href="#subitem10-3" title=""><?php echo Yii::t("help",'Coordenadores') ?></a></li>
        <li><a href="#subitem10-4" title=""><?php echo Yii::t("help",'Programas permitidos') ?></a></li>
        <li><a href="#subitem10-5" title=""><?php echo Yii::t("help",'Sites permitidos') ?></a></li>
        <li><a href="#subitem10-6" title=""><?php echo Yii::t("help",'Parâmetros gerais') ?></a></li>
        <li><a href="#subitem10-7" title=""><?php echo Yii::t("help",'Baixar instalador') ?></a></li>
    </ul>
</li>
<li class="sub-menu">
    <a href="<?= Yii::app()->request->baseUrl ?>/help/Mobile" title="">
        <span><?php echo Yii::t("smith",'11 - Acesso Mobile') ?></span>
    </a>
</li>
<li id="duvidas" class="sub-menu">
    <a href="<?= Yii::app()->request->baseUrl ?>/help/Duvidas" onclick="clicar(this);">
        <span><?php echo Yii::t("smith",'12 - Dúvidas') ?></span>
    </a>
    <ul class="sub">
        <li><a href="#subitem12-1" title=""><?php echo Yii::t("help",'Aspectos jurídicos') ?></a></li>
    </ul>
</li>
<li class="sub-menu">
    <a href="<?= Yii::app()->request->baseUrl ?>/help/FaleConosco" title="">
        <span><?php echo Yii::t("smith",'13 - Fale conosco') ?></span>
    </a>
</li>

<script>
    function clicar(obj){
        var url = $(obj).attr('href');
        window.location.href = url;
    }
</script>