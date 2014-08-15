<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) <year> <copyright holders>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @author Rizart Dokollari
 * @since 8/15/14.
 */

?>


	<header id="header">

		<h1 id="site-logo">
			<a href="<?php echo BASE_URL; ?>">
				<img src="<?php echo BASE_URL; ?>app/assets/img/logos/logo.png" alt="Site Logo"/>
			</a>
		</h1>

		<a href="javascript:;" data-toggle="collapse" data-target=".top-bar-collapse" id="top-bar-toggle"
		   class="navbar-toggle collapsed">
			<i class="fa fa-cog"></i>
		</a>

		<a href="javascript:;" data-toggle="collapse" data-target=".sidebar-collapse" id="sidebar-toggle"
		   class="navbar-toggle collapsed">
			<i class="fa fa-reorder"></i>
		</a>

	</header>
	<!-- header -->

	<nav id="top-bar" class="collapse top-bar-collapse">
		<ul class="nav navbar-nav pull-right">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
					<i class="fa fa-user"></i>
					<?php echo "Welcome " . $user->getFirstName(); ?>
					<span class="caret"></span>
				</a>

				<ul class="dropdown-menu" role="menu">
					<!-- "My schedule" choice is shown only to type tutor -->
					<?php if ($user->is_tutor()) { ?>
						<li>
							<a href="<?php echo BASE_URL; ?>page-calendar.html">
								<i class="fa fa-calendar"></i>
								&nbsp;&nbsp;My Schedule
							</a>
						</li>
						<li class="divider"></li>2
					<?php } ?>
					<li>
						<a href="<?php echo BASE_URL; ?>logout">
							<i class="fa fa-sign-out"></i>
							&nbsp;&nbsp;Logout
						</a>
					</li>
				</ul>
			</li>
		</ul>

	</nav>
	<!-- /#top-bar -->