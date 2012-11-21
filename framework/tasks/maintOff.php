<?php 
echo 'turning maintenance OFF';
Fs::write(Site::getWebroot() . 'maintenance.lock', 'Y');
