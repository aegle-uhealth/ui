@extends('layout')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Analysis Results</h1>
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
                    <span id="file_name" style="font-size: large">Details</span>                                 
                     <p class="pull-right">                         
                        <button class="btn btn-default" style="width: 100px;">Share</button>
                        <button class="btn btn-default" style="width: 100px;">Remove</button>
                         
                     </p>                                    
                    <hr>
                    
                    <p>
                        <span id="file_type_heading" style="font-weight:bold"></span>
                        <br>
                        <span id="file_type"></span>
                    </p>

                    <br>
                    <p>
                        <span id="desc_heading" style="font-weight:bold"></span>
                        <br>
                        <span id="desc"></span>
                    </p>

                    <br>
                    <p>
                        <span id="headers_heading" style="font-weight:bold"></span>
                        <br>
                        <span id="headers"></span>
                    </p>

                    <br> 

                </div>
            </div>
        </div>

</div>
    
@endsection


@section('scripts')
<script type="text/javascript">
    
    $(window).load(function() {         
      $.ajax({
            url: 'analysisresult/buildTree',
            type: 'GET',
            success: function (jdata) {
                //alert(jdata);  
                if(jdata) {
                    $('#tree').treeview({
                       enableLinks: false,
                       data: jdata,         // data is not optional
                       levels:1,
                       onNodeSelected: function(event, ndata) {
                                        // Your logic goes here

                                        if(ndata.href!=undefined)
                                        {
                                            //alert(ndata.href);
                                            //alert(ndata.text);
                                            $filename = ndata.text;
                                            $path = ndata.href;
                                            $.ajax({
                                                url: 'analysisresult/getFileDetail',
                                                type: 'GET',
                                                data: { path: $path },
                                                success: function (data) {            
                                                    if (data){
                                                            $("#file_name").html($filename);

                                                            $("#desc_heading").html("Description");
                                                            $("#desc").html(data.description);

                                                            $("#file_type_heading").html("Data Type");
                                                            $("#file_type").html("CSV");

                                                            $("#headers_heading").html("Headers");
                                                            $("#headers").html(data.columns); 
                                                    }
                                                    else{
                                                        //alert('no data');
                                                            $("#file_name").html("Not Available");

                                                            $("#file_type_heading").html("Data Type");
                                                            $("#file_type").html("CSV");

                                                            $("#desc_heading").html("");
                                                            $("#desc").html("");

                                                            $("#headers_heading").html("");
                                                            $("#headers").html(""); 
                                                    }
                                                },
                                                async: true,
                                                error: function (xhr, ajaxOptions, thrownError) {
                                                    //alert('An error occurred');
                                                    alert(thrownError);
                                                }
                                            });     
                                        }  
                                      }
                    })                                       
                }
            },
            async: true,
            error: function (xhr, ajaxOptions, thrownError) {
                //alert('An error occurred');
                alert(thrownError);
            }
        });    

    });

    function getFileDetail($case, $name) {
        alert($path);
        $.ajax({
            url: 'analysisresult/getFileDetail',
            type: 'GET',
            data: { case: $case, filename: $name },
            success: function (data) {                
                if (data){
                    alert(data);
                }
                else{
                    alert('no data');
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