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
			<input class="form-control input-sm" type="text" name="search" placeholder="Search..."/>

			<button type="submit" id="search-btn" class="btn"><i class="fa fa-search"></i></button>
		</form>
	</div>
	<!-- #search -->
	<nav id="sidebar">

		<ul id="main-nav" class="open-active">

			<li class="<?php if ($section == "dashboard")
			{
				echo "active";
			} ?>">

				<a href="<?php echo BASE_URL; ?>">
					<i class="fa fa-dashboard"></i>
					Dashboard
				</a>
			</li>

			<li class="dropdown <?php if ($section == "appointments")
			{
				echo "active";
			} ?>">
				<a href="javascript:;">
					<i class="fa fa-table"></i>
					Appointments
					<span class="caret"></span>
				</a>

				<ul class="sub-nav">

					<?php if ( ! $user->isTutor()): ?>
						<li>
							<a href="<?php echo BASE_URL; ?>appointments/add">
								<i class="fa fa-plus-square"></i>
								Add
							</a>
						</li>
					<?php endif; ?>
					<?php if ($user->isTutor()): ?>
						<li>
							<a href="<?php echo BASE_URL; ?>appointments/calendar">
								<i class="fa fa-calendar"></i>
								Calendar
							</a>
						</li>
					<?php endif; ?>
					<li>
						<a href="<?php echo BASE_URL; ?>appointments/list">
							<i class="fa fa-list"></i>
							List
						</a>
					</li>
				</ul>
			</li>

			<li class="dropdown <?php if ($section == "staff")
			{
				echo "active";
			} ?>">
				<a href="javascript:;">
					<i class="fa fa-group"></i>
					SASS Staff
					<span class="caret"></span>
				</a>

				<ul class="sub-nav">
					<li>
						<a href="<?php echo BASE_URL; ?>staff/">
							<i class="fa fa-dashboard"></i>
							Personnel
						</a>
					</li>

					<?php if ( ! $user->isTutor()): ?>
						<li>
							<a href="<?php echo BASE_URL; ?>staff/schedules">
								<i class="fa fa-calendar"></i>
								Schedules
							</a>
						</li>
					<?php endif; ?>

					<?php if ( ! $user->isTutor()): ?>
						<li>
							<a href="<?php echo BASE_URL; ?>staff/facilitating-courses">
								<i class="fa fa-table"></i>
								Facilitating Courses
							</a>
						</li>
					<?php endif; ?>

					<?php if ($user->isAdmin()): ?>
						<li>
							<a href="<?php echo BASE_URL; ?>staff/add">
								<i class="fa fa-plus-square"></i>
								Add Personnel
							</a>
						</li>
					<?php endif; ?>

				</ul>
			</li>


			<?php if ( ! $user->isTutor())
			{ ?>
				<li class="dropdown <?php if (strcmp($section, "academia") === 0)
				{
					echo "active";
				} ?>">
					<a href="javascript:">
						<i class="fa fa-university"></i>
						Academia
						<span class="caret"></span>
					</a>

					<ul class="sub-nav">
						<li>
							<a href="<?php echo BASE_URL; ?>academia/courses">
								<i class="fa fa-book"></i>
								Courses
							</a>
						</li>
						<li>
							<a href="<?php echo BASE_URL; ?>academia/majors">
								<i class="fa fa-book"></i>
								Majors
							</a>
						</li>
						<li>
							<a href="<?php echo BASE_URL; ?>academia/students">
								<i class="fa fa-users"></i>
								Students
							</a>
						</li>
						<li>
							<a href="<?php echo BASE_URL; ?>academia/instructors">
								<i class="fa fa-group"></i>
								Instructors
							</a>
						</li>
						<li>
							<a href="<?php echo BASE_URL; ?>academia/terms">
								<i class="fa fa-calendar"></i>
								Terms
							</a>
						</li>
					</ul>
				</li>
			<?php } ?>


			<li class="dropdown <?php if ($section == "account")
			{
				echo "active";
			} ?>">
				<a href="javascript:;">
					<i class="fa fa-file-text"></i>
					Account
					<span class="caret"></span>
				</a>

				<ul class="sub-nav" style="">
					<li>
						<a href="<?php echo BASE_URL; ?>account/profile">
							<i class="fa fa-user"></i>
							Profile
						</a>
					</li>
					<li>
						<a href="<?php echo BASE_URL; ?>account/settings">
							<i class="fa fa-cogs"></i>
							Settings
						</a>
					</li>
					<?php if ($user->isTutor())
					{ ?>
						<li>
							<a href="<?php echo BASE_URL; ?>account/schedule">
								<i class="fa fa-calendar"></i>
								Schedule
							</a>
						</li>
					<?php } ?>
				</ul>
			</li>


			<?php if ($user->isAdmin())
			{ ?>
				<li class="<?php if ($section == "cloud") echo "active"; ?>">
					<a href="<?php echo BASE_URL; ?>cloud">
						<i class="fa fa-cloud"></i>
						Cloud
					</a>
				</li>

			<?php } ?>

			<li class="<?php if ($section == "support") echo "active"; ?>">
				<a href="<?php echo BASE_URL; ?>support">
					<i class="fa fa-question"></i>
					Support
				</a>
			</li>

			<?php if ($user->email === 'grkatsas@acg.edu' || $user->email === 'r.dokollari@gmail.com'): ?>

				<li class="<?php if ($section == "appointments-terms") echo "active"; ?>">
					<a href="<?php echo BASE_URL; ?>appointments-terms.php">
						<i class="fa fa-table"></i>
						Appointments | Terms
					</a>
				</li>

			<?php endif; ?>

		</ul>

	</nav>
	<!-- #sidebar -->

</div> <!-- /#sidebar-wrapper -->