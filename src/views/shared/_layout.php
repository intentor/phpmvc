<?php use Intentor\Utilities\Helpers; ?>
<!DOCTYPE html>
<html>
	 <head>
	 	<meta charset="<?php echo SITE_ENCODING; ?>">	 	
		<title><?php echo APP_TITLE ?> - <?php echo $this->title ?></title>
	</head>
	<body>
		<!--BEGIN BODY -->
		<?php $this->render_body(); ?>
		<!--END BODY -->
			
		<!--BEGIN STYLES -->
		<?php echo($this->styles); ?>
		<!--END STYLES -->
		
		<!--BEGIN SCRIPTS -->
		<script src="<?php url('scripts/intentor.phpmvc.js') ?>"></script>
		<script src="<?php url('scripts/global.js') ?>"></script> 
		<?php echo($this->scripts); ?>
		<!--END SCRIPTS -->
	</body> 
</html>