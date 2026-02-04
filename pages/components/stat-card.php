<?php
/**
 * Reusable stat card component
 *
 * Required variables:
 * - $gradientClass
 * - $iconBgClass
 * - $iconSvg (raw SVG string)
 * - $badgeText
 * - $badgeClass
 * - $title
 * - $value
 * - $footerText
 * - $footerIconSvg (raw SVG string)
 */
?>
<div class="card-hover bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="<?= e($gradientClass) ?> h-2"></div>
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="icon-wrapper <?= e($iconBgClass) ?> p-3 rounded-xl">
                <?= $iconSvg ?>
            </div>
            <span class="text-sm font-semibold px-3 py-1 rounded-full <?= e($badgeClass) ?>">
                <?= e($badgeText) ?>
            </span>
        </div>
        <h3 class="text-gray-500 text-sm font-medium mb-1"><?= e($title) ?></h3>
        <p class="text-3xl font-bold text-gray-800 mb-2"><?= e($value) ?></p>
        <div class="flex items-center text-sm text-gray-600">
            <?= $footerIconSvg ?>
            <span><?= e($footerText) ?></span>
        </div>
    </div>
</div>
