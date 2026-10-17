<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @var CMain $APPLICATION */

$APPLICATION->AddChainItem("Guides", $arResult["FOLDER"]);

// related guides: same section, without the current one; rendered into the sidebar via a view target
$GLOBALS["arrGuidesRelated"] = ["!CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"]];
$APPLICATION->IncludeComponent("bitrix:news.list", "guides_related", [
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"PARENT_SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
	"INCLUDE_SUBSECTIONS" => "Y",
	"FILTER_NAME" => "arrGuidesRelated",
	"NEWS_COUNT" => "4",
	"SORT_BY1" => "ACTIVE_FROM",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"FIELD_CODE" => [],
	"PROPERTY_CODE" => [],
	"DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["detail"],
	"SET_TITLE" => "N",
	"SET_LAST_MODIFIED" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"CHECK_DATES" => "Y",
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	"DISPLAY_BOTTOM_PAGER" => "N",
	"SET_STATUS_404" => "N",
	"SHOW_404" => "N",
], $component);

$APPLICATION->IncludeComponent("bitrix:news.detail", "guides", [
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
	"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
	"FIELD_CODE" => [],
	"PROPERTY_CODE" => ["AUTHOR", "REVIEWER", "READ_TIME", "DIFFICULTY", "TIME_NEEDED", "TOOLS"],
	"SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
	"DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["detail"],
	"IBLOCK_URL" => $arResult["FOLDER"],
	"CHECK_DATES" => "Y",
	"SET_TITLE" => "Y",
	"SET_CANONICAL_URL" => "Y",
	"SET_LAST_MODIFIED" => "Y",
	"SET_BROWSER_TITLE" => "N",
	"SET_META_KEYWORDS" => "N",
	"SET_META_DESCRIPTION" => "N",
	"ADD_SECTIONS_CHAIN" => "Y",
	"ADD_ELEMENT_CHAIN" => "Y",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"STRICT_SECTION_CHECK" => "Y",
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	"SET_STATUS_404" => "Y",
	"SHOW_404" => "Y",
	"MESSAGE_404" => "",
	"USE_PERMISSIONS" => "N",
], $component);
