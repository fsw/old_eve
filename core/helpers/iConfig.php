<?php

interface iConfig
{
    public static function getDatabaseConnection();
    public static function getFileCachePath();
    public static function getModules();
}