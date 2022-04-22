<!-- Sidebar -->
<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <?php foreach ($sidebar as $value) { ?>
                <ul class="nav nav-primary">
                    <?php if ($value['sub_menu'] == '' || $value['sub_menu'] == null) { ?>
                        <li <?= $value['condition'] == "true" ? 'class="nav-item active"' : 'class="nav-item"' ?>>
                            <a href="<?= base_url($value['link']) ?>">
                                <i class="<?= $value['icon'] ?>"></i>
                                <p><?= $value['title'] ?></p>
                            </a>
                        </li>
                    <?php } else { ?>
                        <?php if ($value['title-group'] == '' || $value['title-group'] == null) { ?>
                        <?php } else { ?>
                            <li class="nav-section">
                                <span class="sidebar-mini-icon">
                                    <i class="fa fa-ellipsis-h"></i>
                                </span>
                                <h4 class="text-section"><?= $value['title-group'] ?></h4>
                            </li>
                        <?php } ?>
                        <li <?= $value['condition'] == "true" ? 'class="nav-item active submenu"' : 'class="nav-item"' ?>>
                            <a <?= $value['sub_menu'] !== '' ? 'data-toggle="collapse"' : '' ?> href="<?= $value['link'] ?>">
                                <i class="<?= $value['icon'] ?>"></i>
                                <p><?= $value['title'] ?></p>
                                <span <?= $value['sub_menu'] !== '' ? ' class="caret"' : 'class=""' ?>></span>
                            </a>
                            <div <?= $value['condition'] == "true" ? 'class="collapse show"' : 'class="collapse"' ?> id="<?= $value['id_collapse'] ?>">
                                <ul class="nav nav-collapse">
                                    <?php foreach ($value['sub_menu'] as $val) { ?>
                                        <li <?= $val['condition'] == "true"  ? 'class="active"' : 'class=""' ?>>
                                            <a href="<?= base_url($val['link']) ?>">
                                                <span class="sub-item"><?= $val['title'] ?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </li>
                </ul>
            <?php } ?>
        <?php } ?>
        </div>
    </div>
</div>
<!-- End Sidebar -->