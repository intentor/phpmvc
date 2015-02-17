<?php include 'views/shared/_title.php'; ?>

<div class="content">
	<div class="container">
		<div class="row">
	  
			<?php include 'views/shared/_navigation.php'; ?>
				
             	<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">						
					<form method="post" action="<?php url('account/') ?>">
					<h3 class="title"><?php echo LABEL_PERSONAL_DATA; ?></h3>
					
					<?php Html::hidden_for('user_id'); ?>
					
					<div>
						<?php Html::label_for('user_email') ?>
						<?php Html::textbox_for('user_email', 'form-control'); ?>
						<?php Html::validation_message_for('user_email') ?>
					</div>
					<div>
						<?php Html::label_for('user_name') ?>
						<?php Html::textbox_for('user_name', 'form-control'); ?>
						<?php Html::validation_message_for('user_name') ?>
					</div>
					<div>
						<?php Html::label_for('user_document') ?>
						<?php Html::textbox_for('user_document', 'form-control'); ?>
						<?php Html::validation_message_for('user_document') ?>
					</div>
					<div>
						<?php Html::label_for('user_phone') ?>
						<?php Html::textbox_for('user_phone', 'form-control'); ?>
					</div>
					<div>
						<?php Html::label_for('user_cellphone') ?>
						<?php Html::textbox_for('user_cellphone', 'form-control'); ?>
					</div>
					
					<h3 class="title title-form"><?php echo LABEL_ADDRESS; ?></h3>
					<div>
						<?php Html::label_for('user_address_line1') ?>
						<?php Html::textbox_for('user_address_line1', 'form-control'); ?>
					</div>
					<div>
						<?php Html::label_for('user_address_line2') ?>
						<?php Html::textbox_for('user_address_line2', 'form-control'); ?>
					</div>
					<div>
						<?php Html::label_for('user_district') ?>
						<?php Html::textbox_for('user_district', 'form-control'); ?>
					</div>
					<div>
						<?php Html::label_for('user_city') ?>
						<?php Html::textbox_for('user_city', 'form-control'); ?>
					</div>
					<div>
						<?php Html::label_for('user_state') ?>
						<?php Html::textbox_for('user_state', 'form-control'); ?>
					</div>
					<div>
						<?php Html::label_for('user_country') ?>
						<?php Html::textbox_for('user_country', 'form-control'); ?>
						<?php Html::validation_message_for('user_country') ?>
					</div>
					<div>
						<?php Html::label_for('user_zip') ?>
						<?php Html::textbox_for('user_zip', 'form-control'); ?>
					</div>
					
	      		 	<input type="submit" class="btn-color btn-normal btn-form" value="<?php echo BTN_SEND ?>" />
				</form>
				       	
	      		<form method="post" action="<?php url('account/password/') ?>">
					<div class="divider"></div>
					<h3 class="title"><?php echo LABEL_CHANGE_PASSWORD; ?></h3>
					
					<?php Html::hidden_for('user_id'); ?>
					
					<div>
						<?php Html::label_for('user_password') ?>
						<?php Html::password_for('user_password', 'form-control'); ?>
						<?php Html::validation_message_for('user_password') ?>
					</div>
					<div>
						<?php Html::label_for('user_password_confirm') ?>
						<?php Html::password_for('user_password_confirm', 'form-control'); ?>
						<?php Html::validation_message_for('user_password_confirm') ?>
					</div>
					
	      		 	<input type="submit" class="btn-color btn-normal btn-form" value="<?php echo BTN_CHANGE_PASSWORD ?>" />
				</form>
	  		</div>
   		</div>
	</div>
</div>
<!-- Main Content end-->