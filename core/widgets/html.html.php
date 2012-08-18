<!doctype html>
<html class="no-js" lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo $this->htmlTitle ?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <?php foreach ($this->cssPaths as $path) { ?>
  	<link rel="stylesheet" media="screen" type="text/css" href="<?php echo $path ?>">
  <?php } ?>
</head>
<body>
	<?php require_once($this->__layout); ?>
	<?php foreach ($this->jsPaths as $path) { ?>
		<script src="<?php echo $path ?>"></script>
	<?php } ?>
</body>
</html>
