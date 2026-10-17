<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

if (empty($arResult["ITEMS"])) {
	echo '<p class="empty">No guides here yet.</p>';
	return;
}
?>
<div class="cards">
	<?php foreach ($arResult["ITEMS"] as $item):
		$this->AddEditAction($item["ID"], $item["EDIT_LINK"], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($item["ID"], $item["DELETE_LINK"], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => "Delete this guide?"]);

		$tag = $arResult["SECTION_NAMES"][(int)$item["IBLOCK_SECTION_ID"]] ?? "";
		$mins = isset($item["PROPERTIES"]["READ_TIME"]["VALUE"]) ? (int)$item["PROPERTIES"]["READ_TIME"]["VALUE"] : 0;
		$pic = is_array($item["PREVIEW_PICTURE"]) ? $item["PREVIEW_PICTURE"] : null;
		?>
		<a class="card" id="<?= $this->GetEditAreaId($item["ID"]) ?>" href="<?= htmlspecialcharsbx($item["DETAIL_PAGE_URL"]) ?>">
			<?php if ($pic): ?>
				<img class="card__img" src="<?= htmlspecialcharsbx($pic["SRC"]) ?>" alt="<?= htmlspecialcharsbx($pic["ALT"] ?: $item["NAME"]) ?>" width="<?= (int)$pic["WIDTH"] ?>" height="<?= (int)$pic["HEIGHT"] ?>" loading="lazy">
			<?php else: ?>
				<div class="card__img card__img--ph" aria-hidden="true"></div>
			<?php endif; ?>
			<?php if ($tag !== ""): ?><span class="card__tag"><?= htmlspecialcharsbx($tag) ?></span><?php endif; ?>
			<h3 class="card__title"><?= htmlspecialcharsbx($item["NAME"]) ?></h3>
			<?php if ($mins > 0): ?><span class="card__meta"><?= $mins ?> min read</span><?php endif; ?>
		</a>
	<?php endforeach; ?>
</div>
<?php if ($arParams["DISPLAY_BOTTOM_PAGER"] === "Y" && $arResult["NAV_STRING"] <> ""): ?>
	<div class="pager"><?= $arResult["NAV_STRING"] ?></div>
<?php endif; ?>
