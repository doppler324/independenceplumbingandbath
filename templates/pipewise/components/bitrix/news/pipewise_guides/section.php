<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @var CMain $APPLICATION */

$section = null;
if (CModule::IncludeModule("iblock")) {
	$section = CIBlockSection::GetList(
		[],
		[
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
			"ACTIVE" => "Y",
			"GLOBAL_ACTIVE" => "Y",
		],
		false,
		["ID", "NAME", "CODE", "DESCRIPTION"]
	)->Fetch();
}

if (!$section) {
	\Bitrix\Iblock\Component\Tools::process404("", true, true, true);
	return;
}

$intro = trim(strip_tags($section["DESCRIPTION"]));

$APPLICATION->AddChainItem("Guides", $arResult["FOLDER"]);
$APPLICATION->AddChainItem($section["NAME"]);
$APPLICATION->SetTitle($section["NAME"]);
$APPLICATION->SetPageProperty("title", $section["NAME"] . ": Guides and Fixes | PipeWise");
if ($intro !== "") {
	$APPLICATION->SetPageProperty("description", $intro);
}
?>
<section class="container list-head">
	<?php $APPLICATION->IncludeComponent("bitrix:breadcrumb", "pipewise", ["START_FROM" => "0", "PATH" => "", "SITE_ID" => SITE_ID], false); ?>
	<span class="eyebrow">Guides</span>
	<h1 class="list-head__title"><?= htmlspecialcharsbx($section["NAME"]) ?></h1>
	<?php if ($intro !== ""): ?>
		<p class="list-head__lead"><?= htmlspecialcharsbx($intro) ?></p>
	<?php endif; ?>
</section>

<section class="container home-section">
	<?php
	$APPLICATION->IncludeComponent("bitrix:news.list", "guides_cards", [
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"PARENT_SECTION" => $section["ID"],
		"INCLUDE_SUBSECTIONS" => "Y",
		"NEWS_COUNT" => "12",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FIELD_CODE" => [],
		"PROPERTY_CODE" => ["READ_TIME"],
		"DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["detail"],
		"SET_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"CHECK_DATES" => "Y",
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "modern",
		"PAGER_TITLE" => "Guides",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
	], $component);
	?>
</section>
