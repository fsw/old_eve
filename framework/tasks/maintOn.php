<?php 
echo 'turning maintenance ON';
Fs::write(Site::getWebroot() . 'maintenance.lock', 'Y');
