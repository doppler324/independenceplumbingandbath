<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// "Home" is always the first crumb; any crumb that points to "/" is skipped
$items = [];
foreach ($arResult as $crumb) {
	if ($crumb["LINK"] === "/") {
		continue;
	}
	$items[] = $crumb;
}

$count = count($items);
$html = '<nav class="crumbs" aria-label="Breadcrumb">';
$html .= $count > 0 ? '<a href="/">Home</a>' : '<span aria-current="page">Home</span>';

foreach ($items as $i => $crumb) {
	$title = htmlspecialcharsex($crumb["TITLE"]);
	$html .= '<span aria-hidden="true">/</span>';
	if ($crumb["LINK"] <> "" && $i < $count - 1) {
		$html .= '<a href="' . htmlspecialcharsbx($crumb["LINK"]) . '">' . $title . '</a>';
	} else {
		$html .= '<span aria-current="page">' . $title . '</span>';
	}
}

return $html . '</nav>';
