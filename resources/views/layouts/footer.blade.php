<!-- jQuery (Loaded first, before all plugins) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</div>
<!-- /Container -->

<!-- Footer -->
<div class="hk-footer-wrap container">
    <footer class="footer">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <p>Designed by<a href="www.orioncc.com" class="text-dark" target="_blank">Orion IT Department</a> © 2025</p>
            </div>
            <div class="col-md-6 col-sm-12">
                <p class="d-inline-block">Follow us</p>
                <a href="https://www.facebook.com/orioncontractingcompany" class="d-inline-block btn btn-icon btn-icon-only btn-indigo btn-icon-style-4"><span class="btn-icon-wrap"><i class="fa fa-facebook"></i></span></a>
                <a href="https://www.linkedin.com/company/orion-contracting-company-llc" class="d-inline-block btn btn-icon btn-icon-only btn-indigo btn-icon-style-4"><span class="btn-icon-wrap"><i class="fa fa-linkedin"></i></span></a>
                <a href="https://www.youtube.com/@orioncontracting9881" class="d-inline-block btn btn-icon btn-icon-only btn-indigo btn-icon-style-4"><span class="btn-icon-wrap"><i class="fa fa-youtube"></i></span></a>
            </div>
        </div>
    </footer>
</div>
<!-- /Footer -->
</div>
<!-- /Main Content -->

</div>
<!-- /HK Wrapper -->

<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('dashAssets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<!-- Slimscroll JavaScript -->
<script src="{{ asset('dashAssets/dist/js/jquery.slimscroll.js') }}"></script>

@yield('scripts')

<!-- Fancy Dropdown JS -->
{{-- <script src="{{ asset('dashAssets/dist/js/dropdown-bootstrap-extended.js') }}"></script> --}}

<!-- FeatherIcons JavaScript -->
<script src="{{ asset('dashAssets/dist/js/feather.min.js') }}"></script>

<!-- Toggles JavaScript -->
<script src="{{ asset('dashAssets/vendors/jquery-toggles/toggles.min.js') }}"></script>
<script src="{{ asset('dashAssets/dist/js/toggle-data.js') }}"></script>

<!-- Toastr JS -->
<script src="{{ asset('dashAssets/vendors/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
<script src="{{ asset('dashAssets/dist/js/toast-init.js') }}"></script>

<!-- Counter Animation JavaScript -->
<script src="{{ asset('dashAssets/vendors/waypoints/lib/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/jquery.counterup/jquery.counterup.min.js') }}"></script>

<!-- Morris Charts JavaScript -->
<script src="{{ asset('dashAssets/vendors/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/morris.js/morris.min.js') }}"></script>

<!-- Easy pie chart JS -->
<script src="{{ asset('dashAssets/vendors/easy-pie-chart/dist/jquery.easypiechart.min.js') }}"></script>

<!-- Flot Charts JavaScript -->
<script src="{{ asset('dashAssets/vendors/flot/excanvas.min.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/flot/jquery.flot.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/flot/jquery.flot.time.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/flot/jquery.flot.stack.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/flot/jquery.flot.crosshair.js') }}"></script>
<script src="{{ asset('dashAssets/vendors/jquery.flot.tooltip/js/jquery.flot.tooltip.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- EChartJS JavaScript -->
<script src="{{ asset('dashAssets/vendors/echarts/dist/echarts-en.min.js') }}"></script>

<!-- Init JavaScript -->
<script src="{{ asset('dashAssets/dist/js/init.js') }}"></script>
<script src="{{ asset('dashAssets/dist/js/dashboard2-data.js') }}"></script>
@yield('forceScripts')
@stack('scripts') {{-- This is where the script from timesheet/index.blade.php will be injected --}}

{{-- Toastr Scripts - Ensure these functions (showSuccessToast etc.) are defined, possibly in your Vite-bundled app.js or another global script --}}
@if(session('toast_success'))
<script>
    $(document).ready(function() { // Ensure jQuery is loaded
        if (typeof showSuccessToast === 'function') {
            showSuccessToast("{{ session('toast_success') }}");
        } else {
            console.warn('showSuccessToast function not found.');
        }
    });
</script>
@endif
@if(session('toast_error'))
<script>
    $(document).ready(function() {
        if (typeof showErrorToast === 'function') {
            showErrorToast("{{ session('toast_error') }}");
        } else {
            console.warn('showErrorToast function not found.');
        }
    });
</script>
@endif
@if(session('toast_warning'))
<script>
    $(document).ready(function() {
        if (typeof showWarningToast === 'function') {
            showWarningToast("{{ session('toast_warning') }}");
        } else {
            console.warn('showWarningToast function not found.');
        }
    });
</script>
@endif
@if(session('toast_info'))
<script>
    $(document).ready(function() {
        if (typeof showInfoToast === 'function') {
            showInfoToast("{{ session('toast_info') }}");
        } else {
            console.warn('showInfoToast function not found.');
        }
    });
</script>
@endif
@livewireScripts
</body>

</html>
