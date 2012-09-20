
<div style="overflow:auto;">
<a id="addButton" href="<?php echo Routing::linkToAction('editTicket', 0) ?>" class="button">ADD</a>
<h1>Tickets</h1>
</div>

<ul class="tickets">
<?php  foreach($this->tickets as $ticket) { ?>
<li class="status<?php echo ucfirst($ticket['status']) ?>">
		<?php $statusesFlow = array(
				'new' => array('assigned', 'closed'),
				'assigned' => array('inprogress', 'closed'),
				'inprogress' => array('fixed', 'blocked', 'closed'),
				'blocked' => array('inprogress'),
				'fixed' => array('pushed'),
				'pushed' => array(),
				'closed' => array(),
				); ?>
		<?php foreach($statusesFlow[$ticket['status']] as $status) { ?>
			<a class="actionLink statusIcon <?php echo $status ?>" href="<?php echo Routing::linkToAction('ticketStatus', $ticket['id'], $status) ?>">&nbsp;</a>
		<?php } ?>
		<a class="ticketLink" href="<?php echo Routing::linkToAction('ticket', $ticket['id']) ?>">
		<div class="statusIcon <?php echo $ticket['status'] ?>"></div>
		<div class="title">
			<strong>[#<?php echo $ticket['id']?>] <?php echo $ticket['title']?></strong>
			<br/>
			<?php echo Text::excerpt($ticket['description'], 70) ?>
		</div>
		</a>
</li>
<?php } ?>
</ul>



