<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--meta name="viewport" content="width=device-width, initial-scale=1"-->
     <meta content='maximum-scale=1.0, initial-scale=1.0, width=device-width' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="">
    <meta name="author" content="">
    @yield('refresh')

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
                        
                        <li>
                            <a href="{{ url('workbench') }}" style="text-align:center">
                            <span style="font-size:40px"><i class="fa fa-home fa-fw"></i></span>
                            <br />
                            <label>
                                Workbench
                              </label>
                            </a>
                        </li>
                        
                        <li>
                            <a href="{{ url('datasets') }}" style="text-align:center">
                           <span style="font-size:40px"><i class="fa fa-table fa-fw"></i></span>
                           <br />
                            <label>
                                Datasets
                              </label>
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('workflows') }}" style="text-align:center">
                           <span style="font-size:50px"><i class="fa fa-code-fork fa-fw"></i></span>
                           <br />
                            <label>
                                Workflows
                              </label>
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('analysisresult') }}" style="text-align:center">
                           <span style="font-size:50px"><i class="fa fa-th-list fa-fw"></i></span>
                           <br />
                            <label>
                                Analysis
                              </label>
                            <br />
                            <label>
                               Results
                              </label>
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('visualise') }}" style="text-align:center">
                           <span style="font-size:50px"><i class="fa fa-eye fa-fw"></i></span>
                           <br />
                            <label>
                                Visualise
                              </label>
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('analytictoolbox') }}" style="text-align:center">
                           <span style="font-size:50px"><i class="fa fa-briefcase fa-fw"></i></span>
                           <br />
                            <label>
                                Analytics
                              </label>
                            <br />
                            <label>
                                Toolbox
                              </label>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            @yield('content')
            <!-- /.row -->
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

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });

</script>
      @yield('scripts')

</body>

</html>
