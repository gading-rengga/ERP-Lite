<footer class="footer">
    <div class="container-fluid">
        <nav class="pull-left">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="https://www.themekita.com">
                        ThemeKita
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Help
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Licenses
                    </a>
                </li>
            </ul>
        </nav>
        <div class="copyright ml-auto">
            2018, made with <i class="fa fa-heart heart text-danger"></i> by <a href="https://www.themekita.com">ThemeKita</a>
        </div>
    </div>
</footer>
</div>


</div>

<!--   Core JS Files   -->
<script src="<?= base_url("assets/atlantis/"); ?>js/core/jquery.3.2.1.min.js"></script>
<script src="<?= base_url("assets/atlantis/"); ?>js/core/popper.min.js"></script>
<script src="<?= base_url("assets/atlantis/"); ?>js/core/bootstrap.min.js"></script>

<!-- jQuery UI -->
<script src="<?= base_url("assets/atlantis/"); ?>js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="<?= base_url("assets/atlantis/"); ?>js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

<!-- jQuery Scrollbar -->
<script src="<?= base_url("assets/atlantis/"); ?>js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>


<!-- Chart JS -->
<script src="<?= base_url("assets/atlantis/"); ?>js/plugin/chart.js/chart.min.js"></script>

<!-- jQuery Sparkline -->
<script src="<?= base_url("assets/atlantis/"); ?>js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

<!-- Chart Circle -->
<script src="<?= base_url("assets/atlantis/"); ?>js/plugin/chart-circle/circles.min.js"></script>

<!-- Datatables -->
<script src="<?= base_url("assets/atlantis/"); ?>js/plugin/datatables/datatables.min.js"></script>

<!-- Bootstrap Notify -->
<script src="<?= base_url("assets/atlantis/"); ?>js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- jQuery Vector Maps -->
<script src="<?= base_url("assets/atlantis/"); ?>js/plugin/jqvmap/jquery.vmap.min.js"></script>
<script src="<?= base_url("assets/atlantis/"); ?>js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

<!-- Sweet Alert -->
<script src="<?= base_url("assets/atlantis/"); ?>js/plugin/sweetalert/sweetalert.min.js"></script>

<!-- Atlantis JS -->
<script src="<?= base_url("assets/atlantis/"); ?>js/atlantis.min.js"></script>

<script src="<?= base_url("assets/atlantis/"); ?>/js/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>





<script>
    var myPieChart = new Chart(pieChart, {
        type: 'pie',
        data: {
            datasets: [{
                data: [40, 35, 15, 10],
                backgroundColor: ["#1d7af3", "#f3545d", "#fdaf4b"],
                borderWidth: 0
            }],
            labels: ['New Visitors', 'Subscribers', 'Active Users', 'Broken']
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom',
                labels: {
                    fontColor: 'rgb(154, 154, 154)',
                    fontSize: 11,
                    usePointStyle: true,
                    padding: 20
                }
            },
            pieceLabel: {
                render: 'percentage',
                fontColor: 'white',
                fontSize: 14,
            },
            tooltips: false,
            layout: {
                padding: {
                    left: 20,
                    right: 20,
                    top: 20,
                    bottom: 20
                }
            }
        }
    })
</script>

</body>

</html>