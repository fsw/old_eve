<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js gt-ie8" lang="en">
<!--<![endif]-->
<head>
  <meta charset="utf-8">
  <title><?php echo $this->htmlTitle; ?></title>
  <meta name="description" content="">
  <link rel="icon" type="image/png" href="/static/favicon.png"> 
  <meta name="viewport" content="width=800">
  <?php foreach ($this->metaProperties as $key => $values): ?>
  	<?php if (!is_array($values)) $values = array($values); ?>
  	<?php foreach ($values as $value): ?>
  		<meta property="<?php echo $key; ?>" content="<?php echo $value; ?>">
  	<?php endforeach; ?>
  <?php endforeach; ?>
  <?php foreach ($this->cssUrls as $url): ?>
  	<link rel="stylesheet" media="screen" type="text/css" href="<?php echo $url; ?>">
  <?php endforeach; ?>
  <script src="/static/modernizr.js"></script>
</head>
<body>
	<?php echo $this->htmlBody; ?>
	<?php if($this->attachDevbar): ?>
		<?php Dev::showDevFooter(); ?>
	<?php endif; ?>
	<?php foreach ($this->jsUrls as $url): ?>
		<script src="<?php echo $url; ?>"></script>
	<?php endforeach; ?>
</body>
</html>
