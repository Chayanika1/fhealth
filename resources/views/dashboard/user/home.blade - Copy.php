<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register</title>

    
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dropzone.css') }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/dropzone.js') }}"></script>

    <style>
    	.dropzoneDragArea {
		    background-color: #fbfdff;
		    border: 1px dashed #c0ccda;
		    border-radius: 6px;
		    padding: 60px;
		    text-align: center;
		    margin-bottom: 15px;
		    cursor: pointer;
		}
		.dropzone{
			box-shadow: 0px 2px 20px 0px #f2f2f2;
			border-radius: 10px;
		}
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Dashboard</h4>
                @if(Session::has('success'))
                    <div class="alert alert-success">{{Session::get('success')}}</div>
                @endif
                <p>{{Auth::guard('web')->user()->name}}</p>
                <p>
                    <a href="{{route('user.logout')}}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{route('user.logout')}}" method="POST">
                        @csrf
                    </form>
                </p>

                Create Your Post Here

                    <form action="{{ route('user.upload_post') }}" name="demoform" id="demoform" method="POST" class="dropzone" enctype="multipart/form-data">
						
						@csrf 
						<div class="form-group">
							
							<input type="hidden" class="postid" name="postid" id="postid" value="">
                            <input type="hidden" id="uid" name="uid" value="{{Auth::guard('web')->user()->id}}">

                            <select name="postgroup" id="postgroup">
                                <option value="">Select Group</option>
                                @foreach($groupList as $group)
                                <option value="{{$group->id}}">{{$group->name}}</option>
                                @endforeach
                            </select>
							<span class="text-danger">@error('postgroup'){{$message}}@enderror</span>

							<textarea name="postcontent" id="postcontent" cols="60" rows="10"></textarea>
							<span class="text-danger">@error('postcontent'){{$message}}@enderror</span>
						</div>
						
						<div class="form-group">
                  			<div id="dropzoneDragArea" class="dz-default dz-message dropzoneDragArea">
                  				<span>Upload file</span>
                  			</div>
                  			<div class="dropzone-previews"></div>
                  		</div>
                  		<div class="form-group">
	        				<button type="submit" class="btn btn-md btn-primary">create</button>
	        			</div>
					</form>
                
                <hr>

                <!-- @foreach($feedResult as $data)   
                <div class="card" style="margin-bottom:10px" >
                    <div class="card-body">
                        <h4>{{$data->name}}</h4>
                        <p class="card-text">{{$data->content}}</p>
                    </div>
                    <?php //print_r($data->post_images); ?>
                    @foreach($data->post_images as $img)
                        
                        <img class="card-img-top" src="{{ asset('uploads/images/'.$img['image'])}}" alt="Card image cap" style="width:100px">
                    @endforeach
                    
                </div>
                @endforeach -->

				<div class="container mt-5" style="max-width: 550px">
					<div id="data-wrapper">
						<!-- Results -->
					</div>
					<div class="loadmore" style="display: none;">
						<div id="loadmore-btn"  class="row"></div>
					</div>
					<!-- Data Loader -->
					<div class="auto-load text-center">
						<svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
							x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
							<path fill="#000"
								d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
								<animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
									from="0 50 50" to="360 50 50" repeatCount="indefinite" />
							</path>
						</svg>
					</div>
				</div>
                
            </div>
        </div>
    </div>

<script>

var ENDPOINT = "{{ url('/') }}";
var page = 1;
//infinteLoadMore(page);
load_more(page);
$(window).scroll(function () {
	if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
		page++;
		//infinteLoadMore(page);
		//load_more(page);
		$('.loadmore').show();
		if($('.loadmore').is(':visible')){
			$("#loadmore-btn").click();
		}
	}
});

$(function(){
	$("#loadmore-btn").on('click', function(){
		load_more(page);
	});
});
//function infinteLoadMore(page) {
function load_more(page){
	$.ajax({
			url: ENDPOINT + "/user/postcontents?page=" + page,
			datatype: "html",
			type: "get",
			beforeSend: function () {
				$('.auto-load').show();
			}
		})
		.done(function (response) {
			if (response.length == 0) {
				$('.auto-load').html("We don't have more data to display :(");
				$('.loadmore').hide();
				return false;
			}
			$('.auto-load').hide();
			$("#data-wrapper").append(response);
		})
		.fail(function (jqXHR, ajaxOptions, thrownError) {
			console.log('Server error occured');
		});
}

console.log($('.dropzone-previews').html());  	
Dropzone.autoDiscover = false;
// Dropzone.options.demoform = false;	
let token = $('meta[name="csrf-token"]').attr('content');
$(function() {
var myDropzone = new Dropzone("div#dropzoneDragArea", { 
	paramName: "file",
	url: "{{ route('user.storeimage') }}",
	previewsContainer: 'div.dropzone-previews',
	addRemoveLinks: true,
	autoProcessQueue: false,
	uploadMultiple: false,
	parallelUploads: 5,
	maxFiles: 5,
	params: {
        _token: token
    },
	 // The setting up of the dropzone
	init: function() {
	    var myDropzone = this;
	    //form submission code goes here
	    $("form[name='demoform']").submit(function(event) {
	    	//Make sure that the form isn't actully being sent.
	    	event.preventDefault();
	    	URL = $("#demoform").attr('action');
	    	formData = $('#demoform').serialize();
	    	$.ajax({
	    		type: 'POST',
	    		url: URL,
	    		data: formData,
	    		success: function(result){
	    			if(result.status == "success"){
						console.log($('.dropzone-previews').html());  	
						if($('.dropzone-previews').html() == ''){
							//reset the form
							$('#demoform')[0].reset();
							//reset dropzone
							$('.dropzone-previews').empty();
							location.reload();
						}else{
							// fetch the postid 
							var postid = result.post_id;
							$("#postid").val(postid); // inseting postid into hidden input field
							//process the queue
							myDropzone.processQueue();
						}
	    			}else{
	    				console.log("error");
	    			}
	    		}
	    	});
	    });
	    //Gets triggered when we submit the image.
	    this.on('sending', function(file, xhr, formData){
	    //fetch the user id from hidden input field and send that postid with our image
	      let postid = document.getElementById('postid').value;
		   formData.append('postid', postid);
		});
		
	    this.on("success", function (file, response) {
            //reset the form
            $('#demoform')[0].reset();
            //reset dropzone
            $('.dropzone-previews').empty();
			location.reload();
        });
        this.on("queuecomplete", function () {
		
        });
		
        // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
	    // of the sending event because uploadMultiple is set to true.
	    this.on("sendingmultiple", function() {
	      // Gets triggered when the form is actually being sent.
	      // Hide the success button or the complete form.
	    });
		
	    this.on("successmultiple", function(files, response) {
	      // Gets triggered when the files have successfully been sent.
	      // Redirect user or notify of success.
	    });
		
	    this.on("errormultiple", function(files, response) {
	      // Gets triggered when there was an error sending the files.
	      // Maybe show form again, and notify user of error
	    });
	}
	});
});
</script>


</body>
</html>