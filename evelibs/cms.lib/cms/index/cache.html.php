<h1>Cache</h1>

<?php foreach ($this->caches as $key => $cache): ?>
<h2><?php echo $cache['title'] ?></h2>

<strong><?php echo $cache['count']; ?>(<?php echo Text::formatSize($cache['size']); ?>)</strong>
<br/>
<a href="<?php echo Site::lt('cms/cache', $key); ?>">clear</a>
<?php endforeach; ?>