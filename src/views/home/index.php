<?php 
$this->styles .= <<<PARA
<style>
	.phpmvc { color: #808080; }
</style>
PARA;
?>

<h2>Welcome to <strong class="phpmvc">PhpMVC</strong>, <?php echo $this->model->user_name ?></h2>