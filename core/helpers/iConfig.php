<?php

interface iConfig
{
    public function getDatabaseConnection();
    public function getFileCachePath();
    public function getModules();
}