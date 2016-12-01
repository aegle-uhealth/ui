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
                        <!-- Test -->
                    </div>
                </div>                
            </div>
            <div class="row">          
                <div class="col-lg-12">
                    <a class="btn btn-default btn-block" data-toggle="modal" href="#visualiseModal" >Add New Visualisation</a>
                    <a class="btn btn-default btn-block" data-toggle="modal" href="#myModal2" >Add New Dashboard</a>

                    <!-- Modal Visualise -->
                    <div id="visualiseModal" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Add Visualization</h4>
                            </div>
                            
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="well" style="background: white; height: 500px; overflow-y: scroll;">
                                            <div id="visualiseTree"></div>
                                        </div>
                                        
                                        <input type="hidden"  class="form-control"  name="datasetName" id="datasetName">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                            <a id="visualModalNext" name="visualModalNext" onclick="javascript:OpenInNewTab();" class="btn btn-default btn-block" data-toggle="modal">Next</a>
                            <input type="hidden" name="onNext" id="onNext" value="">
                            <input type="hidden" name="filePath" id="filePath" value="">
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
                                            <div id="dashboardTree"></div>
                                        </div>
                                    </div>

                                    <div class="col-lg-9">
                                        <div class="well" style="background: white;  height: 350px">
                                            <div id="dashboardTree"></div>
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
    
    $(window).load(function() {         
      $.ajax({
            url: 'visualise/buildAddVisualTree',
            type: 'GET',
            success: function (jdata) {
                //alert(jdata);                  
                if(jdata) {
                    $('#visualiseTree').treeview({
                        data: jdata,         // data is not optional
                        levels: 1,
                        enableLinks: false,
                        showBorder: true,
                        onNodeSelected: function(event, ndata) {
                                        // Your logic goes here
                                        //alert(ndata.href);
                                        $path = ndata.href;
                                        $('#datasetName').val(ndata.text);
                                        $('#onNext').attr("value", ndata.text);
                                        $('#filePath').attr("value",ndata.href);
                                      }
                    }); 
                }                            
            },
            async: true,
            error: function (xhr, ajaxOptions, thrownError) {
                //alert('An error occurred');
                alert(thrownError);
            }
        })

        //$('#visualiseTree').on('nodeSelected', function(event, data) {        
        //    alert(data);
        //});


    });

    function OpenInNewTab() {
        $filename = $('#onNext').val();
        $filepath = $('#filePath').val();
        $.ajax({
            url: 'visualise/getPath',
            type: 'GET',
            data: { filename: $filename , filepath: $filepath},
            success: function (data) {
                //alert(data);
                //http://83.212.98.38:3838/plotly_aegle_ICU/?test=/ICU/bennetPre.csv
                //url = "http://83.212.98.38:3838/plotly_aegle_ICU/?test=" + data + $url +".csv"
                //url = "http://83.212.98.38:3838/plotly_aegle_ICU/?test=/ICU/bennetPre.csv"
                var win = window.open(data, '_blank');
                win.focus();
            },
            async: true,
            error: function (xhr, ajaxOptions, thrownError) {
                //alert('An error occurred');
                alert(thrownError);
            }
        });
    }

</script>
@stop