<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Neon Admin Panel" />
	<meta name="author" content="" />
	<link rel="shortcut icon" href="favicon.png">
	<title>Viva Smith - Login</title>

	<?php Yii::app()->clientScript->registerCssFile('themes/neon'.'/assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css');?>
	<?php Yii::app()->clientScript->registerCssFile('themes/neon'.'/assets/css/font-icons/entypo/css/entypo.css');?>
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic"/>
	<?php Yii::app()->clientScript->registerCssFile('themes/neon'.'/assets/css/bootstrap.css');?>
	<?php Yii::app()->clientScript->registerCssFile('themes/neon'.'/assets/css/neon-core.css');?>
	<?php Yii::app()->clientScript->registerCssFile('themes/neon'.'/assets/css/neon-theme.css');?>
	<?php Yii::app()->clientScript->registerCssFile('themes/neon'.'/assets/css/neon-forms.css');?>
	<?php Yii::app()->clientScript->registerCssFile('themes/neon'.'/assets/css/custom.css');?>

	<?php Yii::app()->clientScript->registerScriptFile('themes/neon' . '/assets/js/jquery-1.11.0.min.js'); ?>


	<!-- Bottom scripts (common) -->
	<?php Yii::app()->clientScript->registerScriptFile('themes/neon' . '/assets/js/gsap/main-gsap.js'); ?>
	<?php Yii::app()->clientScript->registerScriptFile('themes/neon' . '/assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js'); ?>
	<?php Yii::app()->clientScript->registerScriptFile('themes/neon' . '/assets/js/bootstrap.js'); ?>
	<?php Yii::app()->clientScript->registerScriptFile('themes/neon' . '/assets/js/joinable.js'); ?>
	<?php Yii::app()->clientScript->registerScriptFile('themes/neon' . '/assets/js/resizeable.js'); ?>
	<?php Yii::app()->clientScript->registerScriptFile('themes/neon' . '/assets/js/neon-api.js'); ?>
	<?php Yii::app()->clientScript->registerScriptFile('themes/neon' . '/assets/js/jquery.validate.min.js'); ?>
	<?php Yii::app()->clientScript->registerScriptFile('themes/neon' . '/assets/js/neon-login.js'); ?>


	<!-- JavaScripts initializations and stuff -->
	<?php Yii::app()->clientScript->registerScriptFile('themes/neon' . '/assets/js/neon-custom.js'); ?>


	<!-- Demo Settings -->
	<?php Yii::app()->clientScript->registerScriptFile('themes/neon' . '/assets/js/neon-demo.js'); ?>
	<!--[if lt IE 9]><?php //Yii::app()->clientScript->registerScriptFile('themes/neon' . '/assets/js/ie8-responsive-file-warning.js'); ?><![endif]-->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<?php //Yii::app()->clientScript->registerScriptFile('themes/neon' . '/https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'); ?>
		<?php //Yii::app()->clientScript->registerScriptFile('themes/neon' . '/https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js'); ?>
	<![endif]-->


</head>
<body class="page-body login-page login-form-fall" data-url="http://neon.dev">


<!-- This is needed when you send requests via Ajax -->

<div class="login-container">
	
	<div class="login-header login-caret">
		
		<div class="login-content">
			
			<a href="index.html" class="logo">
				<img src="<?php echo Yii::app()->theme->baseUrl.'/assets/images/logo-smith.png' ?>" width="300" alt="" />
			</a>
			
			<p class="description"><?php echo Yii::t("smith","Acesse sua conta");  ?></p>
			<!-- progress bar indicator -->
			<div class="login-progressbar-indicator">
				<h3>43%</h3>
			</div>
		</div>
		
	</div>
	
	<div class="login-progressbar">
		<div></div>
	</div>
	
	<div class="login-form">
		
		<div class="login-content">
			
			<div class="form-login-error">
				<h3>Invalid login</h3>
				<p>Enter <strong>demo</strong>/<strong>demo</strong> as login and password.</p>
			</div>
			
			<?php echo $content; ?>
			
			<div class="login-bottom-links">
				
				<a href="javascript:;" id="esqueci" class="link"><?= Yii::t('smith', 'Esqueci minha senha')?></a>
							
			</div>
			
		</div>
		
	</div>
	
</div>



</body>
</html>

            <script type="text/javascript">
                    $("#esqueci").on('click', function() {
                        $("#recuperarSenha").modal();
                    });
            </script>