@extends('layout')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Datasets - <small>{{$case_type}}</small></h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <!--Left-->
    <div class="col-lg-3">
        <div class="panel panel-default" style="background: #fff; height: 570px;">   
            <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">                        
                        <input class="form-control" placeholder="Filter">  
                    </div>
                </div>                
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn btn-default btn-block" data-toggle="modal" href="#myModal" >Add Dataset</a>
                </div>
            </div>
            <hr>
            <div class="row">
               <div class="col-lg-12" style="max-height: 420px; overflow-y:scroll;">
                <div class="panel-group" >
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" href="#collapse1">Datasets</a>
                      </h4>
                    </div>
                      <form>
                    <div id="collapse1" class="panel-collapse collapse">
                      <ul class="list-group">
                        @if (count($path_list) > 0)
                            @foreach($path_list['FileStatuses']['FileStatus'] as $list)
                                @if (strpos($list['pathSuffix'],'csv') != false)                                                            
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
    </div>

    <!--Right-->
    <div class="col-md-9">
        <div class="well" style="background: #fff; height: 570px" >
            <div class="row">
                <div class="col-lg-12"  id="detail-desc">
                    <span id="file_name" style ="font-size: large">Details</span>                                 
                     <p class="pull-right">
                        <button class="btn btn-default" style="width: 100px;">Edit</button>
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
                        <span id="date_upload_heading" style="font-weight:bold"></span>
                        <br>
                        <span id="date_upload"></span>

                    </p>
                    <br>
                    <p>
                        <span id="upload_by_heading" style="font-weight:bold"></span>
                        <br>
                        <span id="upload_by"></span>

                    </p>
                    <br>
                    <p>
                        <span id="sharing_heading" style="font-weight:bold"></span>
                        <br>
                        <span id="sharing"></span>

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


    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Dataset</h4>
            </div>
                            
            <div class="modal-body">
                <form role="form" method="post" action="" id="dataset">
                    <div class="form-group">
                        <label for="txtSource">Source:</label>
                        <input type="text" class="form-control" id="txtSource">
                    </div>
                    <div class="form-group">
                        <label for="dest">Destination:</label>
                        <textarea class="form-control" rows="5" id="dest"></textarea>
                    </div>
                        <div class="form-group">
                        <label for="desc">Description:</label>
                        <textarea class="form-control" rows="5" id="desc"></textarea>
                    </div>
                        <div class="form-group">
                        <label for="lics">License:</label>
                        <textarea class="form-control" rows="2" id="lics"></textarea>
                    </div>
                </form>
            </div>
                            
            <div class="modal-footer">
            <button type="submit" class="btn btn-default" data-dismiss="modal">Add</button>
            </div>

        </div>
            </div>
</div>

    </div>
    
@endsection


@section('scripts')
<script type="text/javascript">

    $(document).ready(function () {
        //alert('Heloo');
    });

    function myJsFunc($name) {
        $.ajax({
            url: 'dataset/getFileDetail',
            type: 'GET',
            data: { filename: $name },
            success: function (data) {
                //alert(data);
                if (data){

                    $("#file_name").html($name);

                    $("#desc_heading").html("Description");
                    $("#desc").html(data.description);

                    $("#date_upload_heading").html("Timestamp");
                    $("#date_upload").html(data.timestamp);

                    $("#upload_by_heading").html("Uploaded By");
                    $("#upload_by").html(data.creator);

                    $("#sharing_heading").html("Sharing");
                    $("#sharing").html(data.sharing);

                    $("#headers_heading").html("Headers");
                    $("#headers").html(data.columns); 
                }
                else {
                    $("#file_name").html('Not Available');

                    $("#desc_heading").html("");
                    $("#desc").html("");

                    $("#date_upload_heading").html("");
                    $("#date_upload").html("");

                    $("#upload_by_heading").html("");
                    $("#upload_by").html("");

                    $("#sharing_heading").html("");
                    $("#sharing").html("");

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

</script>
@stop