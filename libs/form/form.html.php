<h2><?php echo $this->title; ?></h2>
<?php echo $this->description; ?>
<?php if (true) { ?>
	<form method="post" action="">
	<?php for($i = 0; !empty($this->errors[$i]); $i++) { ?>
		<?php echo $this->errors[$i] ?>
		<br/>
	<?php } ?>
	
	<?php foreach ($this->fields as $key => $field) { ?>
		<div class="field">
		<b><?php echo $key ?></b><br/>
		<?php if (!empty($this->errors[$key])) { ?>
			<?php echo $this->errors[$key] ?>
			<br/>
		<?php } ?>
		<?php echo $field->getFormInput($this->name . '[' . $key . ']', array_key_exists($key, $this->data) ? $this->data[$key] : null) ?>
		</div>
	<?php } ?>
	<input type="submit" value="<?php echo $this->submitText; ?>"/>
	</form>
<?php } ?>