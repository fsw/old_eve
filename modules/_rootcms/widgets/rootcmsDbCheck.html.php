
<?php foreach($this->sqls as $key=>$sql): ?>
<strong>
<?php echo $key; ?>
</strong>
<div class="sql">
<?php echo $sql; ?>
<a href="<?php echo controller_Rootcms::hrefDbCheck($key); ?>">run</a>
</div>
<?php endforeach; ?>