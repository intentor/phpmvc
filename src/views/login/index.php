<?php include 'views/shared/_title.php'; ?>

<!-- Main Content start-->
<div class="content">
   <div class="container">
	  <div class="row">
      	<div class="col-lg-12 col-md-12">
      		 <div class="row">
             	<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
	             	<form method="post" action="<?php url('login/') ?>">
						<div>
							<?php Html::label_for('login_email') ?>
							<?php Html::textbox_for('login_email', 'form-control'); ?>
							<?php Html::validation_message_for('login_email') ?>
						</div>
						<div>
							<?php Html::label_for('login_password') ?>
							<?php Html::password_for('login_password', 'form-control') ?>
							<?php Html::validation_message_for('login_password') ?>
						</div>						
						<div>
							<a href="<?php url('login/recover/') ?>" class="right" ><?php echo BTN_LOGIN_RECOVER ?></a>	
						</div>					
						
						<input type="submit" class="btn-color btn-normal btn-form btn-green" value="<?php echo(BUTTON_LOGIN) ?>" />
					    <button type="button" onclick="window.location='<?php echo url('signup/') ?>'" class="btn-color btn-normal btn-form"><?php echo BTN_LOGIN_REGISTER; ?></button>
					</form>
             	</div>
             	
             	<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 login-background">
             		
             	</div>           	
             </div>
	  	</div>
	  </div>
   </div>
</div>
<!-- Main Content end-->