<!--
=========================================================
* Material Dashboard 2 - v3.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by www.creative-tim.com & www.updivision.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
@props(['bodyClass'])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets') }}/img/apple-icon.png"> --}}
    {{-- <link rel="icon" type="image/png" href="{{ asset('assets') }}/img/favicon.png"> --}}
    <title>POS</title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets') }}/css/nucleo-icons.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- For Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link id="pagestyle" href="{{ asset('assets') }}/css/material-dashboard.css" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets') }}/css/jquery-ui.css" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets') }}/css/docs.css" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets') }}/fontcss/all.css" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets') }}/fontcss/fontawesome.css" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets') }}/fontcss/fontawesome.min.css" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets') }}/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets') }}/css/pikaday.min.css" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets') }}/css/daterangpicker.css" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets') }}/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
</head>
<body class="{{ $bodyClass }}">

    {{ $slot }}
    @stack('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    {{-- <script src="{{ asset('assets') }}/js/jquery.min.js"></script> --}}
    <script src="{{ asset('assets') }}/js/plugins/daterangepicker.js"></script>
    <script src="{{ asset('assets') }}/js/bootstrap.js"></script>
    <script src="{{ asset('assets') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('assets') }}/js/core/bootstrap.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="{{ asset('assets') }}/js/header-header.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/popper.min.js"></script>
    <script src="{{ asset('assets') }}/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('assets') }}/js/pikaday.min.js"></script>
    <script src="{{ asset('assets') }}/js/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('assets') }}/js/angular-animate.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
    <script src="{{ asset('assets') }}/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Select an option'
            });
        });
    </script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('body').on('click', '#delete-user', function() {
                var userURL = $(this).data('url');
                var trObj = $(this);
                if (confirm("Are you sure you want to remove?") == true) {
                    $.ajax({
                        url: userURL
                        , type: 'DELETE'
                        , dataType: 'json'
                        , success: function(data) {
                            alert(data.success);
                            trObj.parents("tr").remove();
                        }
                    });
                    window.location.reload()
                }
            });
        });
        var selectAllItems = "#select-all";
        var checkboxItem = ":checkbox";
        $(selectAllItems).click(function() {
            if (this.checked) {
                $(checkboxItem).each(function() {
                    this.checked = true;
                });
            } else {
                $(checkboxItem).each(function() {
                    this.checked = false;
                });
            }
        });
        $(document).ready(function() {
            $('.sub-btn').click(function() {
                $(this).next('.sub-menu').slideToggle();
                $(this).find('.dropdown').toggleClass('rotate');
            });
            $('.menu-btn').click(function() {
                $('.side-bar').addClass('active');
                $('.menu-btn').css("visibility", "hidden");
            });
            $('.close-btn').click(function() {
                $('.side-bar').removeClass('active');
                $('.menu-btn').css("visibility", "visible");
            });
        });

        function confirmAndSubmit() {
            let selectedAction = document.getElementById('action').value;
            document.getElementById('actionField').value = selectedAction;
            let confirmation = confirm('Are you sure you want to perform this action?');
            if (confirmation) {
                document.getElementById('myForm').submit();
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            var currentDate = new Date();
            var formattedDate = currentDate.getDate() + '/' + (currentDate.getMonth() + 1) + '/' + currentDate.getFullYear();
            document.getElementById('datepicker').value = formattedDate;
            var picker = new Pikaday({
                field: document.getElementById('datepicker')
                , format: 'D/M/YYYY'
                , toString(date, format) {
                    const day = date.getDate();
                    const month = date.getMonth() + 1;
                    const year = date.getFullYear();
                    return `${day}/${month}/${year}`;
                }
                , parse(dateString, format) {
                    const parts = dateString.split('/');
                    const day = parseInt(parts[0], 10);
                    const month = parseInt(parts[1], 10) - 1;
                    const year = parseInt(parts[2], 10);
                    return new Date(year, month, day);
                }
            });
        });

        //range datepicker
        document.addEventListener('DOMContentLoaded', function() {
            const initialDate1 = document.getElementById('dateRangePicker1').value;
            const initialDate2 = document.getElementById('dateRangePicker2').value;

            function parseDate(dateString) {
                const parts = dateString.split('/');
                const day = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10) - 1; // months are 0-based in JavaScript
                const year = parseInt(parts[2], 10);
                return new Date(year, month, day);
            }

            var picker1 = new Pikaday({
                field: document.getElementById('dateRangePicker1')
                , format: 'D/M/YYYY'
                , toString(date, format) {
                    const day = date.getDate();
                    const month = date.getMonth() + 1;
                    const year = date.getFullYear();
                    return `${day}/${month}/${year}`;
                }
                , parse(dateString, format) {
                    return parseDate(dateString);
                }
            });

            var picker2 = new Pikaday({
                field: document.getElementById('dateRangePicker2')
                , format: 'D/M/YYYY'
                , toString(date, format) {
                    const day = date.getDate();
                    const month = date.getMonth() + 1;
                    const year = date.getFullYear();
                    return `${day}/${month}/${year}`;
                }
                , parse(dateString, format) {
                    return parseDate(dateString);
                }
            });

            picker1.setDate(parseDate(initialDate1), true); // true to trigger onSelect
            picker2.setDate(parseDate(initialDate2), true); // true to trigger onSelect
        });

        $(document).ready(function() {
            $("input[name='dates']").daterangepicker({});
        });

    </script>
    <script src="{{ asset('assets') }}/js/angular.min.js"></script>
    {{-- <script async defer src="https://buttons.github.io/buttons.js"></script> --}}
    <script src="{{ asset('assets') }}/js/material-dashboard.min.js?v=3.0.0"></script>
    <script src="{{ asset('assets') }}/js/angular-moment.min.js"></script>
    <script src="{{ asset('assets') }}/js/angular-locale_de-de.js"></script>
    <script src="{{ asset('assets') }}/js/angular-animate.js"></script>
    <script src="{{ asset('assets') }}/js/purchase.angular.js"></script>
    <script src="{{ asset('assets') }}/js/sales.angular.js"></script>
</body>
</html>
