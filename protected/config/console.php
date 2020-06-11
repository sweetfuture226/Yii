<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Cron',

    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.*',
        'application.modules.userGroups.models.*',
        'application.extensions.crontab.*',
        'ext.yii-mail.YiiMailMessage',
    ),

    // application components
    'components' => array(
        'routes' => array(
            array(
                'class' => 'CFileLogRoute',
                'logFile' => 'cron.log',
                'levels' => 'error, warning',
            ),
            array(
                'class' => 'CFileLogRoute',
                'logFile' => 'cron_trace.log',
                'levels' => 'trace',
            ),
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

        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'userGroups.components.WebUserGroups',
        ),
        // uncomment the following to enable URLs in path-format

        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
            'showScriptName' => false,
        ),

        'kint' => array(
            'class' => 'ext.Kint.Kint',
        ),
        // uncomment the following to use a MySQL database
        'db' => require(dirname(__FILE__) . '/db.php'),
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
            ),
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
);
