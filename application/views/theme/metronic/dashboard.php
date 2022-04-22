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
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header d-flex align-items-left align-items-md-center flex-column flex-md-row">
                            <div class="card-title">
                                <h3>Sales Statistics</h3>
                                <div class="card-category">Monthly information about statistics in system</div>
                            </div>
                            <div class="ml-md-auto py-2 py-md-0">
                                <a href="<?= base_url('Dashboard/form') ?>" class="btn btn-success btn-border btn-round mr-2">Manage</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Marketing</th>
                                            <th scope="col" style="text-align: center;">Jumlah Penjualan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0 ?>
                                        <?php $this->load->model('M_dashboard'); ?>
                                        <?php foreach ($sales as $val) { ?>
                                            <?php $data_name = $this->M_dashboard->get_sales_name($val['sales_id']) ?>
                                            <?php $data_product = $this->M_dashboard->get_product($val['product_id']) ?>
                                            <?php foreach ($data_name as $key) {
                                                $sales_name = $key['employe_name'];
                                            } ?>
                                            <?php foreach ($data_product as $var) {
                                                $qty = $val['sum(qty)'] * $var['qty'];
                                            } ?>
                                            <tr>
                                                <td><?= ++$i ?></td>
                                                <td><?= $sales_name ?></td>
                                                <td style="text-align: center;"><?= $qty ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>Sales Statistics</h3>
                                <div class="card-category">Monthly information about statistics in system</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!--begin::Chart-->
                            <div id="chart_12" class="d-flex justify-content-center"></div>
                            <!--end::Chart-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
<!--end::Content-->