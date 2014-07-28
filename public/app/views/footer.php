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
</div>

<!-- TODO: load library only when needed. Currently loads no matter the page. -->
<script src="<?php echo BASE_URL; ?>app/assets/js/libs/jquery-1.9.1.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/libs/jquery-ui-1.9.2.custom.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/libs/bootstrap.min.js"></script>


<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datatables/DT_bootstrap.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/tableCheckable/jquery.tableCheckable.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/icheck/jquery.icheck.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/icheck/jquery.icheck.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/timepicker/bootstrap-timepicker.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/select2/select2.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/fileupload/bootstrap-fileupload.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>


<script src="<?php echo BASE_URL; ?>app/assets/js/libs/raphael-2.1.2.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/morris/morris.min.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/demos/charts/morris/area.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/demos/charts/morris/donut.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/demos/calendar.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/demos/dashboard.js"></script>


<script src="<?php echo BASE_URL; ?>app/assets/js/App.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/demos/form-extended.js"></script>


</body>
</html>