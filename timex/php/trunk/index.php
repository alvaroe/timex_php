<?php
if (!isset($_SESSION)) {
	session_start();
}
require_once ('includes/config.inc.php');
$page_title = 'Welcome to TIMEX - Online Timesheet System!';
include ('includes/header.html');
?>
</head>
<body>
<?php
include ('includes/menus.html');
?>
	<div id="page">
		<div id="content">
			<div id="welcome" class="boxed2">
				<h1 class="title"><?php echo $page_title;?></h1>
				<div class="content">
					<p>
						<strong>TIMEX</strong> is a an online timesheet system that keeps
						track of all work done by our employees on a weekly basis. Feel
						free to register, login and send us feedback on this tool.</strong></em>
					</p>
				</div>
			</div>
			<div id="sample1" class="boxed3">
				<h2 class="title">Managers</h2>
				<div class="content">
					<ul>
						<li><a href="#">You must submit your timesheets by each Friday
								before 5pm.</a>
						</li>
						<li><a href="#">You must approve your employee's timesheets before
								Monday 12pm of following week.</a>
						</li>
						<li><a href="#">All approved timesheets this week will be
								processed for payment on following Friday.</a>
						</li>
					</ul>
				</div>
			</div>
			<div id="sample2" class="boxed3">
				<h2 class="title">Hourly Employees</h2>
				<div class="content">
					<ol>
						<li><a href="#">You must fill out your timesheets at the end of
								each day.</a>
						</li>
						<li><a href="#">You must submit your finalized weekly timesheets
								by each Friday before 6pm.</a>
						</li>
						<li><a href="#">This week's timesheet will be paid next Friday.</a>
						</li>
					</ol>
				</div>
			</div>
		</div>
		<!-- end #content -->
		<?php
		include ('includes/sidebar_signin.html');
		?>
		<div style="clear: both; height: 1px;"></div>
	</div>
	<!-- end #page -->
	<?php
	include ('includes/footer.html');
	?>
</body>
</html>
