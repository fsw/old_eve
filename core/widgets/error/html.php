<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header>
	<h1><?=$this->number?> Error</h1> 
	<h2>sorry. no bonus.</h2>
  </header>
  <div role="main">
	<img src="logo.png">
	<b>file:</b> <?=$this->file?>
	<br/>
	<b>message:</b>	<?=$this->message?>
	<br/>
	<b>type:</b>	<?=$this->type?>
	<br/>
	<b>line:</b>	<?=$this->line?>
	<br/>
	<?foreach($this->stack as $call):?>
	<?var_dump($call)?>
	<?endforeach;?>
  </div>
  <footer>

  </footer>

</body>
</html>
