<h1>Cache</h1>

<ul>
<li>
output cache:
<strong><?php echo $this->outputCacheCount; ?>(<?php echo $this->outputCacheSize; ?>)</strong>
<a href="<?php echo controller_Cms::hrefCache('output'); ?>">clear</a>
</li>
<li>
APC (opcode cache):
<strong><?php echo $this->apcCacheCount; ?>(<?php echo $this->apcCacheSize; ?>)</strong>
<a href="<?php echo controller_Cms::hrefCache('apc'); ?>">clear</a>
</li>
</ul>
