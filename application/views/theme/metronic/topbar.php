<!--begin::Wrapper-->
<?php $data_user = $this->session->userdata(['data'][0]); ?>
<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
    <!--begin::Header-->
    <div id="kt_header" class="header header-fixed">
        <!--begin::Container-->
        <div class="container-fluid d-flex align-items-stretch justify-content-between">
            <!--begin::Header Menu Wrapper-->
            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                <!--begin::Header Menu-->

                <!--end::Header Menu-->
            </div>
            <!--end::Header Menu Wrapper-->
            <!--begin::Topbar-->
            <div class="topbar">
                <!--begin::User-->
                <div class="topbar-item">
                    <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg border-secondary bg-light-secondary px-2" id="kt_quick_user_toggle">
                        <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
                        <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3"><?= $data_user['employe_name'] ?></span>
                        <span class="symbol symbol-lg-35 symbol-25 symbol-light">
                            <span class="symbol-label">
                                <img src="<?= base_url('assets/metronic-v7/') ?>media/svg/avatars/009-boy-4.svg" class="h-75 align-self-end" alt="">
                            </span>
                        </span>
                    </div>
                </div>
                <div id="notifications"><?php echo $this->session->flashdata('msg'); ?></div>
                <!--end::User-->
            </div>
            <!--end::Topbar-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Header-->