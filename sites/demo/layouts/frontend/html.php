<header id="header" class="site-header" role="banner">
    <div id="header-inner" class="container sixteen columns over">
    <hgroup class="one-third column alpha">
    <h1 id="site-title" class="site-title">
    <a href="index.html" id="logo"><img src="/static/images/icebrrrg-logo.png" alt="Icebrrrg logo" height="63" width="157" /></a>
    </h1>
    </hgroup>
    <nav id="main-nav" class="two thirds column omega">
    <ul id="main-nav-menu" class="nav-menu">
    
	<?php foreach ($this->mainMenu as $row):?>
	<li id="menu-item-<?php echo $row['id']?>" <?php if (true || ($row['href'] == $this->currentUrl)) echo 'class="current"'; ?>>
    	<a href="<?php echo $row['href']; ?>"><?php echo $row['title']; ?></a>
    	<?php //var_dump($row['href'], $this->currentUrl) ?>
    </li>
	<?php endforeach; ?>
	
	<?php /*echo Html::ulTree($this->mainMenu, function($row){
					return '<a href="' . $row['href'] . '">' . $row['title'] . '</a>';
				}, 'children', array('id' => 'mainMenu')); */?>
    </ul>
    </nav>
    </div>
</header>

	<div class="container">
    
		<aside class="six columns left-sidebar">
		

		
        <div class="sidebar-widget">
        <h2>What is this?</h2>
        <p>
        	This is a DEMO Website for Eve CMS.
        	It is using <a href="http://www.opendesigns.org/design/icebrrrg/">icebrrrg</a> template.
        	Log in to <a href="/cms/">CMS</a> to play.
        </p>
        </div>
        
        
        <div class="sidebar-widget">
        <h2>Left Links</h2>
        <ul>
	        <?php foreach ($this->leftMenu as $row):?>
			<li><a href="<?php echo $row['href']; ?>"> <?php echo $row['title']; ?></a></li>
			<?php endforeach; ?>
        </ul>
        </div>
        
        <div class="sidebar-widget">
        <h2>Some static content</h2>
        <p>Unchangable static content. Unchangable static content. Unchangable static content. Unchangable static content.</p>
        </div>
        
        </aside>
        <!-- End Left Sidebar -->
        <?php echo $this->widget; ?>
        <!-- End main Content -->
      
    
    </div>

<footer>

<div class="footer-inner container">


<div class="social footer-columns one-third column">
<h2><i class="icon-bullhorn icon-large"></i> Get Social</h2>
<p>With theme creators...</p>
<ul>
<li><a href="http://www.twitter.com/opendesigns/"><i class="icon-twitter-sign icon-large"></i> Twitter</a></li>
<li><a href="http://www.facebook.com/opendesigns"><i class="icon-facebook-sign icon-large"></i> Facebook</a></li>
<li><a href="https://plus.google.com/b/110224753971231624818/110224753971231624818/posts"><i class="icon-google-plus-sign icon-large"></i> Google+</a></li>
</ul>
</div>

<div class="footer-columns one-third column">
<h2><i class="icon-book icon-large"></i> Bottom Links</h2>
<p>
<?php foreach ($this->bottomMenu as $row):?>
	<a href="<?php echo $row['href']; ?>"> <?php echo $row['title']; ?></a>, 
<?php endforeach; ?>
</p>

</div>

<div class="footer-columns one-third column">
<h2><i class="icon-user icon-large"></i> More Static</h2>
<p>
More unchangable static content is placed here.
More unchangable static content is placed here.
</p>
<p>
More unchangable static content is placed here. 
More unchangable static content is placed here.   
</p>
</div>

</div>

<div id="footer-base">
<div class="container">
<div class="eight columns">
<a href="http://www.opendesigns.org/design/icebrrrg/">Icebrrg Website Template</a> &copy; 2012
</div>

<div class="eight columns far-edge">
Design by <a href="http://www.opendesigns.org">OD</a>
&nbsp; &nbsp;
Powered by <a href="http://cadosolutions.com/eve">EveFramework</a>
</div>
</div>
</div>

</footer>
