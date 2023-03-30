@extends('layouts.site.app')

@section('content')

@include('partials.header')

<style>
#social-links{width: 100%;}
#social-links ul{display: flex; justify-content:space-between; list-style: none;}
#social-links ul li{width: 18%;}
#social-links ul li a{color:#395e9a; font-size: 20px;}

    .display-comment .display-comment {
        margin-left: 40px
    }
    .below-cmnt{margin-left: 40px}

</style>

@php
    $pid = $data->id; 
    $uid = Auth::guard('web')->user()->id;
    $grpDtls = App\Http\Helper::get_groupDetails($data->gid);
    //print_r($grpDtls);
@endphp

<link rel="stylesheet" href="{{ asset('css/owl.carousel.css') }}">
<link rel="stylesheet" href="{{ asset('css/owl.theme.default.css') }}">

<div class="post-wrapper">
        <div class="row post-page-section">

            <div class="col-12 row pps-head">
                <div class="col-1 ppsh-dp flex">
                    <a href="#">
                        <img src="{{ asset('images/1946429.png')}}">
                    </a>
                </div>
                <div class="col-6 ppsh-details">
                    <p class="ppshd-name">{{$data->name}}</p>
                    <p class="ppshd-date">{{date('jS M,Y', strtotime('+5 hour +30 minutes',strtotime($data->created_at)))}} at {{date('g:i a', strtotime('+5 hour +30 minutes',strtotime($data->created_at)))}}</p>
                </div>
                <div class="col-5 ppsh-group-name d-flex justify-content-end align-items-center">
                    <p>Posted in {{$grpDtls->name}}</p>
                </div>
            </div>

            <div class="col-12 pps-body">
                <div class="ppsb-txt">
                    <p>{!! $data->content !!}</p>
                </div>
                <div class="ppsb-carousel owl-carousel">
                    @foreach($data->post_images as $img)
                        <div class="ppsb-item">
                            <img src="{{ asset('uploads/images/'.$img['image'])}}">
                        </div>
                    @endforeach
                </div>
                <div class="col-12 row ppsb-footer">
                    <div class="col-6 ppsbf-left">
                        <div class="ppsbfl-like">
                        @php
                            $is_liked = App\Http\Helper::check_liked($uid, $pid);
                        @endphp
                        <button class="post-like @if($is_liked > 0) {{ 'post-liked' }} @endif" data-uid="{{$uid}}" data-pid="{{$pid}}">Like</button>
                        </div>
                        <div class="ppsbfl-comment">
                            <button class="flex"><i class="fa fa-comment"></i>&nbsp;Comment</button>
                        </div>
                        <div class="ppsbfl-share">
                            <button class="flex shareButton" data-pid="{{$pid}}"><i class="fa fa-share"></i>&nbsp;Share</button>
                            
                            <div class="shareplaceholder{{$pid}}" style="display:none">
                                <div class="share-div ">
                                    {!! Share::page(route('user.article', [$pid]))->facebook()->twitter()->linkedin()->whatsapp()->telegram() !!}                                  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 ppsbf-right">
                    @php
                        $getTotalLiked = App\Http\Helper::get_liked($pid);
                        $getTotalComment = App\Http\Helper::get_totalcomment($pid);
                        //echo $getTotalLiked;
                    @endphp
                        <div class="ppsbfr-like-count">
                            <p><span>{{$getTotalLiked}}</span> Likes</p>
                        </div>
                        <div class="ppsbfr-comment-count">
                            <p><span>{{$getTotalComment}}</span> Comments</p>
                        </div>
                    </div>
                </div>
                <div class="ppsb-comment">
                    
                        <div class="ppsbc-write-comment">
                            <input type="text" name="parent_comment" id="parent_comment" placeholder="Write a comment">
                            <button class="send-cmnt" data-uid="{{$uid}}" data-pid="{{$pid}}" data-parentid="0">&#10146;</button>
                        </div>

                       
                    

                    <ul class="ppsbc-main-cmnt-ul">

                        
                        <li class="ppsbc-all-cmnt ajax-comment">
                            <!-- <div class="cmnt-author">
                                <a href="#">
                                    <img src="{{ asset('images/1946429.png')}}">
                                </a>
                                <p class="ppshd-name">User Name</p>
                            </div>
                            <div class="written-cmnt">
                                <p>This is a demo comment This is a demo comment This is a demo comment This is a demo comment This is a demo comment</p>
                                <div class="below-cmnts-buttons">
                                    <div class="comment-like-btn">
                                        <button class="post-like post-liked">Like</button>
                                    </div>
                                    <div class="comment-reply-btn">
                                        <button>Reply</button>
                                    </div>
                                </div>
                            </div> -->
                        </li>

                        @include('dashboard.user.replys', ['comments' => $post->comments, 'pid' => $pid])


                        <!-- <li class="ppsbc-all-cmnt">
                            <div class="cmnt-author">
                                <a href="#">
                                    <img src="{{ asset('images/1946429.png')}}">
                                </a>
                                <p class="ppshd-name">User Name</p>
                            </div>
                            <div class="written-cmnt">
                                <p>This is a demo comment This is a demo comment This is a demo comment This is a demo comment This is a demo comment</p>
                                <div class="below-cmnts-buttons">
                                    <div class="comment-like-btn">
                                        <button class="post-like post-liked">Like</button>
                                    </div>
                                    <div class="comment-reply-btn">
                                        <button>Reply</button>
                                    </div>
                                </div>
                                <div class="main-cmnt-reply">
                                    <input placeholder="Write a reply">
                                    <button class="send-cmnt">&#10146;</button>
                                </div>
                                <ul class="below-cmnt">
                                    <li class="comment-reply">
                                        <div class=" comment-reply-txt">
                                            <a href="#">
                                                <img src="{{ asset('images/1946429.png')}}">
                                            </a>
                                            <p>This is a demo Reply This is a demo Reply This is a demo Reply This is a demo Reply</p>
                                        </div>
                                        <div class="below-cmnts-buttons">
                                            <div class="comment-like-btn">
                                                <button class="post-like post-liked">Like</button>
                                            </div>
                                            <div class="comment-reply-btn">
                                                <button>Reply</button>
                                            </div>
                                        </div>
                                        <div class="sub-cmnt-reply">
                                            <input placeholder="Write a reply">
                                            <button class="send-cmnt">&#10146;</button>
                                        </div>
                                    </li>
                                    <li class="comment-reply">
                                        <div class=" comment-reply-txt">
                                            <a href="#" >
                                                <img src="{{ asset('images/1946429.png')}}">
                                            </a>
                                            <p>This is a demo Reply This is a demo Reply This is a demo Reply This is a demo Reply</p>
                                        </div>
                                        <div class="below-cmnts-buttons">
                                            <div class="comment-like-btn">
                                                <button class="post-like post-liked">Like</button>
                                            </div>
                                            <div class="comment-reply-btn">
                                                <button>Reply</button>
                                            </div>
                                        </div>
                                        <div class="sub-cmnt-reply">
                                            <input placeholder="Write a reply">
                                            <button class="send-cmnt">&#10146;</button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li> -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

<script src="{{ asset('js/slider.js') }}"></script>
<script>
    $('.ppsb-carousel').owlCarousel({
        items:1,
        loop:true,
        dots:false,
        nav:true,
        navText: ["<", ">"],
        lazyLoad:true
    });
</script>
<script>

    $(document).on('click', '.cmt_reply', function(event){
        var cmtreply_obj = this;
        var cid = $(this).data('cid');
        //$(".share-div").hide();
        //event.stopPropagation();
        $('.sub-cmnt-reply').hide();
        $(".snd-rply-"+cid).toggle();		
    });

    // $('#parent_comment').keydown(function (e) {
    //     if (e.keyCode == 13) {
    //         //alert('you pressed enter ^_^');
    //         $(".send-cmnt").trigger("click");
    //     }
    // })

    $(document).on('click', '.send-cmnt', function(event){
        //console.log('you pressed enter ^_^');
        var sendobj = this;
        var uid = $(this).data('uid');
        var pid = $(this).data('pid');
        var parentid = $(this).data('parentid');
        var parent_comment = $('#parent_comment').val();
        //alert(parent_comment);
        if(parent_comment == ''){
            return false;
        }
        let commentNo = parseInt($( ".ppsbfr-comment-count p span" ).text());

        $.ajax({
            type : "POST",
            url : "{{ route('user.postComment') }}",
            data : { pid : pid, uid : uid, parentid : parentid, comment : parent_comment },
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : function(response){
                response = JSON.parse(response);
                    console.log(response);
                    if(response.status == 'success'){
                        // if(parentid == 0){
                        //     $('.ajax-comment').append("<div class='cmnt-author'><a href='#'><img src='http://localhost/un_social/public/images/1946429.png'></a><p class='ppshd-name'>"+response.uname+"</p></div><div class='written-cmnt'><p>"+response.ucomment+"</p><div class='below-cmnts-buttons'><div class='comment-like-btn'><button class='post-like post-liked'>Like</button></div><div class='comment-reply-btn'><button>Reply</button></div></div></div>");
                        // }else{
                        //     $('.ajax-child-comment-'+parentid).append("<div class='cmnt-author'><a href='#'><img src='http://localhost/un_social/public/images/1946429.png'></a><p class='ppshd-name'>"+response.uname+"</p></div><div class='written-cmnt'><p>"+response.ucomment+"</p><div class='below-cmnts-buttons'><div class='comment-like-btn'><button class='post-like post-liked'>Like</button></div><div class='comment-reply-btn'><button>Reply</button></div></div></div>");    
                        // }
                        $("#parent_comment").val('');
                        //$('#inputID').removeAttr('value');
                        $('.ajax-comment').append("<div class='cmnt-author'>"+
                                                    "<a href='#'><img src='http://localhost/un_social/public/images/1946429.png'></a>"+
                                                    "<p class='ppshd-name'>"+response.uname+"</p></div>"+
                                                    "<div class='written-cmnt'><p>"+response.ucomment+"</p>"+
                                                    "<div class='below-cmnts-buttons'>"+
                                                    "Just Now"+
                                                    "<div class='comment-reply-btn'><button class='cmt_reply' data-cid='"+ response.cid +"'>Reply</button></div></div></div>"+
                                                    "<div class='sub-cmnt-reply snd-rply-"+ response.cid +"' style='display:none'>"+
                                                    "<input type='hidden' name='post_id' value='"+ pid +"' />"+
                                                    "<input type='hidden' name='comment_id' value='"+ response.cid +"' />"+
                                                    "<input type='text' name='parent_comment_child' id='parent_comment_child_"+ response.cid +"' placeholder='Write a reply'>"+
                                                    "<button class='send-cmnt-child' data-uid='"+ uid +"' data-pid='"+ pid +"' data-parentid='"+ response.cid +"'>&#10146;</button>"+
                                                    "</div>"+
                                                    "<ul class='below-cmnt'>"+
                                                    "<li class='ppsbc-all-cmnt ajax-child-comment-"+ response.cid +"'></li>"+        
                                                    "</ul>"
                                                );  

                        commentNo = commentNo + 1;
                        $( ".ppsbfr-comment-count p span" ).text(commentNo);
                    }
                },
            error : function(){
            }
        });  
    });

    $(document).on('click', '.send-cmnt-child', function(event){
        //console.log('you pressed enter ^_^');
        var sendobj = this;
        var uid = $(this).data('uid');
        var pid = $(this).data('pid');
        var parentid = $(this).data('parentid');
        var parent_comment = $('#parent_comment_child_'+parentid).val();
        if(parent_comment == ''){
            return false;
        }
        //alert(parent_comment);
        let commentNo = parseInt($( ".ppsbfr-comment-count p span" ).text());

        $.ajax({
            type : "POST",
            url : "{{ route('user.postComment') }}",
            data : { pid : pid, uid : uid, parentid : parentid, comment : parent_comment },
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : function(response){
                response = JSON.parse(response);
                    console.log(response);
                    if(response.status == 'success'){
                        $(".snd-rply-"+parentid).toggle();
                        // $('.ajax-child-comment-'+parentid).append("<div class='cmnt-author'>"+
                        //                                             "<a href='#'><img src='http://localhost/un_social/public/images/1946429.png'></a>"+
                        //                                             "<p class='ppshd-name'>"+response.uname+"</p></div>"+
                        //                                             "<div class='written-cmnt'><p>"+response.ucomment+"</p>"+
                        //                                             "<div class='below-cmnts-buttons'>"+
                        //                                             "Just Now"+
                        //                                             "<div class='comment-reply-btn'><button>Reply</button></div></div></div>");   
                        
                        $('.ajax-child-comment-'+parentid).append("<div class='cmnt-author'>"+
                                                    "<a href='#'><img src='http://localhost/un_social/public/images/1946429.png'></a>"+
                                                    "<p class='ppshd-name'>"+response.uname+"</p></div>"+
                                                    "<div class='written-cmnt'><p>"+response.ucomment+"</p>"+
                                                    "<div class='below-cmnts-buttons'>"+
                                                    "Just Now"+
                                                    "<div class='comment-reply-btn'><button class='cmt_reply' data-cid='"+ response.cid +"'>Reply</button></div></div></div>"+
                                                    "<div class='sub-cmnt-reply snd-rply-"+ response.cid +"' style='display:none'>"+
                                                    "<input type='hidden' name='post_id' value='"+ pid +"' />"+
                                                    "<input type='hidden' name='comment_id' value='"+ response.cid +"' />"+
                                                    "<input type='text' name='parent_comment_child' id='parent_comment_child_"+ response.cid +"' placeholder='Write a reply'>"+
                                                    "<button class='send-cmnt-child' data-uid='"+ uid +"' data-pid='"+ pid +"' data-parentid='"+ response.cid +"'>&#10146;</button>"+
                                                    "</div>"+
                                                    "<ul class='below-cmnt'>"+
                                                    "<li class='ppsbc-all-cmnt ajax-child-comment-"+ response.cid +"'></li>"+        
                                                    "</ul>"
                                                );  

                        commentNo = commentNo + 1;
                        $( ".ppsbfr-comment-count p span" ).text(commentNo);
                        
                    }
                },
            error : function(){
            }
        });  
    });

    $(document).on('click', '.shareButton', function(event){
        var share_obj = this;
        var pid = $(this).data('pid');
        //$(".share-div").hide();
        event.stopPropagation();
        $(".shareplaceholder"+pid).toggle();		
    });

    $(document).on('click', '.post-like', function(event){
        var like_obj = this;
        var action = $(this).hasClass('post-liked') ? 'R' : 'A'; // A => Like, R => Remove like
        var uid = $(this).data('uid');
        var pid = $(this).data('pid');

        let likeNo = parseInt($( ".ppsbfr-like-count p span" ).text());

        $.ajax({
            type : "POST",
            url : "{{ route('user.postLike') }}",
            data : { pid : pid, uid : uid, action : action },
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : function(response){
                response = JSON.parse(response);
                    //console.log(response);
                    if(response.status == 'success'){
                        if(action == "A"){
                            $(like_obj).addClass('post-liked');
                            console.log($( ".ppsbfr-like-count p span" ).text());
                            likeNo = likeNo + 1;
                            $( ".ppsbfr-like-count p span" ).text(likeNo);
                        }else{
                            $(like_obj).removeClass('post-liked');
                            likeNo = likeNo - 1;
                            $( ".ppsbfr-like-count p span" ).text(likeNo);
                        }
                    }
                },
            error : function(){
            }
        });  
    });
</script>

@include('partials.footer')

@endsection