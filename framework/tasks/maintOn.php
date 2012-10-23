<?php 
echo 'turning maintenance ON';
Fs::write($this->site->getWebroot() . 'maintenance.lock', 'Y');
