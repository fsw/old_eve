<?php
/** 
 * @package CadoLibs
 * @author fsw
 */

class Mail
{
	private static function preparehtmlmail($html, $txt, $root)
	{
		//searching for images in provided html
		$images = array();
		preg_match_all('~<img.*?src=.([\/.a-z0-9:_-]+).*?>~si',$html,$matches);

		foreach ($matches[1] as $img)
		{
			$img_old = $img;

			if (strpos($img, "http://") === false)
			{
				$uri = parse_url($img);
				$image = array();
				$image['name'] = $uri['path'];
				$image['path'] = Cado::findResource($root.$uri['path']);
				$content_id = md5($img);
				$html = str_replace($img_old, 'cid:'.$content_id, $html);
				$image['cid'] = $content_id;
				$images[] = $image;
			}
		}

		$boundary = "=_".md5(uniqid(time()));
		$nl = "\r\n";

		$headers = 'MIME-Version: 1.0' . $nl;
		$headers .= 'From: no-reply@' . Eve::$domains[0] . $nl;
		$headers .= 'Content-Type: multipart/related; type="multipart/alternative"; boundary="' . $boundary . '"' . $nl;

		$multipart = '';

		$multipart .= '--' . $boundary . $nl;
		$multipart .= 'Content-Type: multipart/alternative; boundary="_=ALT' . $boundary . '"' . $nl;
		$multipart .= $nl . $nl;

		$multipart .= '--_=ALT' . $boundary . $nl;
		$multipart .= 'Content-Type: text/plain; charset="utf-8"' . $nl;
		$multipart .= 'Content-Transfer-Encoding: quoted-printable' . $nl . $nl;
		$multipart .= $txt . /* chunk_split($txt, 76, '=' . $nl ) .*/ $nl;

		$multipart .= '--_=ALT' . $boundary . $nl;
		$multipart .= 'Content-Type: text/html; charset="utf-8"' . $nl;
		$multipart .= 'Content-Transfer-Encoding: quoted-printable' . $nl . $nl;
		$multipart .= $html . /* chunk_split($html, 76, '=' . $nl ) .*/ $nl;
		$multipart .= '--_=ALT' . $boundary . '--' . $nl . $nl;

		foreach ($images as $path)
		{
			//var_dump($path); die();
			if(file_exists($path['path']))
				$fp = fopen($path['path'],"r");
			if (!@$fp)  {
				return false;
			}

			$imagetype = substr(strrchr($path['path'], '.' ),1);
			$file = fread($fp, filesize($path['path']));
			fclose($fp);

			$message_part = "";

			switch ($imagetype) {
				case 'png':
				case 'PNG':
					$message_part .= "Content-Type: image/png";
					break;
				case 'jpg':
				case 'jpeg':
				case 'JPG':
				case 'JPEG':
					$message_part .= "Content-Type: image/jpeg";
					break;
				case 'gif':
				case 'GIF':
					$message_part .= "Content-Type: image/gif";
					break;
			}

			$message_part .= "; name=\"$path[name]\"$nl";
			$message_part .= "Content-Transfer-Encoding: base64$nl";
			$message_part .= 'Content-ID: <'.$path['cid'].">$nl$nl";
			//$message_part .= 'X-Attachment-Id: '.$path['cid']."$nl$nl";
			//$message_part .= "Content-Disposition: inline; filename=\"".basename($path['path'])."\"\r\n\r\n";
			$message_part .= chunk_split(base64_encode($file)) . $nl;
				
			$multipart .= "--$boundary$nl".$message_part . $nl . $nl;

		}
		$multipart .= "--$boundary--" . $nl;

		return array('multipart' => $multipart, 'headers' => $headers);
	}

	public static function send($to, $subject, $template, $vars = array(), $attach = array())
	{
		$vars['webViewLink'] = actions_Webmail::hrefIndex($template, $vars);
		
		$htmlBody = new Template('mails/' . $template . '/mail.html', $vars);
		$txtBody = new Template('mails/' . $template . '/mail.txt', $vars);

		$final_msg = self::preparehtmlmail($htmlBody, $txtBody, 'mails/' . $template . '/'); // give a function your html*

		return mail(
				is_array($to) ? implode(', ', $to) : $to, 
				$subject, 
				$final_msg['multipart'], 
				$final_msg['headers']);
		/* TODO
		 --PHP-mixed-{$sep}
		Content-Type: application/zip; name="attachment.zip"
		Content-Transfer-Encoding: base64
		Content-Disposition: attachment

		{$attached}

		--PHP-mixed-{$sep}--
		*/
	}

}
