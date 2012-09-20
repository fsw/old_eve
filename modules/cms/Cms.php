<?php

class Cms extends Module
{
	public static function getModel()
	{
		return array('Users', 'Groups', 'Privilages', 'Attachments');
	}
}
