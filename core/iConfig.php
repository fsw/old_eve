<?php

interface iConfig
{
	public static function getMasterDatabaseConnection();
	public static function getSlaveDatabaseConnection();
}