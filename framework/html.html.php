<!doctype html>
<html class="no-js" lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo $this->htmlTitle; ?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <?php foreach ($this->cssUrls as $url): ?>
  	<link rel="stylesheet" media="screen" type="text/css" href="<?php echo $url; ?>">
  <?php endforeach; ?>
</head>
<body>
	<?php echo $this->htmlBody; ?>
	<?php foreach ($this->jsUrls as $url): ?>
		<script src="<?php echo $url; ?>"></script>
	<?php endforeach; ?>
</body>
</html>
