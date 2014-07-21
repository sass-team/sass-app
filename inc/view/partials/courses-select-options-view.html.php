<?php
/**
 * Expects $major & $course
 */
?>

<optgroup label="<?php echo $major; ?>">
	<?php foreach ($courses as $course) { ?>
		<?php if ($course['Major'] === $major) { ?>
			<option
				value="<?php echo $course['Extension'] . $course['Code']; ?>">
				<?php echo $course['Extension'] . " " . $course['Code'] . " " . $course['Name'] . " Level " . $course['Level']; ?></option>
		<?php
		}
	}?>
</optgroup>

