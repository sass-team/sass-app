<?php
/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/13/14
 * Time: 12:24 AM
 */
?>

<div id="sidebar-wrapper" class="collapse sidebar-collapse">

<div id="search">
	<form>
		<input class="form-control input-sm" type="text" name="search" placeholder="Search..." />

		<button type="submit" id="search-btn" class="btn"><i class="fa fa-search"></i></button>
	</form>
</div> <!-- #search -->

<nav id="sidebar">

<ul id="main-nav" class="open-active">

<li class="<?php if ($section == "dashboard") { echo "active"; } ?>">
	<a href="<?php echo BASE_URL; ?>index.php">
		<i class="fa fa-dashboard"></i>
		Dashboard
	</a>
</li>

<li class="dropdown <?php if ($section == "my_account") { echo "active"; } ?>">
	<a href="javascript:;">
		<i class="fa fa-file-text"></i>
		My Account
		<span class="caret"></span>
	</a>

	<ul class="sub-nav">
		<li>
			<a href="<?php echo BASE_URL; ?>my_account/profile.php">
				<i class="fa fa-user"></i>
				Profile
			</a>
		</li>
		<li>
			<a href="<?php echo BASE_URL; ?>my_account/profile-settings.php">
				<i class="fa fa-cogs"></i>
				Settings
			</a>
		</li>
		<?php if($user->isTutor()) { ?>
		<li>
			<a href="<?php echo BASE_URL; ?>page-calendar.html">
				<i class="fa fa-calendar"></i>
				My Schedule
			</a>
		</li>
		<?php } ?>
	</ul>

</li>
<?php if($user->isAdmin()) { ?>
<li class="dropdown <?php if ($section == "manage_users") {echo "active"; } ?>">
	<a href="javascript:;">
		<i class="fa fa-tasks"></i>
		Manage / View Users
		<span class="caret"></span>
	</a>

	<ul class="sub-nav">
		<li>
			<a href="<?php echo BASE_URL; ?>manage_users/manage-users.php">
				<i class="fa fa-edit"></i>
				Manage Users
			</a>
		</li>
		<li>
			<a href="<?php echo BASE_URL; ?>manage_users/view-users.php">
				<i class="fa fa-group"></i>
				View Current Users
			</a>
		</li>
	</ul>
</li>
<?php } ?>
<li class="dropdown">
	<a href="javascript:;">
		<i class="fa fa-tasks"></i>
		Form Elements
		<span class="caret"></span>
	</a>

	<ul class="sub-nav">
		<li>
			<a href="./form-regular.html">
				<i class="fa fa-location-arrow"></i>
				Regular Elements
			</a>
		</li>
		<li>
			<a href="./form-extended.html">
				<i class="fa fa-magic"></i>
				Extended Elements
			</a>
		</li>
		<li>
			<a href="./form-validation.html">
				<i class="fa fa-check"></i>
				Validation
			</a>
		</li>
	</ul>

</li>

<li class="dropdown">
	<a href="javascript:;">
		<i class="fa fa-desktop"></i>
		UI Features
		<span class="caret"></span>
	</a>

	<ul class="sub-nav">
		<li>
			<a href="./ui-buttons.html">
				<i class="fa fa-hand-o-up"></i>
				Buttons
			</a>
		</li>
		<li>
			<a href="./ui-tabs.html">
				<i class="fa fa-reorder"></i>
				Tabs & Accordions
			</a>
		</li>

		<li>
			<a href="./ui-popups.html">
				<i class="fa fa-asterisk"></i>
				Popups / Notifications
			</a>
		</li>

		<li>
			<a href="./ui-sliders.html">
				<i class="fa fa-tasks"></i>
				Sliders
			</a>
		</li>

		<li class="">
			<a href="./ui-typography.html">
				<i class="fa fa-font"></i>
				Typography
			</a>
		</li>

		<li class="">
			<a href="./ui-icons.html">
				<i class="fa fa-star-o"></i>
				Icons
			</a>
		</li>
	</ul>
</li>

<li class="dropdown">
	<a href="javascript:;">
		<i class="fa fa-table"></i>
		Tables
		<span class="caret"></span>
	</a>

	<ul class="sub-nav">
		<li>
			<a href="./table-basic.html">
				<i class="fa fa-table"></i>
				Basic Tables
			</a>
		</li>
		<li>
			<a href="./table-advanced.html">
				<i class="fa fa-table"></i>
				Advanced Tables
			</a>
		</li>
		<li>
			<a href="./table-responsive.html">
				<i class="fa fa-table"></i>
				Responsive Tables
			</a>
		</li>
	</ul>

</li>

<li>
	<a href="./ui-portlets.html">
		<i class="fa fa-list-alt"></i>
		Portlets
	</a>
</li>

<li class="dropdown">
	<a href="javascript:;">
		<i class="fa fa-bar-chart-o"></i>
		Charts & Graphs
		<span class="caret"></span>
	</a>

	<ul class="sub-nav">
		<li>
			<a href="./chart-flot.html">
				<i class="fa fa-bar-chart-o"></i>
				jQuery Flot
			</a>
		</li>
		<li>
			<a href="./chart-morris.html">
				<i class="fa fa-bar-chart-o"></i>
				Morris.js
			</a>
		</li>
	</ul>
</li>

<li class="dropdown">
	<a href="javascript:;">
		<i class="fa fa-file-text-o"></i>
		Extra Pages
		<span class="caret"></span>
	</a>

	<ul class="sub-nav">
		<li>
			<a href="<?php echo BASE_URL; ?>login.php">
				<i class="fa fa-unlock"></i>
				Login Basic
			</a>
		</li>
		<li>
			<a href="<?php echo BASE_URL; ?>page-login-social.html">
				<i class="fa fa-unlock"></i>
				Login Social
			</a>
		</li>
		<li>
			<a href="<?php echo BASE_URL; ?>page-404.html">
				<i class="fa fa-ban"></i>
				404 Error
			</a>
		</li>
		<li>
			<a href="<?php echo BASE_URL; ?>page-500.html">
				<i class="fa fa-ban"></i>
				500 Error
			</a>
		</li>
		<li>
			<a href="<?php echo BASE_URL; ?>page-blank.html">
				<i class="fa fa-file-text-o"></i>
				Blank Page
			</a>
		</li>
	</ul>
</li>

</ul>

</nav> <!-- #sidebar -->

</div> <!-- /#sidebar-wrapper -->