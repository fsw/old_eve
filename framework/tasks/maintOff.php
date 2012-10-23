<?php 
echo 'turning maintenance OFF';
Fs::write($this->site->getWebroot() . 'maintenance.lock', 'Y');
