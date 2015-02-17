<?php include 'views/shared/_title.php'; ?>

<!-- Main Content start-->
<div class="content">
   <div class="container">
	  <div class="row">
		 <div class="posts-block col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<article>
				<form method="post" action="<?php url('signup/terms/') ?>">
					<?php echo DISCLAIMER_TERMS ?>
					<?php echo TERMS_OF_SERVICE ?>
					<button input type="submit" class="btn-color btn-normal btn-form btn-green"><?php echo BTN_TERMS_ACCEPT ?></button>
					<button type="button" onclick="window.location='<?php url('home/') ?>'" class="btn-color btn-normal btn-form"><?php echo BTN_TERMS_NOT_ACCEPT; ?></button>
				</form>
			</article>
		 </div>
	  </div>
   </div>
</div>
<!-- Main Content end-->