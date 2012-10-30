<?php

class field_Video extends field_File
{
	public function __construct($thumbnails = array())
	{
		parent::__construct(array('video'), $thumbnails);
	}
}
