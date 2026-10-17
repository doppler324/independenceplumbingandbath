<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

if (empty($arResult["ITEMS"])) {
	return;
}

// the guide detail template prints this block with $APPLICATION->ShowViewContent("guide_related")
$this->SetViewTarget("guide_related");
?>
<div class="aside-box">
	<h2 class="aside-box__title">Related guides</h2>
	<ul class="aside-list">
		<?php foreach ($arResult["ITEMS"] as $item): ?>
			<li><a href="<?= htmlspecialcharsbx($item["DETAIL_PAGE_URL"]) ?>"><?= htmlspecialcharsbx($item["NAME"]) ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
<?php
$this->EndViewTarget();
