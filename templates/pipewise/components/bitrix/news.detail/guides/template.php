<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
/** @var CMain $APPLICATION */

$p = $arResult["PROPERTIES"] ?? [];
$val = function ($code) use ($p) {
	if (!isset($p[$code]["VALUE"]) || is_array($p[$code]["VALUE"])) {
		return "";
	}
	return trim((string)$p[$code]["VALUE"]);
};

$author = $val("AUTHOR");
$reviewer = $val("REVIEWER");
$mins = (int)$val("READ_TIME");
$difficulty = $val("DIFFICULTY");
$timeNeeded = $val("TIME_NEEDED");
$tools = $val("TOOLS");

$tag = "Guides";
$tagUrl = "/guides/";
if (!empty($arResult["SECTION"]["PATH"])) {
	$last = end($arResult["SECTION"]["PATH"]);
	$tag = $last["NAME"];
	$tagUrl = "/guides/" . $last["CODE"] . "/";
}

$deck = trim(strip_tags($arResult["PREVIEW_TEXT"]));
$ts = MakeTimeStamp($arResult["TIMESTAMP_X"] ?: $arResult["DATE_CREATE"]);
$updated = $ts ? date("F j, Y", $ts) : "";
$pic = (!empty($arResult["DETAIL_PICTURE"]) && is_array($arResult["DETAIL_PICTURE"])) ? $arResult["DETAIL_PICTURE"] : null;

$APPLICATION->SetPageProperty("title", $arResult["NAME"] . " | PipeWise");
if ($deck !== "") {
	$APPLICATION->SetPageProperty("description", $deck);
}
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/guide.js");

$ld = [
	"@context" => "https://schema.org",
	"@type" => "Article",
	"headline" => $arResult["NAME"],
	"mainEntityOfPage" => "https://independenceplumbingandbath.com" . $arResult["DETAIL_PAGE_URL"],
	"publisher" => ["@type" => "Organization", "name" => "PipeWise"],
];
if ($deck !== "") {
	$ld["description"] = $deck;
}
if ($ts) {
	$ld["dateModified"] = date("c", $ts);
}
if ($author !== "") {
	$ld["author"] = ["@type" => "Person", "name" => $author];
}
if ($pic) {
	$ld["image"] = "https://independenceplumbingandbath.com" . $pic["SRC"];
}
?>
<script type="application/ld+json"><?= json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>

<article>
	<header class="container guide-head">
		<?php $APPLICATION->IncludeComponent("bitrix:breadcrumb", "pipewise", ["START_FROM" => "0", "PATH" => "", "SITE_ID" => SITE_ID], false); ?>
		<a class="eyebrow" href="<?= htmlspecialcharsbx($tagUrl) ?>"><?= htmlspecialcharsbx($tag) ?></a>
		<h1 class="guide-head__title"><?= htmlspecialcharsbx($arResult["NAME"]) ?></h1>
		<?php if ($deck !== ""): ?><p class="guide-head__deck"><?= htmlspecialcharsbx($deck) ?></p><?php endif; ?>
		<div class="guide-meta">
			<?php if ($author !== ""): ?><span>Written by <strong><?= htmlspecialcharsbx($author) ?></strong></span><?php endif; ?>
			<?php if ($reviewer !== ""): ?>
				<span class="guide-meta__review"><svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="m4 10.500 4 4 8-9" stroke="#1F6F78" stroke-width="2.200" stroke-linecap="round" stroke-linejoin="round"/></svg>Reviewed by <strong><?= htmlspecialcharsbx($reviewer) ?></strong></span>
			<?php endif; ?>
			<?php if ($updated !== ""): ?><span>Updated <?= htmlspecialcharsbx($updated) ?></span><?php endif; ?>
			<?php if ($mins > 0): ?><span><?= $mins ?> min read</span><?php endif; ?>
		</div>
	</header>

	<div class="container guide-layout">
		<nav class="toc" id="guide-toc" aria-label="In this guide" hidden>
			<div class="eyebrow">In this guide</div>
		</nav>

		<div class="guide-body">
			<?php if ($pic): ?>
				<img class="guide-hero" src="<?= htmlspecialcharsbx($pic["SRC"]) ?>" alt="<?= htmlspecialcharsbx($pic["ALT"] ?: $arResult["NAME"]) ?>" width="<?= (int)$pic["WIDTH"] ?>" height="<?= (int)$pic["HEIGHT"] ?>">
			<?php endif; ?>

			<?php if ($difficulty !== "" || $timeNeeded !== "" || $tools !== ""): ?>
				<div class="facts">
					<h2 class="facts__title">Before you start</h2>
					<div class="facts__grid">
						<?php if ($difficulty !== ""): ?><div><div class="facts__label">Difficulty</div><div class="facts__value"><?= htmlspecialcharsbx($difficulty) ?></div></div><?php endif; ?>
						<?php if ($timeNeeded !== ""): ?><div><div class="facts__label">Time</div><div class="facts__value"><?= htmlspecialcharsbx($timeNeeded) ?></div></div><?php endif; ?>
						<?php if ($tools !== ""): ?><div><div class="facts__label">Tools</div><div class="facts__value"><?= htmlspecialcharsbx($tools) ?></div></div><?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="prose"><?= $arResult["DETAIL_TEXT"] ?></div>
		</div>

		<aside class="guide-aside">
			<?php $APPLICATION->ShowViewContent("guide_related"); ?>
			<div class="aside-box aside-box--mint">
				<h2 class="aside-box__title">Not sure it&rsquo;s the right fix?</h2>
				<p>Use the topic pages to narrow a problem down by where the water shows up.</p>
				<a class="aside-box__link" href="/guides/">Browse all guides &rarr;</a>
			</div>
		</aside>
	</div>
</article>
