<?php
/**
 * Versionable collection.
 * 
 * @package Core
 * @author fsw
 */

trait model_set_Moderated
{
	public static $moderated = true;
	use model_set_Versioned;
	
	public static function canModerate()
	{
		return in_array('m_' . static::getBaseName(), model_Users::getLoggedInPrivilages());
	}
}