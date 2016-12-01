<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--meta name="viewport" content="width=device-width, initial-scale=1"-->
     <meta content='maximum-scale=1.0, initial-scale=1.0, width=device-width' name='viewport'>
    <meta name="description" content="">
    <meta name="author" content="">

    <title>AEGLE</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Treeview CSS -->
    <link href="{{asset('assets/bower_components/bootstrap-treeview/dist/bootstrap-treeview.min.css')}}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{asset('assets/bower_components/metisMenu/dist/metisMenu.min.css')}}" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="{{asset('assets/dist/css/timeline.css')}}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{asset('assets/dist/css/sb-admin-2.css')}}" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="{{asset('assets/bower_components/morrisjs/morris.css')}}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{asset('assets/bower_components/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header" >
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="keycloak/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <a  style="text-align:center">
                                
                                <img src="{{asset('assets/images/aegle.png')}}" >

                            </a>
                            <!-- /input-group -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
          <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Select Case</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form">                                                       
                            @if ($user_role == 'cll_user')                                
                                <?php $_SESSION["case_select"]="CLL"; ?> 
                                <br>
                                <a href="{{ url('/workbench') }}" class="btn btn-lg btn-success btn-block">CLL</a>
                                <br>
                                <a href="#" class="btn btn-lg btn-primary disabled btn-block">ICU</a>
                                <br>
                                <a href="#" class="btn btn-lg btn-primary disabled btn-block">Diabetes</a>
                                <br>  
                            @elseif ($user_role == 'icu_user')   
                                <?php $_SESSION["case_select"]="ICU"; ?>                              
                                <br>
                                <a href="#" class="btn btn-lg btn-primary disabled btn-block">CLL</a>
                                <br>
                                <a href="{{ url('/workbench') }}" class="btn btn-lg btn-success  btn-block">ICU</a>
                                <br>
                                <a href="#" class="btn btn-lg btn-primary disabled btn-block">Diabetes</a>
                                <br>  
                             @elseif ($user_role == 'diabetes_user')    
                                <?php $_SESSION["case_select"]="visualization"; ?>                                
                                <br>
                                <a href="#" class="btn btn-lg btn-primary disabledbtn-block">CLL</a>
                                <br>
                                <a href="#" class="btn btn-lg btn-primary disabled btn-block">ICU</a>
                                <br>
                                <a href="{{ url('/workbench') }}" class="btn btn-lg btn-success  btn-block">Diabetes</a>
                                <br>    
                             @elseif ($user_role == 'fullaccess_user')                                
                                <br>
                                <a href="#" class="btn btn-lg btn-success btn-block">CLL</a>
                                <br>
                                <a href="#" class="btn btn-lg btn-success btn-block">ICU</a>
                                <br>
                                <a href="#" class="btn btn-lg btn-success btn-block">Diabetes</a>
                                <br>                 
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        </div>
        <!-- /#page-wrapper -->

        

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="{{asset('assets/bower_components/jquery/dist/jquery.min.js')}}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{asset('assets/bower_components/metisMenu/dist/metisMenu.min.js')}}"></script>

    <!-- Morris Charts JavaScript -->
    <script src="{{asset('assets/bower_components/raphael/raphael-min.js')}}"></script>
    <script src="{{asset('assets/bower_components/morrisjs/morris.min.js')}}"></script>
    <script src="{{asset('assets/js/morris-data.js')}}"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{asset('assets/dist/js/sb-admin-2.js')}}"></script>

    <!-- Treeview JavaScript -->
    <script src="{{asset('assets/bower_components/bootstrap-treeview/src/js/bootstrap-treeview.js')}}"></script>

      @yield('scripts')

</body>

</html>
