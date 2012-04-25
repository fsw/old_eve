<?php

interface iConfig
{
    public function getDatabaseConnection();
    public function getFileCachePath();
}