<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/15/2014
 * Time: 10:52 PM
 */

require __DIR__ . '/../app/init.php';

$reports = ReportFetcher::retrieveAll();


foreach ($reports as $report) {
	$reportId = $report[ReportFetcher::DB_COLUMN_ID];
	if (!StudentBroughtAlongFetcher::exists($reportId)) StudentBroughtAlongFetcher::insert($reportId);
	if (!PrimaryFocusOfConferenceFetcher::exists($reportId)) PrimaryFocusOfConferenceFetcher::insert($reportId);
	if (!ConclusionWrapUpFetcher::exists($reportId)) ConclusionWrapUpFetcher::insert($reportId);
}
