<?php
/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/13/14
 * Time: 12:38 AM
 */

?>
<?php
function auto_copyright($year = 'auto') {
	if (strcmp($year, 'auto') === 0) $year = date('Y');

	if (intval($year) == date('Y')) return intval($year);
	if (intval($year) < date('Y')) return intval($year) . ' - ' . date('Y');
	if (intval($year) > date('Y')) return date('Y');
}

?>

<div id="footer">
	<ul class="nav pull-right">
		<li>
			Copyright &copy; <?php echo auto_copyright('2014'); // 2010 - 2011 ?>, <a
				href="https://github.com/rdok">rdok</a>
			&amp; <a href="http://gr.linkedin.com/pub/georgios-skarlatos/70/461/123">geoif</a>
		</li>
	</ul>
</div>