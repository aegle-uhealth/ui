@extends('layout')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Visualise</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <!--Left-->
    <div class="col-lg-3">
        <div class="well" style="background: #fff; height: 550px">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">                        
                        <input class="form-control" placeholder="Filter">  
                    </div>
                </div>                
            </div>
            <div class="row">          
                <div class="col-lg-12">
                    <a class="btn btn-default btn-block" data-toggle="modal" href="#myModal1" >Add New Visualisation</a>
                    <a class="btn btn-default btn-block" data-toggle="modal" href="#myModal2" >Add New Dashboard</a>

                    <!-- Modal Visualise -->
                    <div id="myModal1" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Add Visulization</h4>
                            </div>
                            
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="well" style="background: white; height: 350px">
                                            <div id="visualiseTree"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                            <a class="btn btn-default btn-block" data-dismiss="modal" data-toggle="modal" href="#myModal2" >Next</a>
                            </div>

                        </div>
                      </div>
                    </div>

                    <!-- Modal Dashboard-->
                    <div id="myModal2" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Add Visulization</h4>
                            </div>
                            
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="well" style="background: white;  height: 350px">
                                            <div id="visualiseTree"></div>
                                        </div>
                                    </div>

                                    <div class="col-lg-9">
                                        <div class="well" style="background: white;  height: 350px">
                                            <div id="visualiseTree"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-default" data-dismiss="modal">Add</button>
                            </div>

                        </div>
                      </div>
                    </div>

                    <hr> 
                </div>                                        
            </div>

            <div class="row">
                <div class="col-lg-12"> 
                    <div id="tree"></div>
                </div>
            </div>

        </div>
    </div>

    <!--Right-->
    <div class="col-md-9">
        <div class="well" style="background: #fff; height: 550px" >
            <div class="row">
                <div class="col-lg-12">
                    <span style="font-size: large">Details</span>                                 
                     <p class="pull-right">                         
                        <button class="btn btn-default" style="width: 100px;">Edit</button>
                        <button class="btn btn-default" style="width: 100px;">Share</button>
                        <button class="btn btn-default" style="width: 100px;">Remove</button>
                         
                     </p>                                    
                    <hr>
                </div>
            </div>
        </div>

</div>
    
@endsection


@section('scripts')
<script type="text/javascript">

    var tree = [
                {
                    text: "Visualisations",
                    nodes: [
                    {
                        text: "Workflow 1"

                    },
                    {
                        text: "Dashboard 1"
                    }
                  ]
                }
              ];

    // Example: initializing the treeview
    // expanded to 5 levels
    // with a background color of green
    $('#tree').treeview({
        data: tree,         // data is not optional
        levels: 1
    });

    $('#visualiseTree').treeview({
        data: tree,         // data is not optional
        levels: 1
    });


</script>
@stop