<?php foreach ($this->methods as $method => $args): ?>
	<h2><?php echo $method; ?></h2>
	<form class="methodForm" action="<?php echo controller_Api::hrefJson($this->model, $method, array()); ?>" method="get">
	<?php foreach ($args as $name => $default): ?>
		<?php echo $name; ?>: <input type="text" name="<?php echo $name; ?>" value="<?php echo htmlspecialchars(json_encode($default)); ?>" />
	<?php endforeach; ?>
	<button type="submit">Call</button>
	<div class="response">
		
	</div>
	</form>
<?php endforeach; ?>