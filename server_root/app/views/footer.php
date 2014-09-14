<?php
/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/13/14
 * Time: 12:38 AM
 */

?>
<?php function auto_copyright($year = 'auto') { ?>
	<?php if (intval($year) == 'auto') {
		$year = date('Y');
	} ?>
	<?php if (intval($year) == date('Y')) {
		echo intval($year);
	} ?>
	<?php if (intval($year) < date('Y')) {
		echo intval($year) . ' - ' . date('Y');
	} ?>
	<?php if (intval($year) > date('Y')) {
		echo date('Y');
	} ?>
<?php } ?>

<footer id="footer" class="navbar navbar-fixed-bottom">
	<ul class="nav pull-right">
		<li>
			Copyright &copy; <?php auto_copyright('2014'); // 2010 - 2011 ?>, <a href="https://github.com/rdok">rdok</a>
			&amp; <a href="http://gr.linkedin.com/pub/georgios-skarlatos/70/461/123">geoif</a>

		</li>
	</ul>
</footer>
