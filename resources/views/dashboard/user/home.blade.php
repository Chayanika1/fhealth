@extends('layouts.site.app')

@section('content')

	<link rel="stylesheet" type="text/css" href="{{ asset('css/dropzone.css') }}">
	<script src="{{ asset('js/dropzone.js') }}"></script>
	<script type = "text/javascript" src = "https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.0/tinymce.min.js"></script>  
	
	<style>
		.dropzoneDragArea:before {
			content: "Upload images and pdfs";
		}
    	.dropzoneDragArea {
		    background-color: #fbfdff;
		    border: 1px dashed #c0ccda;
		    border-radius: 6px;
		    padding: 18px;
		    text-align: center;
		    margin-bottom: 8px;
		    cursor: pointer;
		}
		.dropzone{
			box-shadow: 0px 2px 20px 0px #f2f2f2;
			border-radius: 10px;
		}
		.dropzone-previews{/*display: flex; position: absolute; top: 4%; left: 4%;*/height: 150px; overflow-y: scroll; padding: 5px;}
		.dz-success-mark, .dz-error-mark {display:none!important;}
		.dz-preview{border: 1px solid #dcdada; border-radius: 5px; margin-bottom: 5px;}
		.dz-image{/*display:none;*/ padding: 2px;}
		.dz-image img{width: 8%;}
		.dz-size{display: none;}
		.dz-filename{/*position: absolute; top: 59%; left: 12%;*/ margin-top: -6%; margin-left: 10%;}
		/* .dz-details{display: none;} */
		.dz-remove{visibility: hidden;
			position: absolute;
			top: 51%;
    		right: -9%;
			content: "✖";
			border: 1px solid gray;
			background-color:#eee;
			padding:0.5em;
			cursor: pointer;
			color: red
		}
		.dz-remove:before  {
			visibility: visible;
			/*font-family: FontAwesome;*/ 
			font-size: 30px; 
			display: inline-block; 
			content: "✖";
		}
		.tox:not(.tox-tinymce-inline) .tox-editor-header{display:none;}
		.tox .tox-statusbar{display:none;}

		#social-links{width: 100%;}
		#social-links ul{display: flex; justify-content:space-between; list-style: none;}
		#social-links ul li{width: 18%;}
		#social-links ul li a{color:#395e9a; font-size: 20px;}
    
            /* .social-btn-sp #social-links {
                margin: 0 auto;
                max-width: 500px;
            }
            .social-btn-sp #social-links ul li {
                display: inline-block;
            }          
            .social-btn-sp #social-links ul li a {
                padding: 15px;
                border: 1px solid #ccc;
                margin: 1px;
                font-size: 30px;
            }
            table #social-links{
                display: inline-table;
            }
            table #social-links ul li{
                display: inline;
            }
            table #social-links ul li a{
                padding: 5px;
                border: 1px solid #ccc;
                margin: 1px;
                font-size: 15px;
                background: #e3e3ea;
            } */
        </style>

	@include('partials.header')

    <div class="row community-body">
        <div class="col-3 community-body-left">
            <div class="community-menu">
                <ul>
                    <li>FEED
                        <ul>
                            <li>Home</li>
                        </ul>
                    </li>
                    <li>GROUPS
                        <ul>
                            <li>Group 1</li>
                            <li>Group 2</li>
                        </ul>
                    </li>
                    <li>TOPICS
                        <ul>
                            <li>Topics 1</li>
                            <li>Topics 2</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-6 community-body-middle">
            <div class="community-post">
                <!-- <div class="community-post-menu d-flex">
                    <p>Hot</p>
                    <p>Location</p>
                    <p>New</p>
                    <p>Top</p>
                </div> -->
                <div class="row feed-posting">
                    <div class="col-1 post-here-dp">
                        <a href="#">
                            <img src="{{ asset('images/1946429.png')}}">
                        </a>
                    </div>
                    <div class="col-9 post-here-txt">
                        <button class="post-section">Post Here ...</button>
                    </div>
                    <div class="col-2 post-here-icons d-flex justify-content-between">
                        <a href="#" class="pdf-icon flex"><img src="{{ asset('images/pdf-icon.png')}}"></a>
                        <a href="#" class="link-icon flex"><img src="{{ asset('images/link-icon.png')}}"></a>
                        <a href="#" class="gallery-icon flex"><img src="{{ asset('images/gallery-icon.png')}}"></a>
                    </div>
                </div>

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
        <div class="col-3 community-body-right"></div>
    </div>

	<div class="post-modal">
        <div class="row post-modal-content">
            <div class="col-12 post-modal-heading">
                <p>Create Post</p>
                <div class="post-cross">
                    &#10006;
                </div>
            </div>
			<form action="{{ route('user.upload_post') }}" name="demoform" id="demoform" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="col-12 post-modal-body">
					<div class="row post-info">
						<div class="col-6 post-info-left">
							<a href="#">
							<img src="{{ asset('images/1946429.png')}}">
							</a>
							<p class="post-ac-name">{{Auth::guard('web')->user()->name}}</p>
						</div>
						<div class="col-6 post-info-right">
							<input type="hidden" class="postid" name="postid" id="postid" value="">
                            <input type="hidden" id="uid" name="uid" value="{{Auth::guard('web')->user()->id}}">

							<select name="postgroup" id="postgroup">
								<option selected value="">Select Group</option>
								@foreach($groupList as $group)
									<option value="{{$group->id}}">{{$group->name}}</option>
								@endforeach
							</select>
							<span id="postgroup-msg" style="display:none; position:absolute; bottom:-40%; font-size:11px; color:red"></span>
						</div>
					</div>
					<div class="col-12 post-textarea">
						<textarea name="postcontent" id="postcontent" rows="10" placeholder="Write Something"></textarea>
						<span id="postcontent-msg" style="display:none; font-size:11px; color:red"></span>
					</div>

					<div class="col-12 bulkDropzone" style="display:none">
						<div id="dropzoneDragArea" class="dz-default dz-message dropzoneDragArea">
							
						</div>
						<div class="dropzone-previews"></div>
					</div>

					<div class="col-12 row post-attachment">
						<div class="col-6 post-attach-left">
							<p>Add to your post</p>
						</div>
						<div class="col-6 post-attach-right">
							<a href="#" class="pdf-icon flex"><img src="{{ asset('images/pdf-icon.png')}}"></a>
							<a href="#" class="link-icon flex"><img src="{{ asset('images/link-icon.png')}}"></a>
							<a href="#" class="gallery-icon flex" id="img_icon"><img src="{{ asset('images/gallery-icon.png')}}"></a>
						</div>
					</div>
					<div class="col-12 post-footer">
						<button class="post-button" id="submitbtn">Post</button>
					</div>
				</div>
			</form>
        </div>
    </div>

	<script>
	
		// tinymce.init({  
		// 	selector: '#postcontent',  
		// 	width: 550, 
		// 	height: 250
		// }); 

		
			tinymce.init({
				selector: '#postcontent',
				width: 550, 
				height: 250,
				/*theme: 'modern',
				plugins: [
					'advlist autolink lists link image charmap print preview hr anchor pagebreak',
					'searchreplace wordcount visualblocks visualchars code fullscreen',
					'insertdatetime media nonbreaking save table contextmenu directionality',
					'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
				],
				toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
				toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
				image_advtab: true,
				templates: [{
						title: 'Test template 1',
						content: 'Test 1'
					},
					{
						title: 'Test template 2',
						content: 'Test 2'
					}
				],
				content_css: [],*/
				init_instance_callback: function (editor) {
					editor.on('keydown', function (e) {
					$('#postcontent-msg').hide();
					});
				}
			});
		

		
    

        $(".post-section").click(function(){
            $(".post-modal").addClass("show-post-modal")
        })
        $(".post-cross").click(function(){
            $(".post-modal").removeClass("show-post-modal")
        })

		var ENDPOINT = "{{ url('/') }}";
		var page = 1;
		//infinteLoadMore(page);
		load_more(page);
		$(window).scroll(function () {
			//if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
			if($(window).scrollTop()>= $(document).height() - $(window).height() - $('.community-footer').height() - 100) {
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

		
		$("#img_icon").click(function() {
			event.stopPropagation();
			$(".bulkDropzone").toggle();
		});

		// function validatePostContent() {
		// 	//let postcontentValue = $("#postcontent").val();
		// 	var postcontentValue = tinyMCE.get('postcontent').getContent();
		// 	if (postcontentValue == "") {
		// 		$("#postcontent-msg").show();
		// 		$("#postcontent-msg").html("**Please write some content");
		// 		postcontentError = false;
		// 		return false;
		// 	} else {
		// 		$("#postcontent-msg").hide();
		// 		postcontentError = true;
		// 	}
		// }

		// $("#postcontent").keyup(function() {
		// 	$("#postcontent-msg").hide();
		// });

		function validatePostGroup() {
			let postgroupValue = $("#postgroup").val();
			if (postgroupValue.length == "") {
			//if($('#postgroup').prop('disabled',true)){
				$("#postgroup-msg").show();
				$("#postgroup-msg").html("**Please select group");
				//$('#postgroup').prop('disabled',false)
				postgroupError = false;
				return false;
			} else {
				$("#postgroup-msg").hide();
			}
		}

		$('#postgroup').on('change', function() {
			$("#postgroup-msg").hide();
		});

		$("#submitbtn").click(function () {
						
			//validatePostGroup();
			let postgroupValue = $("#postgroup").val();
			if (postgroupValue == "") {
				$("#postgroup-msg").show();
				$("#postgroup-msg").html("**Please select group");
				return false;
			} 

			var postcontentValue = tinyMCE.get('postcontent').getContent();
			
			if (postcontentValue == "") {
				$("#postcontent-msg").show();
				$("#postcontent-msg").html("**Please write some content");
				//postcontentError = false;
				return false;
			}
			// validatePostContent();

			//alert(postcontentError);
			
			// if (postgroupError == true) {
			// 	return true;
			// } else {
			// 	return false;
			// }
			
		});

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

    @include('partials.footer')

@endsection