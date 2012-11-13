<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title>Cado Framework - Application Error</title>
		<meta name="description" content="sorry. no bonus" />
		<meta name="viewport" content="width=device-width" />
		<link rel="shortcut icon" type="image/​png" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9wKHwAONFTV8KcAAAFeSURBVDjLpZO9SgNREIW/ufvjDwpGjBYGBE0h6SyENFa+gVaKtU/gT2clwTSC2lpKCkFfQiwkFr6AnVYpgmjQhN07FtnVbLIbBC9cZu5wOHPODBf+eWSgUHvdUGMOMa6LMQ4A1obYIBBrq7o9f9uLdwcpTRVvtAiARjXHgONC56sKJAhMgu2iXlLPP0P1V1+sURX1/DP3ol7KJAinZvdAzhHRQWWiIOddTAYB3sg4InHfR2AfWACmgXVEBG9kPJtAfxrX2MytAnfAFfAELPZhMobYPSdRPALWovwyDdinwMbZc7SFMv3T+MUMtbAUxYfEOlMspCtQDqI1HiPcI7yg7KYpSMxAbNhtJuxw0ywC18AW8IawksCkEajjtXue5eieDsEkLUjQbqCRyV6vca6qErQbmRZMq1lRx19W1//AOJP4Y00AOp85bPguQWfCtJoV+9evWpjLzxTm8jPDMN8VWnW5WJpSAAAAAABJRU5ErkJggg=="/>
		<style type="text/css">
		html, body, div, form, fieldset, legend, label { margin: 0; padding: 0; }
		h1, h2, h3, h4, h5, h6, th, td, caption { font-weight: bold; }
		img { border: 0; }
		body { background-color: #231f20; }
		div#error { width: 560px; margin: 70px auto 0px auto; background-color: #FF4D10; border-radius: 50px 50px 50px 50px; padding: 40px; }
		div#error div.info { font-family: "Lucida Grande", "Lucida Sans Unicode", Arial, Helvetica, sans-serif; font-weight: 400; line-height: 1.3em; text-shadow: 0 0 10px #000000; color: white; }
		div#error div.debug { background-color: white; padding: 5px; }
		div.footer { width: 560px; margin: 10px auto 0px auto; text-align: right; color: #777; }
		div.footer a { color: #aaa; text-decoration: none; }
		</style>
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
			Powered by <a href="http://eve.cadosolutions.com/">EveFramework</a>
		</div>
	</body>
</html>