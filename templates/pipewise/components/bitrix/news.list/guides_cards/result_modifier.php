<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// section names for the small label above each card title
$arResult["SECTION_NAMES"] = [];
$ids = [];
foreach ($arResult["ITEMS"] as $item) {
	if ((int)$item["IBLOCK_SECTION_ID"] > 0) {
		$ids[(int)$item["IBLOCK_SECTION_ID"]] = true;
	}
}
if ($ids && CModule::IncludeModule("iblock")) {
	$rs = CIBlockSection::GetList([], ["ID" => array_keys($ids)], false, ["ID", "NAME"]);
	while ($s = $rs->Fetch()) {
		$arResult["SECTION_NAMES"][(int)$s["ID"]] = $s["NAME"];
	}
}
