<div class="main-panel">
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
                        <h5 class="text-white op-7 mb-2">Statistics Dashboard</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="<?= base_url('Dashboard/form') ?>" class="btn btn-white btn-border btn-round mr-2">Manage</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="card-title">Sales Statistics</div>
                        <div class="card-category">Monthly information about statistics in system</div>
                        <div class="chart-container">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Marketing</th>
                                        <th scope="col">Jumlah Penjualan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0 ?>
                                    <?php $this->load->model('M_dashboard'); ?>
                                    <?php foreach ($sales as $val) { ?>
                                        <?php $data_name = $this->M_dashboard->get_sales_name($val['sales_id']) ?>
                                        <?php foreach ($data_name as $key) {
                                            $sales_name = $key['employe_name'];
                                        } ?>
                                        <tr>
                                            <td><?= ++$i ?></td>
                                            <td><?= $sales_name ?></td>
                                            <td><?= $val['sum(qty)'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>