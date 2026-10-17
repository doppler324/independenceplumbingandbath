<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult)) return "";

$count = count($arResult);
$html = '<nav class="crumbs" aria-label="Breadcrumb">';

foreach ($arResult as $i => $crumb) {
	$title = htmlspecialcharsex($crumb["TITLE"]);
	if ($i > 0) {
		$html .= '<span aria-hidden="true">/</span>';
	}
	if ($crumb["LINK"] <> "" && $i < $count - 1) {
		$html .= '<a href="' . htmlspecialcharsbx($crumb["LINK"]) . '">' . $title . '</a>';
	} else {
		$html .= '<span aria-current="page">' . $title . '</span>';
	}
}

return $html . '</nav>';
