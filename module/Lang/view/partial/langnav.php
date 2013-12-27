<ul class="nav navbar-nav">
    <?php $aclGuard = $this->layout()->aclGuard ?>
    <?php $acl = $aclGuard->getAcl() ?>
    <?php $role = $aclGuard->getRole() ?>
    <?php foreach ($this->container as $page): ?>
        <?php /* @var $page Zend\Navigation\Page\Mvc */ ?>
        <?php // when using partials we need to manually check for ACL conditions ?>
        <?php $isAllowed = $acl->isAllowed($role, $page->getRoute()) ?>
        <?php if( !$isAllowed || ! $page->isVisible() || !$this->navigation()->accept($page)) continue; ?>

        <?php $this->lang($page) ?>

        <?php if( ! $page->hasPages()): ?>
            <li class="<?php if ($page->isActive()) echo 'active' ?>">
                <a class="nav-header" href="<?= $page->getHref() ?>">
                    <?= $this->translate($page->getLabel(), 'lang') ?>
                </a>
            </li>
        <?php else: ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="<?= $page->getHref() ?>">
                    <?= $this->translate($page->getLabel(), 'lang') ?>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                <?php foreach($page->getPages() as $child): ?>
                    <?php // when using partials we need to manually check for ACL conditions ?>
                    <?php if( ! $child->isVisible() || !$this->navigation()->accept($child)) continue; ?>

                    <?php $this->lang($child) ?>

                    <?php if (isset($child->divider) && 'above' === $child->divider): ?>
                        <li role="presentation" class="divider"></li>
                    <?php endif;?>
                    <?php if (isset($child->header)): ?>
                        <li role="presentation" class="dropdown-header"><?= $child->header?></li>
                    <?php endif;?>
                    <li>
                        <a href="<?= $child->getHref() ?>">
                            <?= $this->translate($child->getLabel(), 'lang') ?>
                        </a>
                    </li>
                    <?php if (isset($child->divider) && 'below' === $child->divider): ?>
                        <li class="divider"></li>
                    <?php endif;?>
                <?php endforeach ?>
                </ul>
            </li>
        <?php endif ?>
    <?php endforeach ?>
</ul>
