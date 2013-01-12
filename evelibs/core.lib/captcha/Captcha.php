<?php
/**
 * Captcha generation and verification.
 * 
 * 
 * @package Core
 * @author fsw
 */

class Captcha
{
	private $code = null;
	const NUM_DOGS = 10;
	const NUM_CATS = 65;
	const PICTURES = 8;
	const SESSION_KEY = 'captcha';
	const IMG_SRC = '/captcha.png';

	public static function render()
	{
		$start = microtime(true);
		$code = [];
		for ($i=0; $i<Captcha::PICTURES; $i++)
		{
			$code[] = rand(0,1);
		}
		$_SESSION[Captcha::SESSION_KEY] = implode('', $code);

		$image = imagecreatetruecolor(50 * Captcha::PICTURES, 50);
		$background = imagecolorallocate($image, 0, 0, 255);
		foreach ($code as $i => $c)
		{
			$src = sprintf('%s/%s/%03d.jpg', __DIR__, $c ? 'cats' : 'dogs', rand(1, $c ? Captcha::NUM_CATS : Captcha::NUM_DOGS));
			$img = imagecreatefromjpeg($src);
			
			//CONTRAST
			if (rand(0,2) == 0)
			{
				imagefilter($img, IMG_FILTER_CONTRAST, rand(-10, 10));
			}
			//BRIGHTNESS
			if (rand(0,2) == 0)
			{
				imagefilter($img, IMG_FILTER_BRIGHTNESS, rand(-10, 10));
			}
			
			//COLORIZE
			$color = rand(0, 3);
			imagefilter($img, IMG_FILTER_COLORIZE, 
				$color == 1 ? rand(-100,100) : rand(-20, 20),
				$color == 2 ? rand(-100,100) : rand(-20, 20),
				$color == 3 ? rand(-100,100) : rand(-20, 20));

			//CROP
			$x = rand(0, 15);
			$y = rand(0, 15);
			$w = imagesx($img) - max($x, $y) - rand(0, 10);
			$h = $w;
			if (rand(0, 1))
			{
				$x = imagesx($img) - $x;
				$w = -$w;	
			}
			imagecopyresampled($image, $img, 50 * $i, 0, $x, $y, 50, 50, $w, $h);
		}
		
		header('Content-Type: image/png');
		imagepng($image);
		imagedestroy($image);
	}
	
	public static function isValid($code)
	{
		$string = '';
		for ($i=0; $i<Captcha::PICTURES; $i++)
		{
			$string .= (empty($code[$i]) || ($code[$i] != '1')) ? '0' : '1';
		}
		if (!empty($_SESSION[Captcha::SESSION_KEY]))
		{
			$ret = $string === $_SESSION[Captcha::SESSION_KEY];
			unset($_SESSION[Captcha::SESSION_KEY]);
		}
		else
		{
			$ret = false;
		}
		return $ret;
	}
	
	public static function formInput($key)
	{
		$ret = ['Please check cats:<br/>', '<img src="/captcha/get.png"/>', '<br/>'];
		for ($i = 0; $i < Captcha::PICTURES; $i++)
		{
			$ret[] = '<div style="float:left; width: 50px; text-align:center;"><input type="checkbox" name="' . $key . '[' . $i . ']" value="1"/></div>';
		}
		return implode(NL, $ret);
	}
	
}
