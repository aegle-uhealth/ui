@extends('layout')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Analytics Toolbox</h1>
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
               <div class="col-lg-12" style="max-height: 420px; overflow-y:scroll;">
                <div class="panel-group" >
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" href="#collapse1">Tools</a>
                      </h4>
                    </div>
                      <form>
                    <div id="collapse1" class="panel-collapse collapse">
                      <ul class="list-group">
                        @if (count($path_list) > 0)
                          <?php $i=1?>
                            @foreach($path_list['FileStatuses']['FileStatus'] as $list)
                                @if (strpos($list['pathSuffix'],'xml') != false)                                                            
                                       <li class="list-group-item"><a href="#" onclick ="myJsFunc('{{$list['pathSuffix']}}')">{{substr($list['pathSuffix'], 0, -4)}}</a></li> 
                                @endif  
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
                    <span style="font-size: large">Details</span>                                 
                                                   
                    <hr>

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
                        <span id="desc"></span>

                    </p>

                    <br>
                    <p>
                        <span id="example_heading" style="font-weight:bold"></span>
                        <br>
                        <span id="example"></span>

                    </p>

                </div>
            </div>
        </div>

</div>
    
@endsection

@section('scripts')
<script type="text/javascript">

    function myJsFunc($name) {
        $.ajax({
            url: 'analytictoolbox/getFileDetail',
            type: 'GET',
            data: { filename: $name },
            success: function (data) {
                //alert(data);
                if (data){

                    //$("#file_name").html('Details');

                    $("#input_file_heading").html("Input");
                    $("#input_file").html(data.inputs);

                    $("#output_file_heading").html("Output");
                    $("#output_file").html(data.outputs);
                              
                    $("#desc_heading").html("Description");
                    $("#desc").html(data.description);

                    $("#example_heading").html("Help");
                    $("#example").html(data.help); 
                }
                else {
                    $("#file_name").html('Not Available');

                    $("#input_file_heading").html("");
                    $("#input_file").html('');

                    $("#output_file_heading").html("");
                    $("#output_file").html('');
                              
                    $("#desc_heading").html("");
                    $("#desc").html('');

                    $("#example_heading").html("");
                    $("#example").html('');
                }
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