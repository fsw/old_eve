<div id="devfooter">
   <ul>
      <li title="Home"><a href="http://your.domain.tld/"><img src="/static/icons/16/home.png" alt="" /></a></li>
   </ul>
   <span class="jx-separator-left"></span>
   <ul>
   	<li title="Times"><a href="#">Timers</a>
         <ul>
			<?php foreach (self::$times as $name => $time): ?>
				<li><?php echo $name ?>&nbsp;<b><?php echo $time * 1000 ?></b>ms</li>
			<?php endforeach; ?>
         </ul>
      </li>       
      <li title="Errors"><a href="#"><img src="/static/icons/16/error.png" alt="Errors" /></a>
         <ul>
            <li>asdfasdfasdf</li>
            <li>asdfasdfasdf</li>
            <li>asdfasdfasdf</li>
         </ul>
      </li>
   </ul>   
   <span class="jx-separator-left"></span>
   <ul>
   <?php foreach (self::$events as $class => $events): ?>
		<li title="<?php echo $class ?>"><a href="#" ><img src="/static/icons/16/info.png" alt="" /><?php echo $class ?> <b>(<?php echo count($events); ?>)</b></a>
         <ul>
			<?php foreach ($events as $event): ?>
				<li><?php echo $event ?></li>
			<?php endforeach; ?>
		</ul>
		</li>
	<?php endforeach; ?>
	</ul>
	   <span class="jx-separator-left"></span>
	
   <div>Lorem ipsum</div>
   <ul class="jx-bar-button-right">
      <li title="Feeds"><a href="#"><img src="img/feed.png" alt="" /></a>
         <ul>
            <li><a href="http://your.domain.tld/comments/"><img src="img/comment.png" title="Comment Feed" />Comment Feed</a></li>
         	<li><a href="http://your.domain.tld/comments/"><img src="img/comment.png" title="Comment Feed" />Comment Feed</a></li>
         </ul>
      </li>
   </ul>
   <span class="jx-separator-right"></span>   
</div>