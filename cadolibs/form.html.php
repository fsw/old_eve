<div class="form">
	<h1><?php echo $this->title; ?></h1>
	<?php if (!empty($this->description)): ?>
		<div class="legend">
		<?php echo $this->description; ?>
		</div>
	<?php endif; ?>
	<?php if (true) { ?>
		<form method="post" action="" enctype="multipart/form-data">
			<input type="hidden" name="<?php echo $this->name ?>[token]" value="123"/>
		<?php for($i = 0; !empty($this->errors[$i]); $i++) { ?>
			<?php echo $this->errors[$i] ?>
			<br/>
		<?php } ?>
		
		<?php foreach ($this->fields as $key => $field) { ?>
			<div class="field">
				<label><?php echo $key ?></label>
				<?php if (!empty($this->errors[$key])) { ?>
					<?php echo $this->errors[$key] ?>
				<?php } ?>
				<?php echo $field->getFormInput($this->name . '[' . $key . ']', array_key_exists($key, $this->data) ? $this->data[$key] : null) ?>
			</div>
		<?php } ?>
		<div class="submit">
			<input type="submit" value="<?php echo $this->submitText; ?>"/>
		</div>
		</form>
	<?php } ?>
</div>