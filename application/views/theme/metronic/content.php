<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-2">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5"><?php if (isset($title)) {
                                                                            echo $title;
                                                                        } else {
                                                                            echo "title Kosong";
                                                                        } ?></h4>
                </h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url($this->uri->segment(0))  ?>" class="text-muted">Home</a>
                    </li>
                    <?php if ($this->uri->segment(1) !== null) { ?>
                        <li class="breadcrumb-item">
                            <a href="<?= base_url($this->uri->segment(1))  ?>" class="text-muted"><?= $this->uri->segment(1) ?></a>
                        </li>
                    <?php } else {
                    } ?>
                    <?php if ($this->uri->segment(2) !== null) { ?>
                        <li class="breadcrumb-item">
                            <a href="<?= base_url($this->uri->segment(2))  ?>" class="text-muted"><?= $this->uri->segment(2) ?></a>
                        </li>
                    <?php } else {
                    } ?>
                    <?php if ($this->uri->segment(3) !== null) { ?>
                        <li class="breadcrumb-item">
                            <a href="<?= base_url($this->uri->segment(3))  ?>" class="text-muted"><?= $this->uri->segment(3) ?></a>
                        </li>
                    <?php } else {
                    } ?>
                </ul>
                <!--end::Actions-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Content-->
            <?php if (isset($card)) {
                echo $card;
            } else {
                echo "Config Card Tidak ada";
            } ?>
            <!--end::Content-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
<!--end::Content-->