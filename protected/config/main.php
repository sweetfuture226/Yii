<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Viva Smith',
    'sourceLanguage' => 'pt_br',
    'language' => 'pt_br',
    'defaultController' => 'site',
    'theme' => 'neon',

    'catchAllRequest'=>file_exists(dirname(__FILE__).'/.manutencao') ? array('site/maintenance') : null,
    // autoloading model and component classes
    'preload' => (strpos($_SERVER['REQUEST_URI'], 'service') || strpos($_SERVER['REQUEST_URI'], 'api')) ? array('log', 'kint', 'booster') : array('log', 'kint', 'translate', 'booster'),
    'import' => array(
        'application.models.*',
        'application.models.views.*',
        'application.components.*',
        'application.modules.*',
        'application.modules.userGroups.models.*',
        'ext.yii-mail.YiiMailMessage',
        'application.modules.translate.TranslateModule',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'admin33',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
        'userGroups' => array(
            'accessCode' => 'smithVIVA',
        ),
        'translate',
    ),
    // application components
    'components' => array(

        'booster' => array(
            'class' => 'ext.yiibooster.components.Booster',
        ),
        'widgetFactory' => array(
            'widgets' => array(
                'CLinkPager' => array(
                    'htmlOptions' => array(
                        'class' => 'pagination'
                    ),
                    'selectedPageCssClass' =>'active',
                    'header' => false,
                    'maxButtonCount' => 10,
                    'cssFile' => false,
                ),
                'CGridView' => array(
                    'htmlOptions' => array(
                        'class' => 'dataTables_wrapper table-responsive form-inline'
                    ),
                    'filterCssClass' => 'replace-inputs',
                    'rowCssClass' => array('gradeA odd','gradeA even'),
                    'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
                    'itemsCssClass' => 'table  table-bordered table-striped datatable dataTable no-footer',
                    'cssFile' => false,
                    'summaryCssClass' => 'dataTables_info',
                    'summaryText' => 'Exibindo de {start} até {end} de {count} resultados',
                    'template' => '{items}<div class="row"><div class="col-xs-3 col-left" >{summary}</div><div class="col-xs-9 col-right" >{pager}</div></div><br />',
                    'emptyText' => 'Nenhum resultado encontrado.',
                ),
            ),
        ),


        'session' => array(
            'cookieParams' => array(
                'httponly' => true,
            ),
        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'userGroups.components.WebUserGroups',
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                'post/<id:\d+>/<title:.*?>' => 'post/view',
                'posts/<tag:.*?>' => 'post/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
            'showScriptName' => false,
        ),
        'db' => require(dirname(__FILE__) . '/db.php'),
        'messages' => array(
            'class' => 'CDbMessageSource',
            'onMissingTranslation' => array('Ei18n', 'missingTranslation'),
            'sourceMessageTable' => 'traducao_literal',
            'translatedMessageTable' => 'traducao'
        ),
        'translate' => array(
            'class' => 'translate.components.Ei18n',
            'createTranslationTables' => true,
            'connectionID' => 'db',
            'languages' => array(
                'en' => 'English',
                'es' => 'Espanhol',
                'pt' => 'Português',
            )
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
        'simpleImage' => array(
            'class' => 'application.extensions.CSimpleImage',
        ),
        'kint' => array(
            'class' => "ext.kintNew.Kint",
        ),
        'mail' => array(
            'class' => 'ext.yii-mail.YiiMail',
            'transportType' => 'smtp',
            'transportOptions' => array(
                'host' => 'smtp.gmail.com',
                'encryption' => 'ssl',
                'username' => 'notificacao@vivainovacao.com',
                'password' => 'Notificacao@!',
                'port' => 465,
            ),
            'viewPath' => 'application.views.mails',
        ),
        'ePdf' => array(
            'class' => 'ext.yii-pdf.EYiiPdf',
            'params' => array(
                'mpdf' => array(
                    'librarySourcePath' => 'application.vendors.mpdf.*',
                    'constants' => array(
                        '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                    ),
                    'class' => 'mpdf', // the literal class filename to be loaded from the vendors folder
                ),
                'HTML2PDF' => array(
                    'librarySourcePath' => 'application.vendors.html2pdf.*',
                    'classFile' => 'html2pdf.class.php', // For adding to Yii::$classMap
                )
            ),
	),
    ),


    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail' => 'lucascardoso@vivainovacao.com',
        'defaultPageSize' => 50,
        'pageSizeOptions'=>array(10=>10,20=>20,50=>50,100=>100,500=>500,1000=>1000),
    ),
);
