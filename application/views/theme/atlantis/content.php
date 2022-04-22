<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title"> <?php if (isset($title)) {
                                            echo $title;
                                        } else {
                                            echo "title Kosong";
                                        } ?></h4>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="<?= base_url($this->uri->segment(0))  ?>">
                            <i class="flaticon-home"></i>
                        </a>
                    </li>
                    <?php if ($this->uri->segment(1) !== null) { ?>
                        <li class="separator">
                            <i class="flaticon-right-arrow"></i>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url($this->uri->segment(1))  ?>"><?= $this->uri->segment(1) ?></a>
                        </li>
                    <?php } else {
                    } ?>
                    <?php if ($this->uri->segment(2) !== null) { ?>
                        <li class="separator">
                            <i class="flaticon-right-arrow"></i>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url($this->uri->segment(2)) ?>"><?= $this->uri->segment(2) ?></a>
                        </li>
                    <?php } else {
                    } ?>
                    <?php if ($this->uri->segment(3) !== null) { ?>
                        <li class="separator">
                            <i class="flaticon-right-arrow"></i>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url($this->uri->segment(3)) ?>"><?= $this->uri->segment(3) ?></a>
                        </li>
                    <?php } else {
                    } ?>
                </ul>
            </div>
            <div class="container">
                <div class="col-md-12">
                    <?php if (isset($card)) {
                        echo $card;
                    } else {
                        echo "Config Card Tidak ada";
                    } ?>
                </div>
            </div>
        </div>
    </div>