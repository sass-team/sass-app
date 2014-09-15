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
 * @author George Skarlatos
 * @since 9/15/2014
 */
?>
<tr>
	<td class="text-center"><?php echo htmlentities($term[TermFetcher::DB_COLUMN_NAME]); ?></td>
	<td class="text-center"><?php echo htmlentities($term[TermFetcher::DB_COLUMN_START_DATE]); ?></td>
	<td class="text-center"><?php echo htmlentities($term[TermFetcher::DB_COLUMN_END_DATE]); ?></td>
	<td class="text-center">
		<a href="#updateCourse" data-toggle="modal" class="btn btn-xs btn-primary btnUpdateTerm"><i
				class="fa fa-pencil fa-lg"></i></a>
		<a href="#deleteCourse" data-toggle="modal" class="btn btn-xs btn-secondary btnDeleteTerm"><i
				class="fa fa-times fa-lg"></i></a>
		<input type="hidden" value="<?php echo $term[TermFetcher::DB_COLUMN_ID]; ?>"/>
	</td>
</tr>
