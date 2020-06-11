<?php
$this->breadcrumbs = array(
    'API' => array('index')
);
?>
    <p>
        <?php echo Yii::t('smith', 'Esta seção é dedicada aos desenvolvedores, os quais poderão utilizar a API para realizar requisições de informações
        que os relatórios fornecem e a possibilidade de cadastro de atividades externas e atualização de informações dos
        colaboradores') ?>
    </p>
    <style>
        li.L0, li.L1, li.L2, li.L3,
        li.L5, li.L6, li.L7, li.L8 {
            list-style-type: decimal !important;
        }
    </style>
    <div class="col-lg-12">
        <section class="panel">
            <div class="panel-body">
                <div class="dd" id="nestable_list_1">
                    <ol class="dd-list">
                        <li class="dd-item dd-collapsed">
                            <div class="dd-handle">1. <?php echo Yii::t('smith', 'Introdução') ?></div>
                            <ol class="dd-list">
                                <li class="dd-item">
                                    <div class="dd-handle">
                                        <p>
                                            <?php echo Yii::t('smith', 'A API Viva Smith é uma aplicação que permite acesso aos resultados dos relatórios
                                        existentes no sistema para que os clientes possam utilizar esses dados em aplicações
                                        próprias.') ?>
                                        
                                        <br><br>
                                            <?php echo Yii::t('smith', 'Para ter acesso às informações o cliente precisará fazer uma requisição ao sistema
                                        utilizando a biblioteca cURL para transferência de dados ou a biblioteca em PHP
                                        disponibilizada para download no menu da API. O retorno das requisições será em JSON
                                        (JavaScript Object Notation).') ?>

                                        </p>
                                    </div>
                                </li>
                            </ol>
                        </li>
                        <li class="dd-item dd-collapsed">
                            <div class="dd-handle">2. <?php echo Yii::t('smith', 'Modos de Uso') ?></div>
                            <ol class="dd-list">
                                <li class="dd-item">
                                    <div class="dd-handle">2.1 <?php echo Yii::t('smith', 'Chamada direta') ?></div>
                                    <ol class="dd-list">
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                <p>
                                                    2.1.1 <?php echo Yii::t('smith', 'Utilizar um protocolo de transferência de dados via POST.') ?> </p>
                                                &nbsp&nbsp&nbsp&nbsp <?php echo Yii::t('smith', 'Exemplo utilizando curl pela linha de comando:') ?>
                                                <br>
                                                <pre class="prettyprint lang-bsh linenums">
curl --data "action=getTeamMonthlyProductivity&serial=A7DC-4E4S-XAF0-HI6M&filter=all&iniDate=2016-02-01&endDate=2016-02-29" https://vivasmith.com/api/execute/<br><br>
                                            </pre>
                                            </div>
                                        </li>
                                    </ol>
                                </li>
                                <li class="dd-item dd-collapsed">
                                    <div class="dd-handle"> 2.2 <?php echo Yii::t('smith', 'Biblioteca em PHP') ?></div>
                                    <ol class="dd-list">
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                <p>
                                                    2.2.1 <?php echo Yii::t('smith', 'Copiar a biblioteca para o sistema que chamará a API.') ?></p>
                                                &nbsp&nbsp&nbsp&nbsp <?php echo Yii::t('smith', 'Baixe a biblioteca para PHP da API do Viva') ?>
                                                Smith <?= CHtml::link('aqui', '../public/api/smithApi.zip') ?>.
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                <p>
                                                    2.2.2 <?php echo Yii::t('smith', 'Importar o arquivo que contém a classe da API para o arquivo que a executará.') ?></p>
                                                <pre class="prettyprint lang-php linenums">
require_once('smithApi/smithApi.php');
                                            </pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                <p>
                                                    2.2.3 <?php echo Yii::t('smith', 'Instanciar um novo objeto e chamar uma função, enviando os dados como parâmetro.') ?></p>
                                                &nbsp&nbsp&nbsp&nbspExemplo:
                                                <pre class="prettyprint lang-php linenums">
$output = new SmithApi();
$teste = $output->getTeamMonthlyProductivity('A7DC-4E4S-XAF0-HI6M', 'all', '2016-02-01', '2016-02-29');
                                            </pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                2.2.4 <?php echo Yii::t('smith', 'Exemplo') ?>:<br>
<pre class="prettyprint lang-php linenums">
require_once('smithApi/smithApi.php');
class CustomController {
    public function actionIndex() {
        $output = new SmithApi();
        $result = $output->getTeamMonthlyProductivity('A7DC-4E4S-XAF0-HI6M', 'all', '2016-02-01', '2016-02-29');
        var_dump($result);
    }
}
</pre>
                                            </div>
                                        </li>
                                    </ol>
                                </li>
                            </ol>
                        </li>
                        <li class="dd-item dd-collapsed">
                            <div class="dd-handle">3. <?php echo Yii::t('smith', 'Variáveis e Funções') ?></div>
                            <ol class="dd-list">
                                <li class="dd-item dd-collapsed">
                                    <div class="dd-handle">3.1 <?php echo Yii::t('smith', 'Variáveis') ?></div>
                                    <ol class="dd-list">
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.1 action
<pre>
<?php echo Yii::t('smith', 'Descrição: nome da função que será executada no sistema') ?>
    <?php echo Yii::t('smith', 'Tipo: string') ?>
    <?php echo Yii::t('smith', 'Formato:') ?>: getNomeDaFuncao
    <?php echo Yii::t('smith', 'Obs: Variável obrigatória em todas as funções.') ?>
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.2 serial
<pre>
<?php echo Yii::t('smith', 'Descrição: chave individual da empresa cadastrada no sistema do Viva Smith, que servirá de autenticação para a API') ?>
    <?php echo Yii::t('smith', 'Tipo: string') ?>
    <?php echo Yii::t('smith', 'Formato') ?>: XXXX-XXXX-XXXX-XXXX
    <?php echo Yii::t('smith', 'Obs: Variável obrigatória em todas as funções.') ?>
    <?php echo Yii::t('smith', 'Veja seu serial') ?>
    <?= CHtml::link('aqui', 'https://app.vivasmith.com/empresaHasParametro/instalador', array('target' => '_blank')) ?>
    .
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.3 date
<pre>
<?php echo Yii::t('smith', 'Descrição: data única para funções que precisam somente de data inicial ou final') ?>
    <?php echo Yii::t('smith', 'Tipo: string') ?>
    <?php echo Yii::t('smith', 'Formato') ?>: yyyy-mm-dd
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.4 iniDate
<pre>
<?php echo Yii::t('smith', 'Descrição: data inicial para funções que aceitam períodos variáveis') ?>
    <?php echo Yii::t('smith', 'Tipo: string') ?>
    <?php echo Yii::t('smith', 'Formato') ?>: yyyy-mm-dd
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.5 endDate
<pre>
<?php echo Yii::t('smith', 'Descrição: data final para funções que aceitam períodos variáveis') ?>
    <?php echo Yii::t('smith', 'Tipo: string') ?>
    <?php echo Yii::t('smith', 'Formato') ?>: yyyy-mm-dd
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.6 type
<pre>
<?php echo Yii::t('smith', 'Descrição: filtro para captura de dados das funções.') ?>
    <?php echo Yii::t('smith', 'Tipo: string') ?>
    <?php echo Yii::t('smith', 'Formato') ?>: 'day', 'month' , 'year', 'team' ,'employee' ou
'contract'
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.7 filter
<pre>
<?php echo Yii::t('smith', 'Descrição: filtro para captura de dados das funções.') ?>
    <?php echo Yii::t('smith', 'Tipo: string') ?>
    <?php echo Yii::t('smith', 'Formato') ?>: 'all', 'id_equipe', 'id_colaborador' ou
'id_contrato'
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.8 id
<pre>
<?php echo Yii::t('smith', 'Descrição: identificador do colaborador.') ?>
<?php echo Yii::t('smith', 'Tipo: int') ?>
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.9 name
<pre>
<?php echo Yii::t('smith', 'Descrição: nome do colaborador definido para atualização.') ?>
<?php echo Yii::t('smith', 'Tipo: string') ?>
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.10 lastName
<pre>
<?php echo Yii::t('smith', 'Descrição: sobrenome do colaborador definido para atualização.') ?>
<?php echo Yii::t('smith', 'Tipo: string') ?>
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.11 email
<pre>
<?php echo Yii::t('smith', 'Descrição: email do colaborador definido para atualização.') ?>
<?php echo Yii::t('smith', 'Tipo: string') ?>
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.12 salary
<pre>
<?php echo Yii::t('smith', 'Descrição: salário do colaborador definido para atualização.') ?>
    <?php echo Yii::t('smith', 'Tipo: float') ?>
    <?php echo Yii::t('smith', 'Formato') ?>: 2000.75
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.13 weeklyWorkload
<pre>
<?php echo Yii::t('smith', 'Descrição: carga horária semanal do colaborador definido para atualização.') ?>
    <?php echo Yii::t('smith', 'Tipo: int') ?>
    <?php echo Yii::t('smith', 'Formato') ?>: 20, 30, 40
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.14 team
<pre>
<?php echo Yii::t('smith', 'Descrição: equipe do colaborador definido para atualização.') ?>
    <?php echo Yii::t('smith', 'Tipo: int') ?>
    <?php echo Yii::t('smith', 'Formato') ?>: getTeams
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.15 contract
<pre>
<?php echo Yii::t('smith', 'Descrição: contrato informado para cadastro de atividade externa.') ?>
    <?php echo Yii::t('smith', 'Tipo: int') ?>
    <?php echo Yii::t('smith', 'Formato') ?>: getContracts
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.16 employee
<pre>
<?php echo Yii::t('smith', 'Descrição: colaborador informado para cadastro de atividade externa.') ?>
    <?php echo Yii::t('smith', 'Tipo: int') ?>
    <?php echo Yii::t('smith', 'Formato') ?>: getEmployees
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.17 description
<pre>
<?php echo Yii::t('smith', 'Descrição: descrição informada para cadastro de atividade externa.') ?>
<?php echo Yii::t('smith', 'Tipo: string') ?>
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.18 departure_time
<pre>
<?php echo Yii::t('smith', 'Descrição: horário de saída informada para cadastro de atividade externa.') ?>
    <?php echo Yii::t('smith', 'Tipo: string') ?>
    <?php echo Yii::t('smith', 'Formato') ?>: HH:MM
</pre>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.1.19 time_of_arrival
<pre>
<?php echo Yii::t('smith', 'Descrição: horário de chegada informada para cadastro de atividade externa.') ?>
    <?php echo Yii::t('smith', 'Tipo: string') ?>
    <?php echo Yii::t('smith', 'Formato') ?>: HH:MM
</pre>
                                            </div>
                                        </li>
                                    </ol>
                                </li>
                                <li class="dd-item dd-collapsed">
                                    <div class="dd-handle">3.2 Funções</div>
                                    <ol class="dd-list">
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.1 getTeams<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: lista das equipes cadastradas para ser utilizada como filtro das chamadas das funções') ?>
    <?php echo Yii::t('smith', 'Parâmetros: serial') ?>
    <?php echo Yii::t('smith', 'Chamada') ?>: getTeams($serial);
</pre>

                                                <?php echo Yii::t('smith', 'Variáveis de retorno:') ?>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'identificador') ?></td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Identificador da equipe') ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.2 getEmployees<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: lista dos colaboradores cadastrados para ser utilizada como filtro das chamadas das funções') ?>
    <?php echo Yii::t('smith', 'Parâmetros: serial') ?>
    <?php echo Yii::t('smith', 'Chamada') ?>: getEmployees($serial);
</pre>
                                                <?php echo Yii::t('smith', 'Variáveis de retorno:') ?>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'identificador') ?></td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Identificador do colaborador') ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.3 getContracts<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: lista dos contratos cadastrados para ser utilizada como filtro das chamadas das funções') ?>
    <?php echo Yii::t('smith', 'Parâmetros: serial') ?>
    <?php echo Yii::t('smith', 'Chamada') ?>: getContracts($serial);
</pre>
                                                <?php echo Yii::t('smith', 'Variáveis de retorno:') ?>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'identificador') ?></td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Identificador do contrato') ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.4 getTeamMonthlyProductivity<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: Produtividade das equipes no período de datas escolhido') ?>
    <?php echo Yii::t('smith', 'Parâmetros') ?>: serial, filter = ['all', 'id_equipe'] , iniDate , endDate
    <?php echo Yii::t('smith', 'Chamada') ?>: getTeamMonthlyProductivity($serial, $filter, $iniDate, $endDate);
</pre>
                                                <?php echo Yii::t('smith', 'Variáveis de retorno:') ?>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>status_code</td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Código do status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>status</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>done</td>
                                                                                <td>string</td>
                                                                                <td>0.00%</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Produzido') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>goal</td>
                                                                                <td>string</td>
                                                                                <td>0.00%</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Meta') ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.5 getEmployeeProductivity<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: Produtividade dos colaboradores no período selecionado') ?>
    <?php echo Yii::t('smith', 'Parâmetros') ?>: serial, type = ['day', 'month', 'year'], filter = ['all', 'id_colaborador'], iniDate , endDate
    <?php echo Yii::t('smith', 'Chamada') ?>: getEmployeeProductivity($serial, $type, $filter, $iniDate, $endDate);
</pre>
                                                <?php echo Yii::t('smith', 'Variáveis de retorno:') ?>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>status_code</td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Código do status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>status</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>done</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Tempo Produzido') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>goal</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Meta') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>average</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Média') ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.6 getProductivityCost<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: Comparativo produtividade x custo') ?>
    <?php echo Yii::t('smith', 'Parâmetros') ?>: serial, type = ['all', 'team', 'employee'], filter = ['all', 'id_equipe', 'id_colaborador'], iniDate, endDate
Chamada: getProductivityCost($serial, $type, $filter, $iniDate, $endDate);
</pre>
                                                Variáveis de retorno:
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith',
                                                                                        'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>status_code</td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Código do status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>status</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>brl</td>
                                                                                <td>array</td>
                                                                                <td>array[ ]</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Valores em Real brasileiro') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>usd</td>
                                                                                <td>array</td>
                                                                                <td>array[ ]</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Valores em Dólar americano') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>floatDoneCost</td>
                                                                                <td>float</td>
                                                                                <td>0.00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Custo em produção') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>formatDoneCost</td>
                                                                                <td>string</td>
                                                                                <td>R$0,00 ou $0.00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Custo em produção') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>floatAbsentCost</td>
                                                                                <td>float</td>
                                                                                <td>0.00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Custo em ausência do computador') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>formatAbsentCost</td>
                                                                                <td>string</td>
                                                                                <td>R$0,00 ou $0.00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Custo em ausência do computador') ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.7 getOvertimeProductivity<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: Produtividade dos colaboradores em hora extra') ?>
    <?php echo Yii::t('smith', 'Parâmetros') ?>: serial, type = ['all', 'team', 'employee'], filter = ['all', 'id_equipe', 'id_colaborador'], iniDate, endDate
    <?php echo Yii::t('smith', 'Chamada') ?>: getOvertimeProductivity($serial, $type, $filter, $iniDate, $endDate);
</pre>
                                                Variáveis de retorno:
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith', 'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>status_code</td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith', 'Código do status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>status</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith', 'Status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>time</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith', 'Tempo') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>productivity</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith', 'Produtividade') ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.8 getAttendanceReport<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: Horários de entrada e saída dos colaboradores') ?>

    <?php echo Yii::t('smith', 'Parâmetros') ?>: serial, type = ['all', 'team', 'employee'], filter = ['all', 'id_equipe', 'id_colaborador'], iniDate, endDate
Chamada: getAttendanceReport($serial, $type, $filter, $iniDate, $endDate);
</pre>
                                                Variáveis de retorno:
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                        <tr>
                                                                            <th><?php echo Yii::t('smith', 'Variável') ?></th>
                                                                            <th><?php echo Yii::t('smith', 'Tipo') ?></th>
                                                                            <th><?php echo Yii::t('smith', 'Formato') ?></th>
                                                                            <th><?php echo Yii::t('smith', 'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>status_code</td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith', 'Código do status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>status</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith', 'Status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>entry</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith', 'Horário de entrada') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>departure</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith', 'Horário de saída') ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.9 getProgramsAndSitesProductivity<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: Produtividade dos colaboradores em programas e sites') ?>
    <?php echo Yii::t('smith', 'Parâmetros') ?>: serial, type = ['all', 'team', 'employee'], filter = ['all', 'id_equipe', 'id_colaborador'], iniDate, endDate
    <?php echo Yii::t('smith', 'Chamada') ?>: getProgramsAndSitesProductivity($serial, $type, $filter, $iniDate, $endDate);
</pre>
                                                <?php echo Yii::t('smith', 'Variáveis de retorno') ?>:
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith', 'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>status_code</td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith', 'Código do status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>status</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>allowedPrograms</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Tempo em programas permitidos') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>allowedSites</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Tempo em sites permitidos') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>outdoorActivity</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Tempo em atividades externas') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>unallowedPrograms</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Tempo em programas não permitidos') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>unallowedSites</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Tempo em sites não permitidos') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>absent</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Tempo ausente do computador') ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.10 getIndividualContractProductivity<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: Informações e produtividade dos projetos') ?>
    <?php echo Yii::t('smith', 'Parâmetros') ?>: serial,  filter = ['all', 'id_contrato'], iniDate, endDate
Chamada: getIndividualContractProductivity($serial, $filter, $iniDate, $endDate);
</pre>
                                                <?php echo Yii::t('smith', 'Variáveis de retorno') ?>:
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith', 'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>status_code</td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Código do status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>status</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>code</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Código do contrato') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>start</td>
                                                                                <td>string</td>
                                                                                <td>00/00/0000</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Data de início do projeto') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>estimateConclusion</td>
                                                                                <td>string</td>
                                                                                <td>00/00/0000</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Estimativa de término do projeto') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>timeGoal</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Tempo previsto') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>timeDone</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Tempo realizado') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>costGoal</td>
                                                                                <td>float</td>
                                                                                <td>0.00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Custo previsto') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>costDone</td>
                                                                                <td>float</td>
                                                                                <td>0.00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Custo realizado') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>doneTime</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Tempo realizado no projeto') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>participation</td>
                                                                                <td>string</td>
                                                                                <td>0%</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Participação do colaborador no projeto') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>brlFloatBudget</td>
                                                                                <td>float</td>
                                                                                <td>0.00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Orçamento do colaborador em Real
                                                                                    brasileiro') ?>
                                                                                    
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>brlFormatBudget</td>
                                                                                <td>string</td>
                                                                                <td>R$0,00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        ' Orçamento do colaborador em Real
                                                                                    brasileiro') ?>
                                                                                   
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>usdFloatBudget</td>
                                                                                <td>string</td>
                                                                                <td>0.00</td>
                                                                                <td>
                                                                                    <?php echo Yii::t('smith',
                                                                                        'Orçamento do colaborador em Dólar
                                                                                    americano') ?>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>usdFormatBudget</td>
                                                                                <td>string</td>
                                                                                <td>$0.00</td>
                                                                                <td>
                                                                                    <?php echo Yii::t('smith',
                                                                                        'Orçamento do colaborador em Dólar
                                                                                    americano') ?>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.11 getGeneralContractProductivity<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: Produtividade geral nos projetos') ?>
    <?php echo Yii::t('smith', 'Parâmetros') ?>: serial, type = ['all', 'team', 'employee', 'contract'], filter = ['all', 'id_equipe', 'id_colaborador', 'id_contratct'], iniDate, endDate
Chamada: getGeneralContractProductivity($serial, $type, $filter, $iniDate, $endDate);
</pre>
                                                <?php echo Yii::t('smith', 'Variáveis de retorno') ?>:
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith', 'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>status_code</td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith', 'Código do status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>status</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>code</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Código do contrato') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>timeGoal</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Tempo previsto') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>timeDone</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Tempo realizado') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>brlFloatBudgetGoal</td>
                                                                                <td>float</td>
                                                                                <td>0.00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Orçamento previsto em Real brasileiro') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>brlFormatBudgetGoal</td>
                                                                                <td>string</td>
                                                                                <td>R$0,00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Orçamento previsto em Real brasileiro') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>usdFloatBudgetGoal</td>
                                                                                <td>float</td>
                                                                                <td>0.00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Orçamento previsto em Dólar americano') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>usdFormatBudgetGoal</td>
                                                                                <td>string</td>
                                                                                <td>$0.00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Orçamento previsto em Dólar americano') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>brlFloatBudgetDone</td>
                                                                                <td>float</td>
                                                                                <td>0.00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Orçamento realizado em Real brasileiro') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>brlFormatBudgetDone</td>
                                                                                <td>string</td>
                                                                                <td>R$0,00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Orçamento realizado em Real brasileiro') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>usdFloatBudgetDone</td>
                                                                                <td>float</td>
                                                                                <td>0.00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Orçamento realizado em Dólar americano') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>usdFormatBudgetDone</td>
                                                                                <td>string</td>
                                                                                <td>$0.00</td>
                                                                                <td><?php echo Yii::t('smith',
                                                                                        'Orçamento realizado em Dólar americano') ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.12 getEmployeesContractProductivity<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: Produtividade dos colaboradores nos projetos') ?>
    <?php echo Yii::t('smith', 'Parâmetros') ?>: serial, filter = ['all', 'id_colaborador'], iniDate, endDate
    <?php echo Yii::t('smith', 'Chamada') ?>: getEmployeesContractProductivity($serial, $filter, $iniDate, $endDate);
</pre>
                                                <?php echo Yii::t('smith', 'DVariáveis de retorno') ?>:
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith', 'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>status_code</td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith', 'Código do status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>status</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith', 'Status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>name</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith', 'Nome do contrato') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>doneTime</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td>
                                                                                    <?php echo Yii::t('smith', 'Tempo de produtividade do colaborador no projeto') ?>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.13 getContractConsumption<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: Consumo de energia dos projetos em kw/h') ?>
    <?php echo Yii::t('smith', 'Parâmetros') ?>: serial, filter = ['all', 'id_contract'], iniDate, endDate
    <?php echo Yii::t('smith', 'Chamada') ?>: getContractConsumption($serial, $filter, $iniDate, $endDate);
</pre>
                                                <?php echo Yii::t('smith', 'DVariáveis de retorno') ?>:
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith', 'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>status_code</td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith', 'Código do status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>status</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith', 'Status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>brlFloatConsumption</td>
                                                                                <td>float</td>
                                                                                <td>0.00</td>
                                                                                <td>
                                                                                    <?php echo Yii::t('smith', 'Consumo de energia do projeto em Real
                                                                                    brasileiro') ?>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>brlFormatConsumption</td>
                                                                                <td>string</td>
                                                                                <td>R$0,00</td>
                                                                                <td>
                                                                                    <?php echo Yii::t('smith', ' Consumo de energia do projeto em Real
                                                                                    brasileiro (formatado para moeda)') ?>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>usdFloatConsumption</td>
                                                                                <td>float</td>
                                                                                <td>0.00</td>
                                                                                <td>
                                                                                    <?php echo Yii::t('smith', ' Consumo de energia do projeto em Dólar
                                                                                    americano') ?>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>usdFormatConsumption</td>
                                                                                <td>string</td>
                                                                                <td>$0.00</td>
                                                                                <td>
                                                                                    <?php echo Yii::t('smith', '  Consumo de energia do projeto em Dólar
                                                                                    americano (formatado para moeda)') ?>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.14 getMetricsReport<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: Participação dos colaboradores nas métricas') ?>
    <?php echo Yii::t('smith', 'Parâmetros') ?>: serial, type = ['all', 'team', 'employee'], filter = ['all', 'id_equipe', 'id_colaborador'], iniDate, endDate
    <?php echo Yii::t('smith', 'Chamada') ?>: getMetricsReport($serial, $type, $filter, $iniDate, $endDate);
</pre>
                                                <?php echo Yii::t('smith', 'Variáveis de retorno') ?>:
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith', 'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>status_code</td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith', 'Código do status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>status</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith', 'Status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>performance</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith', 'Área de atuação') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>aplication</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith', 'Aplicação medida') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>criterion</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith', 'Critério de filtragem') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>inputs</td>
                                                                                <td>integer</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith', 'Quantidade de entradas') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>totalTime</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith', 'Tempo total') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>averageTimePerDay</td>
                                                                                <td>string</td>
                                                                                <td>00:00:00</td>
                                                                                <td><?php echo Yii::t('smith', 'Média de tempo por dia') ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.15 updateEmployee<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: Atualizar informações do colaborador') ?>

    <?php echo Yii::t('smith', 'Parâmetros') ?>: serial, id, name, lastName, email, salary, weeklyWorkload, team
Observação: Apenas os campos 'serial' e 'id' são obrigatórios; o campo 'id' se refere aos identificadores retornados da função 'getEmployees'
    <?php echo Yii::t('smith', 'Chamada') ?>: updateEmployee($serial, $id, $name, $lastName, $email, $salary, $weeklyWorkload, $team);
</pre>
                                                <?php echo Yii::t('smith', 'Variáveis de retorno') ?>:
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith', 'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>status_code</td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith', 'Código do status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>status</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith', 'Status da requisição') ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                3.2.16 insertExternalActivity<br>
<pre>
<?php echo Yii::t('smith', 'Descrição: Inserção de atividades externa') ?>
    <?php echo Yii::t('smith', 'Parâmetros') ?>: serial, contract, employee, description, departure_time, time_of_arrival, date
    <?php echo Yii::t('smith', 'Observação') ?>: o campo 'contract' e 'employee' se refere aos identificadores retornados da função 'getContracts' e 'getEmployees' respectivamente.
    <?php echo Yii::t('smith', 'Chamada') ?>: insertExternalActivity($serial, $contract, $employee, $description, $departure_time, $time_of_arrival, $date);
</pre>
                                                <?php echo Yii::t('smith', 'Variáveis de retorno') ?>:
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <section class="panel" style="background-color: #F5F5F5">
                                                            <div class="panel-body">
                                                                <section id="unseen">
                                                                    <table class="table table-bordered table-striped table-condensed">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo Yii::t('smith', 'Variável') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Tipo') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Formato') ?></th>
                                                                                <th><?php echo Yii::t('smith', 'Descrição') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>status_code</td>
                                                                                <td>int</td>
                                                                                <td>0</td>
                                                                                <td><?php echo Yii::t('smith', 'Código do status da requisição') ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>status</td>
                                                                                <td>string</td>
                                                                                <td></td>
                                                                                <td><?php echo Yii::t('smith', 'Status da requisição') ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </section>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </li>
                            </ol>
                        </li>
                        <li class="dd-item dd-collapsed">
                            <div class="dd-handle">4. <?php echo Yii::t('smith', 'Exemplos') ?></div>
                            <ol class="dd-list">
                                <li class="dd-item dd-collapsed">
                                    <div class="dd-handle">4.1 <?php echo Yii::t('smith', 'Chamada direta') ?></div>
                                    <ol class="dd-list">
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                4.1.1 <?php echo Yii::t('smith', 'Exemplo de chamada de uma função por cURL via linha de comando') ?>
                                                <br>
                                                <div class="dd-handle">
                                                    <?php echo Yii::t('smith', 'Chamada') ?>:<br>
                                                    <pre class="prettyprint lang-bsh linenums">
curl --data "action=getTeamMonthlyProductivity&serial=A7DC-4E4S-XAF0-HI6M&filter=all&iniDate=2016-02-01&endDate=2016-02-29" https://vivasmith.com/api/execute/
                                                </pre>
                                                </div>
                                                <div class="dd-handle">
                                                    <?php echo Yii::t('smith', 'Retorno') ?>:<br>
                                                    <pre class="prettyprint lang-js linenums">
{
"status_code":200,
"status":"OK",
"response":{
    "Comercial":{
        "info"{
            "done":30.68,
            "goal":60
        },
        "employees":{
            "Marcos Ferreira":{"done":10.68},
            "Fernanda dos Santos":{"done":49.9}
        }
    },
    "Desenvolvimento":{
        "info"{
            "done":55.96,
            "goal":70
        },
        "employees":{
            "Pedro das Couves":{"done":53.66},
            "Maria da Silva":{"done":58.26}
        }
    },
}
                                                </pre>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </li>
                                <li class="dd-item dd-collapsed">
                                    <div class="dd-handle">4.2 <?php echo Yii::t('smith', 'Biblioteca em PHP') ?></div>
                                    <ol class="dd-list">
                                        <li class="dd-item">
                                            <div class="dd-handle">
                                                4.2.1 <?php echo Yii::t('smith', 'Exemplo de chamada de uma função utilizando a biblioteca para PHP do sistema') ?>
                                                <br>
                                                <div class="dd-handle">
                                                    <?php echo Yii::t('smith', 'Chamada') ?>:<br>
                                                    <pre class="prettyprint lang-php linenums">
require_once('smithApi/smithApi.php');
class CustomController {
    public function actionIndex() {
        $output = new SmithApi();
        $result = $output->getTeamMonthlyProductivity('A7DC-4E4S-XAF0-HI6M', 'all','2016-02-01', '2016-02-29');
        var_dump($result);
    }
}
                                                </pre>
                                                </div>
                                                <div class="dd-handle">
                                                    <?php echo Yii::t('smith', 'Retorno') ?>:<br>
                                                    <pre class="prettyprint lang-js linenums">
{
"status_code":200,
"status":"OK",
"response":{
    "Comercial":{
        "info"{
            "done":30.68,
            "goal":60
        },
        "employees":{
            "Marcos Ferreira":{"done":10.68},
            "Fernanda dos Santos":{"done":49.9}
        }
    },
    "Desenvolvimento":{
        "info"{
            "done":55.96,
            "goal":70
        },
        "employees":{
            "Pedro das Couves":{"done":53.66},
            "Maria da Silva":{"done":58.26}
        }
    },
}
                                                </pre>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </li>
                            </ol>
                        </li>


                        <li class="dd-item dd-collapsed">
                            <div class="dd-handle">5. <?php echo Yii::t('smith', 'Erros') ?></div>
                            <ol class="dd-list">
                                <li class="dd-item">
                                    <div class="dd-handle">
                                        <?php echo Yii::t('smith', ' Mensagens de erro que podem ocorrer e suas causas') ?>
                                        :<br><br>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <section class="panel" style="background-color: #F5F5F5">
                                                    <div class="panel-body">
                                                        <section id="unseen">
                                                            <table class="table table-bordered table-striped table-condensed">
                                                                <thead>
                                                                    <tr>
                                                                        <th><?php echo Yii::t('smith', 'Mensagem') ?></th>
                                                                        <th><?php echo Yii::t('smith', 'Código') ?></th>
                                                                        <th><?php echo Yii::t('smith', 'Causa') ?></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Missing iniDate and endDate</td>
                                                                        <td>400</td>
                                                                        <td>
                                                                            <?php echo Yii::t('smith', 'O campo data inicial e data final não
                                                                            foram informados') ?> 
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Missing iniDate</td>
                                                                        <td>400</td>
                                                                        <td>
                                                                            <?php echo Yii::t('smith', 'O campo data inicial não foi informado') ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Missing endDate</td>
                                                                        <td>400</td>
                                                                        <td>
                                                                            <?php echo Yii::t('smith', 'O campo data final não foi informado') ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Missing date</td>
                                                                        <td>400</td>
                                                                        <td><?php echo Yii::t('smith', 'O campo data não foi informado') ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            iniDate cannot be bigger than endDate
                                                                        </td>
                                                                        <td>400</td>
                                                                        <td>
                                                                            <?php echo Yii::t('smith', ' O valor do campo data inicial não pode
                                                                            ser maior que a data final') ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            endDate cannot be bigger than actual
                                                                            date
                                                                        </td>
                                                                        <td>400</td>
                                                                        <td>
                                                                            <?php echo Yii::t('smith', 'O valor do campo data final não por ser
                                                                            maior que a data atual') ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Missing serial</td>
                                                                        <td>400</td>
                                                                        <td><?php echo Yii::t('smith', 'O campo ‘serial’ não foi enviado') ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Missing action</td>
                                                                        <td>400</td>
                                                                        <td><?php echo Yii::t('smith', 'O campo ‘action’ não foi enviado') ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Action not found</td>
                                                                        <td>400</td>
                                                                        <td>
                                                                            <?php echo Yii::t('smith', 'Não foi encontrada ação com o valor enviado em
                                                                            ‘action’') ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Authentication failed</td>
                                                                        <td>401</td>
                                                                        <td>
                                                                            <?php echo Yii::t('smith', 'O serial enviado não corresponde a nenhuma empresa') ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>No results found with these parameters</td>
                                                                        <td>204</td>
                                                                        <td>
                                                                            <?php echo Yii::t('smith', 'Não foi encontrado resultado da função chamada com
                                                                            os valores passados como parâmetro') ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            it's necessary fill some fields to update info
                                                                            employee
                                                                        </td>
                                                                        <td>400</td>
                                                                        <td>
                                                                            <?php echo Yii::t('smith', 'É necessário preencher alguns campos para atualizar
                                                                            as informações do colaborador') ?> 
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Missing contract</td>
                                                                        <td>400</td>
                                                                        <td>
                                                                            <?php echo Yii::t('smith', 'Não foi informado o identificador do contrato para a
                                                                            inserção de atividade externa') ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Missing employees list</td>
                                                                        <td>400</td>
                                                                        <td>
                                                                            <?php echo Yii::t('smith', 'Não foi informado os colaboradores para a inserção
                                                                            de atividade externa') ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Missing description</td>
                                                                        <td>400</td>
                                                                        <td>
                                                                            <?php echo Yii::t('smith', 'Não foi informado a descrição da atividade para a
                                                                            inserção de atividade externa') ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Missing departure time</td>
                                                                        <td>400</td>
                                                                        <td>
                                                                            <?php echo Yii::t('smith', 'Não foi informado o horário de saída da atividade
                                                                            para a inserção de atividade externa') ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Missing time of arrival</td>
                                                                        <td>400</td>
                                                                        <td>
                                                                            <?php echo Yii::t('smith', 'Não foi informado o horário de chegada da atividade
                                                                            para a inserção de atividade externa') ?>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </section>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ol>
                </li>
               </ol>
            </div>
            </div>
        </section>
    </div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.dd').nestable({
            expandBtnHTML   : '<button data-action="expand" type="button"></button>',
            collapseBtnHTML : '<button data-action="collapse" type="button"></button>',
        });
    });
</script>