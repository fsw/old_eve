<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title>Cado Framework - Application Error</title>
		<meta name="description" content="sorry. no bonus" />
		<meta name="viewport" content="width=device-width" />
		<link rel="shortcut icon" href="/static/cadoIcon.png" />
		<link rel="stylesheet" href="/static/reset.css" />
		<link rel="stylesheet" href="/static/error.css" />
	</head>
	<body>
		<div id="error">
			<div class="info">
				<h1>Application Error</h1>
				<h2>sorry. no bonus :(</h2>
				<p>
					An error has occured.<br/>
					It was loged and reported to application developers.<br/>
					We are sorry for inconvinience.<br/>
					Please try again later or return to <a href="/">front page</a>
				</p>
			</div>
			<?php if ($printDebug): ?>
			<div class="debug">
				<b><?php echo empty(static::$errorNames[$code]) ? '' : static::$errorNames[$code] . ': '; ?><?php echo $message; ?></b> 
				<br/>
				<?php echo $file; ?>:<?php echo $line; ?>
				<br/>
				stack trace:
				<ul>
				<?php foreach($trace as $lp => $t): ?>
				<li>
					<strong>
					<?php echo count($trace) - $lp; ?>)
					<?php echo empty($t['class']) ? '' : $t['class'] . $t['type']; //$t['object'] ?><?php echo $t['function']; ?>(<?php echo implode(', ', $t['args']); ?>)
					</strong>
					<br/>
					<?php echo empty($t['file']) ? '' : $t['file'] . ':' . (empty($t['line']) ? '0' : $t['line']); ?>
				</li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>
		<div class="footer">
			Powered by <a href="http://cadosolutions.com/framework">CadoFramework</a>
		</div>
	</body>
</html>