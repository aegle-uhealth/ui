@extends('layout')
@section('refresh','<meta http-equiv="refresh" content="30">')
@section('content')
            
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Workbench</h1> 
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <!--Notification-->
    <div class="col-lg-6 col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bell fa-fw"></i><span style="font-size:larger"> Notifications</span> 
            </div>
            <div class="panel-body">
                <div class="col-lg-12 col-md-12" style="max-height:400px; overflow-y:scroll;">
                <div class="well well-sm" >
                    <div class="row">
                        <div class="col-lg-9">
                            Workflow 2 has been finished
                        </div>
                        <div class="col-lg-3">
                            <span class="text-right">23 min ago</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-9">
                            <strong>Status</strong> : <span class="text-success">success</span>
                        </div>
                    </div>
                </div>
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-lg-9">
                            Workflow 1 has been finished
                        </div>
                        <div class="col-lg-3">
                            <span class="text-right">43 min ago</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-9">
                            <strong>Status</strong>: <span class="text-success">success</span>
                        </div>
                    </div>
                </div>
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-lg-9">
                            N. Philip sent you a message
                        </div>
                        <div class="col-lg-3">
                            <span class="text-right">2 days ago</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-9">
                            <span style="font-weight: 200">Re: </span> New Analytics Tool
                        </div>
                    </div>
                </div>
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-lg-9">
                            J. Doe updated Workflow 1
                        </div>
                        <div class="col-lg-3">
                            <span class="text-right">3 days ago</span>
                        </div>
                    </div>
                    
                </div>
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-lg-9">
                            J. Doe updated Workflow 1
                        </div>
                        <div class="col-lg-3">
                            <span class="text-right">3 days ago</span>
                        </div>
                    </div>                    
                </div>
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-lg-9">
                            J. Doe updated Workflow 1
                        </div>
                        <div class="col-lg-3">
                            <span class="text-right">3 days ago</span>
                        </div>
                    </div>                    
                </div>
                </div>
                </div>
             </div>            
        </div>

        <div class="col-lg-6 col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bell fa-fw"></i><span style="font-size:larger"> Workflows</span> 
            </div>
            <div class="panel-body">
                <div class="col-lg-12 col-md-12" style="max-height: 400px; overflow-y:scroll;">
                 @if (count($workflows_list) > 0)                         
                    @foreach($workflows_list['apps']['app'] as $app)
                        <div class="well well-sm">
                            <div class="row">
                                <div class="col-lg-8">
                                    <strong>{{$app['name']}}</strong> 
                                </div>         
                                 <div class="col-lg-4">
                                    <div class="progress">
                                      @if($app['finalStatus']=="SUCCEEDED")
                                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow={{$app['progress']}} aria-valuemin="0" aria-valuemax="100" style='width:{{$app['progress']}}%'>
                                    @elseif($app['finalStatus']=="FAILED")
                                        <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow={{$app['progress']}} aria-valuemin="0" aria-valuemax="100" style='width:{{$app['progress']}}%'>
                                    @else
                                        <div class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" aria-valuenow={{$app['progress']}} aria-valuemin="0" aria-valuemax="100" style='width:{{$app['progress']}}%'>
                                    @endif
                                          {{$app['progress']}}%
                                      </div>
                                    </div>  
                                </div>              
                            </div>
                        <div class="row">
                            <div class="col-lg-12">
                                Start: {{                                                              
                                    date('D, M d, Y H:i', substr($app['startedTime'],0,10))                          
                                }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-9">
                                Last Update: {{
                                        gmdate("D, M d, Y H:i",  substr($app['finishedTime'],0,10))
                                    }}
                            </div>                        
                        </div>
                        <div class="row">
                            <div class="col-lg-9">
                                Status: 
                                @if($app['finalStatus']=="SUCCEEDED")
                                    <span class="text-success">
                                @elseif($app['finalStatus']=="FAILED")
                                    <span class="text-danger">
                                @else
                                    <span class="text-warning">
                                @endif
                                <strong>{{$app['finalStatus']}}</strong>
                                </span>                                
                            </div>                        
                        </div>
                        </div>
                    @endforeach                            
                @endif    
                </div>
            </div>
        </div>
    </div>

    </div>
    
    
 

<div class="row">
    <!--Activity Stream-->
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                Activity Stream
            </div>
            <div class="panel-body">
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-lg-9">
                            J. Doe updated Workflow 1
                        </div>
                        <div class="col-lg-3">
                            <span class="pull-right">3 days ago</span>
                        </div>
                    </div>
                    
                </div>
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-lg-9">
                            J. Doe updated Workflow 1
                        </div>
                        <div class="col-lg-3">
                            <span class="pull-right">3 days ago</span>
                        </div>
                    </div>
                    
                </div>
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-lg-9">
                            J. Doe updated Workflow 1
                        </div>
                        <div class="col-lg-3">
                            <span class="pull-right">3 days ago</span>
                        </div>
                    </div>
                    
                </div>
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-lg-9">
                            J. Doe updated Workflow 1
                        </div>
                        <div class="col-lg-3">
                            <span class="pull-right">3 days ago</span>
                        </div>
                    </div>
                    
                </div>
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-lg-9">
                            J. Doe updated Workflow 1
                        </div>
                        <div class="col-lg-3">
                            <span class="pull-right">3 days ago</span>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        alert('Heloo');
    });
</script>
@stop
