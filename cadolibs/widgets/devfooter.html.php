<div id="devfooter">
   <ul>
      <li title="Home"><a href="http://<?php echo Eve::$domains[0]; ?>"><img src="/static/icons/16/home.png" alt="" /></a></li>
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
      <?php if (!empty($errors)): ?>
      <li title="Errors"><a href="#"><img src="/static/icons/16/error.png" alt="" />Errors <b>(<?php echo count($errors); ?>)</b></a>
         <ul>
			<?php foreach ($errors as $error): ?>
				<li>
				<?php echo $error['file']; ?>:<?php echo $error['line']; ?>
				<br/>
				<b><?php echo $error['count']; ?>x <?php echo $error['message']; ?></b>
				<br/>
				<?php echo $error['url']; ?>
				</li>
			<?php endforeach; ?>
         </ul>
      </li>
      <?php endif; ?>
   </ul>   
   <span class="jx-separator-left"></span>
   <ul>
   <?php foreach (self::$events as $class => $events): ?>
		<li title="<?php echo $class ?>"><a href="#" ><img src="/static/icons/16/info.png" alt="" /><?php echo $class ?> <b>(<?php echo count($events); ?>)</b></a>
	        <ul>
				<?php foreach ($events as $event): ?>
				<li>
					<?php foreach ($event as $arg): ?>
						<span>
						<?php if (is_array($arg)): ?>
							<a title="<?php echo htmlspecialchars(print_r($arg, true)) ?>">array</a>
						<?php else: ?>
							<?php echo $arg ?>
						<?php endif; ?>
						</span>
					<?php endforeach; ?>
				</li>
				<?php endforeach; ?>
			</ul>
		</li>
	<?php endforeach; ?>
	</ul>
	   <span class="jx-separator-left"></span>
	
   <div>Lorem ipsum</div>
   <ul class="jx-bar-button-right">
      <li title="Feeds"><a href="#">Dev settings</a>
         <ul>
            <li><label><input type="checkbox" onClick="devToggleCache(this);" <?php if (!empty($_COOKIE['use_cache']) && ($_COOKIE['use_cache']=='true')) echo ' checked="checked" '; ?>/> use cache</label></li>
            <li><a href="#" onClick="location.reload(); return false;">reload</a></li>
         </ul>
      </li>
   </ul>
   <span class="jx-separator-right"></span>   
</div>