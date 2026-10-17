<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult)) return;
?>
<nav class="footer-nav" aria-label="Footer">
	<?php foreach ($arResult as $item): ?>
		<a href="<?= htmlspecialcharsbx($item["LINK"]) ?>"><?= htmlspecialcharsbx($item["TEXT"]) ?></a>
	<?php endforeach; ?>
</nav>
