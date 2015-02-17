<?php include 'views/shared/_title.php'; ?>

<!-- Main Content start-->
<div class="content">
   <div class="container">   		
	  <div class="row">
	  	<div class="posts-block col-lg-12 col-md-12 col-sm-12 col-xs-12">
	  		<p style="margin-bottom:45px">Ufa! Ainda estamos trabalhando para criar um melhor <strong class="highlight-text-medium">Shopitos</strong> para você!
	  		Enquanto fazemos nossos ajustes aqui, seus dados serão pré-cadastrados em nossa base de dados e em breve entrataremos em contato via e-mail para que você possa concluir
	  		a criação de sua loja e assim se transformar num verdadeiro <strong class="highlight-text-medium">Shopito</strong>!</p>
	  	</div>
	  </div>
	  <div class="row">
      	<div class="col-lg-12 col-md-12">
      		 <div class="row">
             	<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
             		<form method="post" action="<?php url('signup/') ?>">
						<h3 class="title"><?php echo LABEL_DATA_ACCESS; ?></h3>
						<div>
							<?php Html::label_for('user_email') ?>
							<?php Html::textbox_for('user_email', 'form-control'); ?>
							<?php Html::validation_message_for('user_email') ?>
						</div>
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
						
						<h3 class="title title-form"><?php echo LABEL_PERSONAL_DATA; ?></h3>
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

      		 			<input type="submit" class="btn-color btn-normal btn-form" value="<?php echo BTN_LOGIN_REGISTER ?>" />
					</form>
             	</div>
             	
             	<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
             		<div class="shopito1 pull-right"></div>
             		<?php echo INDEX_DISCLAIMER ?>
             	</div>  
             </div>
	  	</div>
	  </div>
   </div>
</div>
<!-- Main Content end-->