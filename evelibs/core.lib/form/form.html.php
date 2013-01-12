<div class="form <?php echo empty($this->class) ? '' : $this->class; ?>">
	<h1><?php echo $this->title; ?></h1>
	<?php if (!empty($this->description)): ?>
		<div class="legend">
		<?php echo $this->description; ?>
		</div>
	<?php endif; ?>
	<?php if (true) { ?>
		<form <?php echo $this->id ? 'id="' . $this->id . '"' : ''; ?> method="post" action="" enctype="multipart/form-data">
			<input type="hidden" name="<?php echo $this->name ?>[token]" value="123"/>
		<?php foreach($this->errors as $e) { ?>
			<div class="error">
				<?php echo $e ?>
			</div>
		<?php } ?>
		
		<?php foreach ($this->elements as $key => $element) { ?>
			<div class="field field<?php echo ucfirst($key) ?>">
				<?php if (!empty($element['error'])) { ?>
				<div class="error">
					<?php echo $element['error'] ?>
				</div>
				<?php } ?>
				<label><?php echo $key ?></label>
				<div class="input">				
					<?php echo $element['field']->getFormInput($this->name . '[' . $key . ']', $element['value']) ?>
				</div>
			</div>
		<?php } ?>
		<div class="submit">
			<input type="submit" value="<?php echo $this->submitText; ?>"/>
		</div>
		</form>
	<?php } ?>
</div>