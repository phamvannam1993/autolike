
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin -  Trang Chủ  </title>
    
    <link href="https://www.autofarmer.xyz/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://www.autofarmer.xyz/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="https://www.autofarmer.xyz/css/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="https://www.autofarmer.xyz/css/green.css" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="https://www.autofarmer.xyz/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="https://www.autofarmer.xyz/css/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="https://www.autofarmer.xyz/css/daterangepicker.css" rel="stylesheet">

    <!-- Toastr -->
    <link href="https://www.autofarmer.xyz/css/toastr.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="https://www.autofarmer.xyz/css/custom.min.css" rel="stylesheet">

    <!-- Custom Personal Style -->
    <link href="https://www.autofarmer.xyz/css/main.css" rel="stylesheet">

    <!-- Date picker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-132961921-1"></script>

    </head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <!-- top navigation -->
 
        <!-- /top navigation -->



        <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Thông tin Exception</h2>

                        <div class="clearfix"></div>
                    </div>
                    <a href="https://api.autofarmer.xyz/api/exception/delete" class="btn btn-danger">Delete</a>
                    <div class="container" id="exception">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="getAtionUrl" value="https://www.autofarmer.xyz/api/exception">
        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

<script src="https://www.autofarmer.xyz/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="https://www.autofarmer.xyz/js/bootstrap.min.js"></script>
<script src="https://www.autofarmer.xyz/js/custom.min.js"></script>
<script type="text/javascript">
    viewData(1);
    function viewData(page) {
        $.ajax({
            type: "GET",
            url: $('#getAtionUrl').val(),
            data: {
                page:page
            },
            success: function (response) {
                $('#exception').html(response);
                $('#exception').find('a').attr('href', 'javascript:void(0)');
            },
            error: function (response) {

            }
        });
    }

    $('body').on('click', '.page-link', function() {
        var page = $(this).text();
        viewData(page);
    });
</script>

</body>
</html>
