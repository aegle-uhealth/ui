@extends('layout')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Workflows - <small>{{$case_type}}</small></h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row" id="row">
    <!--Left-->
    <div class="col-lg-3">
        <div class="well" id="well" style="background: #fff; height: 550px">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">                        
                        <input class="form-control" placeholder="Filter">  
                    </div>
                </div>                
            </div>
            <div class="row">          
                <div class="col-lg-12">
                    <a class="btn btn-default btn-block" data-toggle="modal" href="#myModal" >Add Workflow</a>

                    <!-- Add Workflow Modal -->
                    <div id="myModal" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Add New Workflow</h4>
                            </div>
                            
                            <div class="modal-body">                               
                                <form role="form" method="post" action="javascript:addWorkflow();" id="workflow">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                      <label for="name">Name</label>
                                      <input type="text"  class="form-control"  name="name" id="name">
                                    </div>
                                    <div class="form-group">
                                      <label for="dest">Destination</label>
                                      <textarea readonly class="form-control" rows="2" id="dest" style="font-weight: bold; resize: none">\Workflows</textarea>
                                    </div>
                                     <div class="form-group">
                                      <label for="desc">Description</label>
                                      <textarea class="form-control" rows="4" name="desc" id="desc" style="resize: vertical"></textarea>
                                    </div>
                                     <div class="form-group">
                                      <label for="lics">License</label>
                                      <textarea class="form-control" rows="1" name="lics" id="lics" style="resize: vertical"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-default">Add</button>
                                </form>
                            </div>
                            
                           

                        </div>
                      </div>
                    </div>
                    <hr> 
                </div>                                        
            </div>

            <div class="row">
               <div class="col-lg-12" style="max-height: 420px; overflow-y:scroll;">
                <div class="panel-group" >
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" href="#collapse1">Workflows</a>
                      </h4>
                    </div>
                      <form id="workflowlist">
                        <div id="collapse1" class="panel-collapse collapse">
                          <ul class="list-group">
                            @if (count($path_list) > 0)                         
                                @foreach($path_list['FileStatuses']['FileStatus'] as $list)
                                    <li class="list-group-item"><a href="#" onclick ="getWorkflowDetail('{{$list['pathSuffix']}}')">{{substr($list['pathSuffix'], 0, strlen($list['pathSuffix']))}}</a></li> 
                                @endforeach                            
                             @endif                       
                          </ul>                      
                        </div>
                      </form>
                  </div>
                </div> 
                </div>
            </div>

        </div>
    </div>

    <!--Right-->
    <div class="col-md-9">
        <div class="well" style="background: #fff; height: 550px" >
            <div class="row">
                <div class="col-lg-12">
                    <span id="workflow_heading" style="font-size: large">Details</span>                                 
                     <p id="btn-panel" class="pull-right" style="display: none">                         
                        <button id="run" class="btn btn-default" style="width: 100px;" onclick="runScript()">Run</button>
                        <button id="edit" class="btn btn-default" style="width: 100px;" onclick="callMode()">Edit</button>
                        <button id="share" class="btn btn-default" style="width: 100px;">Share</button>
                        <button id="remove" class="btn btn-default" style="width: 100px;">Remove</button>                         
                     </p>                                    
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                  <span id="addNodebutton" style="display: none;">
                      <button class='btn btn-default' data-toggle='modal' data-target='#nodeModel' style='width: 100px;'>Add Node</button>
                  </span>
                    <br>
                    <br>
                  <ul class="list-group" id="workflowDetail">
                  </ul>
                </div>
            </div>
        </div>

        <!-- Add Node Modal -->
        <div id="nodeModel" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Node</h4>
                </div>
                            
                <div class="modal-body">                               
                    <form role="form" method="post" action="javascript:addAnalytics();" id="node_workflow">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="workflow-name">Workflow</label>
                            <input type="text" class="form-control" name="workflow_name" id="workflow_name" readonly>
                         </div>  
                        <div class="form-group">
                            <label for="name">Node</label>
                            <div class="panel-group" >
                                <div class="col-lg-12" style="max-height: 420px; overflow-y:scroll;">
                        <div class="panel-group" >
                          <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapse2">Tools</a>
                              </h4>
                            </div>
                    
                            <div id="collapse2" class="panel-collapse collapse">
                              <ul class="list-group">
                                @if (count($path_list) > 0)
                                  <?php $i=1?>
                                    @foreach($tool_list['FileStatuses']['FileStatus'] as $list)
                                         @if (strpos($list['pathSuffix'],'xml') != false)      
                                        <li class="list-group-item"><a href="#" onclick ="getToolsDetail('{{$list['pathSuffix']}}')">{{substr($list['pathSuffix'], 0, -4)}}</a></li> 
                                        @endif
                                    @endforeach                            
                                 @endif                       
                              </ul>                      
                            </div>
                    
                              </div>
                            </div> 
                            </div>
                            </div>         
                        </div>   
                         <div class="form-group">
                            <label for="tool-name">Analytics</label>
                            <input type="text" class="form-control" name="analytics_name" id="analytics_name" readonly>
                         </div>                   
                        <div class="form-group">
                            <label for="desc">Description</label>
                            <div class="well" style="height: 250px; overflow-y: scroll">
                            
                            <p>
                                <span id="analytics_name_heading" style="font-weight: bold; font-size: 15px"></span>
                            </p>
                            <br> 
                            <p>
                                <span id="input_file_heading" style="font-weight:bold"></span>
                                <br>
                                <span id="input_file"></span>

                            </p>
                            <br>
                            <p>
                                <span id="output_file_heading" style="font-weight:bold"></span>
                                <br>
                                <span id="output_file"></span>

                            </p>
                            <br>
                            <p>
                                <span id="desc_heading" style="font-weight:bold"></span>
                                <br>
                                <span id="descpt"></span>
                            </p>
                            <br>
                            <p>
                                <span id="help_heading" style="font-weight:bold"></span>
                                <br>
                                <span id="help"></span>

                            </p>
                            </div>
                        </div>                            
                        <hr>    
                        <button type="submit" class="btn btn-default">Add</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Run Time Modal-->
        <div id="dynamicModel" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Node</h4>
                </div>
                            
                <div class="modal-body">                    
                    <form role="form" method="post" action="javascript:processAnalytic();" id="node_workflow">
                        <div class="something" id="generic_form" style="display:none;">                        
                        </div>
                        <button type="submit" class="btn btn-default">Update</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        ------
    </div>
</div>
    
@endsection


@section('scripts')
<script type="text/javascript">

    function getWorkflowDetail($name) {
        $("#workflow_heading").html($name);
        $("#workflow_name").val($name);
        $("#btn-panel").show();
        normalMode();
        $.ajax({
            url: 'workflow/getWorkflowDetail',
            type: 'GET',
            data: { filename: $name },
            success: function (data) {
                $("#workflowDetail").empty();
                $.each(data['FileStatuses'], function (key, item) {
                    $.each(item, function (index, data1) {
                        //console.log('index', data1);
                        $toollist = data1.pathSuffix;
                        $("#workflowDetail").append("<li class='list-group-item'><a href='#' id='"+$toollist.substring(0, $toollist.length-4)+"' class='push'>" + $toollist.substring(0, $toollist.length-4) + "</a></li>");
                    })
                })
            },
            async: true,
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError);
            }
        });

    }

    function callMode() {
        $val = $("#edit").text();
        if ($val == 'Edit')
            editMode();
        else
            normalMode();

    }

    function editMode() {
        $("#addNodebutton").show();
        $("#run").hide();
        $("#edit").html("Save");
    }

    function normalMode() {
        $("#addNodebutton").hide();
        $("#run").show();
        $("#edit").html("Edit");
    }

    function getToolsDetail($name) {
        $.ajax({
            url: 'analytictoolbox/getFileDetail',
            data: { filename: $name },
            success: function (data) {

                $("#analytics_name_heading").html($name);
                $("#analytics_name").val($name);

                $("#input_file_heading").html("Input");
                $("#input_file").html(data.inputs);

                $("#output_file_heading").html("Output");
                $("#output_file").html(data.outputs);

                $("#desc_heading").html("Description");
                $("#descpt").html(data.description);

                $("#help_heading").html("Help");
                $("#help").html(data.help);

            },
            async: true,
            error: function (xhr, ajaxOptions, thrownError) {
                //alert('An error occurred');
                alert(thrownError);
            }
        });
    }

    function runScript() {
        $workflow = $('#workflow_heading').text();        
        $.ajax({
            url: 'runScript',
            data: { workflow: $workflow },
            type: 'POST',
            success: function (data) {
                alert(data);
            },
            async: true,
            error: function (xhr, ajaxOptions, thrownError) {
                //alert('An error occurred');
                alert(thrownError);
            }
        });
    }

    function addAnalytics() {
        $analytics_name = $('#analytics_name').val();
        $workflow_name = $('#workflow_name').val();
        var that = this;
        $.ajax({
            url: 'addTool',
            data: { analytics_name: $analytics_name , workflow_name : $workflow_name  },
            type: 'POST',
            success: function (data) {
                $('#nodeModel').modal('hide')
                //alert("success");

                //Get Detail ///

                $.ajax({
                    url: 'workflow/getWorkflowDetail',
                    type: 'GET',
                    data: { filename: $workflow_name },
                    success: function (data) {
                        $("#workflowDetail").empty();
                        $.each(data['FileStatuses'], function (key, item) {
                            $.each(item, function (index, data1) {
                                //console.log('index', data1);
                                $toollist = data1.pathSuffix;
                                $("#workflowDetail").append("<li class='list-group-item'>" + $toollist.substring(0, $toollist.length-4) + "</li>");
                            })
                        })
                    },
                    async: true,
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError);
                    }
                });
            },
            async: true,
            error: function (data) {
                
                var errors = data.responseJSON['errors'];
            
                alert(errors);
            }
        });
    }

    function addWorkflow() {
        $name = $('#name').val();
        $desc = $('#desc').val();
        $lics = $('#lics').val();
        $.ajax({
            url: 'addWorkflow',
            type: 'POST',
            data: { name: $name , desc : $desc, lics : $lics },
            success: function (data) {
                location.reload();
                //alert(data);
            },
            async: true,
            error: function (data) {
                
                var errors = data.responseJSON['errors'];
            
                alert(errors);
            }
        });
    }

    function ctrlVisbile(sel){
        var clsname = sel.id.substring(0, sel.id.indexOf("_"));
        if(sel.value=="y"){
            $('.'+clsname).show();
            $('#'+clsname).filter(':input').each(function(x){
              x.setAttribute("required","required");
            })
        }    
        else{
            $('.'+clsname).hide();
        }

        //alert(clsname);
    }

    $(document).on('click', '.push', function () {            
            $filename=$(this).attr('id');
            $workflow = $('#workflow_heading').text();     
            
            $.ajax({
                url: 'workflow/buildModelForm',
                type: 'POST',
                data: { filename: $filename, workflow: $workflow},
                success: function (data) {
                        //alert(data);

                        $('#dynamicModel').modal({
                            show:true
                        })

                        $("#dynamicModel .modal-title").html($filename+' - INPUTS');            
                        $('.something').show().html(data);
                    },
                async: true,
                error: function (data) {                    
                    var errors = data.responseJSON['errors'];                
                    alert(errors);
                }
            });
            
        });
    
    function processAnalytic() {
        
        $filename= $("#dynamicModel .modal-title").text();
        $workflow = $('#workflow_heading').text();

         //Get the element with id="generic_form" (a div), then get all elements inside div with class="form-group"
         var analyticArr  = [];
         var x = document.getElementById("generic_form").querySelectorAll(".form-group");  
         var sArray="";

         for (var i = 0; i < x.length; i++) {
            var id;
            var value;
            var nodeList = x[i].querySelectorAll(".form-control");
            
            for (var index = 0; index < nodeList.length; index++) {
                id = nodeList[index].id;
                value = nodeList[index].value;

                //alert(id);
                //alert(value);

                if (nodeList[index].onchange != null){
                    
                    //alert("nodeList[index].onchange");
                    id = id.replace("_select", "");
                    var spans = document.getElementsByClassName(id);
                    
                    //alert("span length :" + spans.length);
                    
                    for (var iSpan = 0; iSpan < spans.length; iSpan++) {

                        var innerNodeList = spans[iSpan].querySelectorAll(".form-control");
                        
                        //alert("div :" + innerNodeList.length);

                        for (var innerIndex = 0; innerIndex < innerNodeList.length; innerIndex++) {
                            
                            //id = id + "." + innerNodeList[index].id;
                            value = innerNodeList[index].value;
                            
                            if(nodeList[index].value == "n"){
                                value = "null";
                            }
                            // If internal skip next
                            i = i + 1;

                            sArray = sArray + String(id + "." + innerNodeList[index].id) + " " + String(value) + " ";
                            var tempArr = [String(id + "." + innerNodeList[index].id) ,String(value)];
                            analyticArr.push(tempArr);
                        }
                    }
                }else{
                    sArray = sArray + String(id) + " " + String(value) + " ";
                    var tempArr = [String(id) ,String(value)];
                    analyticArr.push(tempArr);
                }
            }
            //alert(id);
            //alert(value);
         }
         
        var analytic = JSON.stringify(analyticArr);

        //alert(analytic);
        //alert($filename);
        //alert($workflow);

        $.ajax({
            url: 'workflow/processAnalytic',
            type: 'POST',
            data: { workflow: $workflow, filename: $filename.replace(" - INPUTS",""), analytic: analytic},
            success: function (data) {
                //location.reload();
                alert(data);
            },
            async: true,
            error: function (data) {
                
                var errors = data.responseJSON['errors'];
            
                alert(errors);
            }
        });
    }

</script>
@stop