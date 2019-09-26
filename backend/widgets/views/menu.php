<?php
/**
 * @var $items array
 */
?>
<aside id="sidebar-left" class="sidebar-left">
    <div class="sidebar-header">
        <div class="sidebar-title">
            <?= Yii::t('app', 'Menu') ?>
        </div>
        <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    <?php foreach ($items as $item): ?>
                        <?php if (isset($item['child']) && count($item['child']) > 0): ?>
                            <li class="nav-parent <?php if ($item['active']): ?>nav-expanded<?php endif; ?>">
                                <a>
                                    <i class="<?=$item['icon']?>" aria-hidden="true"></i>
                                    <span><?=$item['name']?></span>
                                </a>

                                <ul class="nav nav-children">
                                    <?php foreach ($item['child'] as $itemChild): ?>
                                        <li <?php if ($itemChild['active']): ?>class="nav-active"<?php endif ?>>
                                            <a href="<?=$itemChild['url']?>">
                                                <i class="<?=$itemChild['icon']?>" aria-hidden="true"></i>
                                                <?=$itemChild['name']?>
                                            </a>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li <?php if ($item['active']): ?>class="nav-active"<?php endif ?>>
                                <a href="<?=$item['url']?>">
                                    <i class="<?=$item['icon']?>" aria-hidden="true"></i>
                                    <span><?=$item['name']?></span>
                                </a>
                            </li>
                        <?php endif ?>
                    <?php endforeach ?>
                </ul>
            </nav>
        </div>
    </div>
</aside>