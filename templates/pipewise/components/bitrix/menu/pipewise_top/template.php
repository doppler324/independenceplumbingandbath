<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult)) return;
?>
<nav class="nav" aria-label="Main">
	<?php foreach ($arResult as $item): ?>
		<a class="nav__link<?= $item["SELECTED"] ? " is-active" : "" ?>" href="<?= htmlspecialcharsbx($item["LINK"]) ?>"><?= htmlspecialcharsbx($item["TEXT"]) ?></a>
	<?php endforeach; ?>
</nav>
