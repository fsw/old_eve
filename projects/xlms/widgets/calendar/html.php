<div style="overflow:auto;">
<a id="addButton" href="<?php echo Routing::linkToAction('editTicket', 0) ?>" class="button">ADD</a>
<h1>Calendar</h1>
</div>

<table id="calendar" cellspacing="0" cellpadding="0">
<caption>
	<a href="#" title="previous month" class="nav">&laquo;</a> March <a href="#" title="next month" class="nav">&raquo;</a>
</caption>

 <tr>
	<th scope="col" abbr="Sunday" title="Sunday">Sun</th>
	<th scope="col" abbr="Monday" title="Monday">Mon</th>
	<th scope="col" abbr="Tuesday" title="Tuesday">Tue</th>
	<th scope="col" abbr="Wednesday" title="Wednesday">Wed</th>
	<th scope="col" abbr="Thursday" title="Thursday">Thu</th>
	<th scope="col" abbr="Friday" title="Friday">Fri</th>
	<th scope="col" abbr="Saturday" title="Saturday">Sat</th>
 </tr>
 <?php foreach($this->weeks as $week) { ?>
 <tr>
 	<?php foreach($week as $day) { ?>
	<td <?php echo !empty($day['class']) ? 'class="' . $day['class'] . '"' : '' ?>>
		<?php echo $day['label']?> (<?php echo $day['doy']?>)
		<ul>
		<?php foreach($day['tickets'] as $ticket) { ?>
		<li>
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
	</td>
	<?php } ?>
 </tr>
 <?php } ?>
</table>




