<?php include 'views/shared/_title.php'; ?>

<!-- Main Content start-->
<div class="content">
   <div class="container">
	  <div class="row">
      	<div class="col-lg-12 col-md-12">
      		 <div class="row">
             	<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">          	
	             	<form method="post" action="<?php url('login/recover/') ?>">
						<div>
							<?php Html::label_for('user_email') ?>
							<?php Html::textbox_for('user_email', 'form-control'); ?>
							<?php Html::validation_message_for('user_email') ?>
						</div>				
						
						<button input type="submit" class="btn-color btn-normal btn-form"><?php echo BTN_SEND ?></button>
					</form>
             	</div>
             	
             	<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
             		<?php echo(DISCLAIMER_RECOVER_INDEX) ?>
             	</div>           	
             </div>
	  	</div>
	  </div>
   </div>
</div>
<!-- Main Content end-->