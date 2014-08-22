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

			<li class="<?php if ($section == "dashboard") {
				echo "active";
			} ?>">
				<a href="<?php echo BASE_URL; ?>">
					<i class="fa fa-dashboard"></i>
					Dashboard
				</a>
			</li>

			<li class="dropdown <?php if ($section == "account") {
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
					<?php if ($user->isTutor()) { ?>
						<li>
							<a href="<?php echo BASE_URL; ?>account/schedule">
								<i class="fa fa-calendar"></i>
								My Schedule
							</a>
						</li>
					<?php } ?>
				</ul>

			</li>
			<li class="dropdown <?php if ($section == "users") {
				echo "active";
			} ?>">
				<a href="javascript:;">
					<i class="fa fa-group"></i>
					Users
					<span class="caret"></span>
				</a>

				<ul class="sub-nav">
					<li>
						<a href="<?php echo BASE_URL; ?>users/overview">
							<i class="fa fa-group"></i>
							Overview
						</a>
					</li>

					<?php if ($user->isAdmin()): ?>
						<li>
							<a href="<?php echo BASE_URL; ?>users#schedule">
								<i class="fa fa-calendar"></i>
								Schedule
							</a>
						</li>
						<li>
							<a href="<?php echo BASE_URL; ?>users/create">
								<i class="fa fa-edit"></i>
								Create
							</a>
						</li>
					<?php endif; ?>


				</ul>
			</li>

			<?php if ($user->isAdmin() || $user->isTutor()) { ?>
				<li class="dropdown <?php if ($section == "courses-majors") {
					echo "active";
				} ?>">
					<a href="javascript:;">
						<i class="fa fa-list-alt"></i>
						Courses &amp; Majors
						<span class="caret"></span>
					</a>

					<ul class="sub-nav">
						<li>
							<a href="<?php echo BASE_URL; ?>courses-majors/courses">
								<i class="fa fa-table"></i>
								Courses
							</a>
						</li>
						<li>
							<a href="<?php echo BASE_URL; ?>courses-majors/majors">
								<i class="fa fa-table"></i>
								Majors
							</a>
						</li>
					</ul>
				</li>
				<li class="dropdown <?php if ($section == "workshops") {
					echo "active";
				} ?>">
					<a href="javascript:;">
						<i class="fa fa-calendar"></i>
						Workshop Sessions
						<span class="caret"></span>
					</a>

					<ul class="sub-nav">
						<li>
							<a href="<?php echo BASE_URL; ?>workshops/overview">
								<i class="fa fa-dashboard"></i>
								Overview
							</a>
						</li>
						<li>
							<a href="<?php echo BASE_URL; ?>workshops/add">
								<i class="fa fa-plus-square"></i>
								Add
							</a>
						</li>
					</ul>
				</li>

			<?php } ?>

			<li class="dropdown"></li>

			<li class="dropdown"></li>

			<li></li>

			<li class="dropdown"></li>

			<li class="dropdown"></li>

		</ul>

	</nav>
	<!-- #sidebar -->

</div> <!-- /#sidebar-wrapper -->