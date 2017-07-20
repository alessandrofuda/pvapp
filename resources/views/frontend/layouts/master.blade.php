<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Modern Business - Start Bootstrap Template</title>

    <!-- Bootstrap Core CSS -->
    <link href="custom/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="custom/css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="custom/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Google font-->
    <link href='//fonts.googleapis.com/css?family=Asset' rel='stylesheet'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- navigation -->
    @include('frontend.layouts.partials._navigation') 


    <!-- Header Carousel -->
    @include('frontend.layouts.partials._header')


    
    <!-- Page Container -->
    <div class="container">

        <!-- hp content -->
        @yield('content')

        <!-- Footer -->
        @include('frontend.layouts.partials._footer')

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="custom/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="custom/js/bootstrap.min.js"></script>

    <!-- Script to Activate the Carousel -->
    <script>
    $('.carousel').carousel({
        interval: 5000 //changes the speed
    })
    </script>

</body>

</html>