<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @var CMain $APPLICATION */

$APPLICATION->AddChainItem("Guides");
$APPLICATION->SetTitle("Plumbing guides");
$APPLICATION->SetPageProperty("title", "Plumbing Guides: Fixes, Costs and When to Call a Pro | PipeWise");
$APPLICATION->SetPageProperty("description", "Browse plain-English plumbing guides for US homes: leaks, drains, water heaters, toilets, pipes, costs and emergencies.");

$sections = [];
if (CModule::IncludeModule("iblock")) {
	$rs = CIBlockSection::GetList(
		["SORT" => "ASC", "NAME" => "ASC"],
		["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y"],
		true,
		["ID", "NAME", "CODE", "DESCRIPTION"]
	);
	while ($s = $rs->Fetch()) {
		$sections[] = $s;
	}
}
?>
<section class="container list-head">
	<?php $APPLICATION->IncludeComponent("bitrix:breadcrumb", "pipewise", ["START_FROM" => "0", "PATH" => "", "SITE_ID" => SITE_ID], false); ?>
	<span class="eyebrow">Guides</span>
	<h1 class="list-head__title">Plumbing guides</h1>
	<p class="list-head__lead">Pick a topic, or scroll down for the latest guides.</p>
</section>

<?php if ($sections): ?>
<section class="container home-section">
	<div class="tiles">
		<?php foreach ($sections as $s): ?>
			<a class="tile" href="<?= htmlspecialcharsbx($arResult["FOLDER"] . $s["CODE"] . "/") ?>">
				<strong><?= htmlspecialcharsbx($s["NAME"]) ?></strong>
				<?php if (trim(strip_tags($s["DESCRIPTION"])) !== ""): ?>
					<span><?= htmlspecialcharsbx(trim(strip_tags($s["DESCRIPTION"]))) ?></span>
				<?php endif; ?>
			</a>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>

<section class="container home-section">
	<h2 class="home-section__title">Latest guides</h2>
	<?php
	$APPLICATION->IncludeComponent("bitrix:news.list", "guides_cards", [
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
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
