@extends('layouts.site.app')

@section('content')

@include('partials.header')

<div class="post-wrapper">
    <div class="group-feed-section">
        <div class="group-feed-image">
            <img src="{{ asset('uploads/groups_image/'.$groupResult->image)}}">
        </div>
        <div class="alert alert-success succsess-msg" style="display:none; margin-top: 10px;">
            <b>Thank You..</b>Your joining request sending to Admin
            <br>Kindly give us 24 hrs to approved by Admin. You will received a email notification.
        </div>
        <div class="group-feed-details">
            <div class="name-nd-join">
                <p>{{$groupResult->name}}</p>
                @if($userGroupVerified == 'Y')
                <button>Joined</button>
                @elseif($userGroupVerified == 'R')
                <button>Join Request Send</button>
                @else
                <button class="nogrp group_join" data-uid="{{$uid}}" data-gid="{{$groupResult->id}}"><span>Join</span></button>
                @endif
            </div>
            @php
                $getTotalGroupMember = App\Http\Helper::get_groupMembers($groupResult->id);
            @endphp
            <div class="member-no">
                <span>{{count($getTotalGroupMember)}}</span> Member
            </div>
            <div class="group-about">
                <p class="group-about-title">About</p>
                <p class="group-about-txt">{{$groupResult->content}}</p>
            </div>
        </div>
        
        <input type="hidden" name="gid" id="gid" value="@if($userGroupVerified == 'Y'){{$groupResult->id}}@endif">
        

        @if($userGroupVerified == 'Y')
        <div id="data-wrapper">
                
        </div>
        <div class="loadmore" style="display: none;">
            <div id="loadmore-btn"  class="row"></div>
        </div>
        
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
        @endif

        <!-- <div class="group-feed-body">
            <div class="row gfb-profile">
                <div class="col-12 row gfb-head">
                    <div class="col-1 gfb-dp flex">
                        <a href="#">
                            <img src="images/1946429.png">
                        </a>
                    </div>
                    <div class="col-6 gfb-details">
                        <p class="gfb-name">User Name</p>
                        <p class="gfb-date">12 February at 7:39 PM</p>
                    </div>
                </div>
            </div>
            <div class="gfb-txt">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur omnis inventore recusandae veniam necessitatibus atque quod rerum minus commodi, nihil deserunt, accusamus eum voluptatibus eos ducimus deleniti, aut soluta doloribus.
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo autem nesciunt soluta non? Possimus inventore corrupti, ea ab ratione dolore? Ducimus facilis, sed perspiciatis sapiente...
                </p>
            </div>
            <div class="gfb-images d-flex justify-content-between">
                <div class="gfb-img">
                    <a href="#">
                        <img src="images/Anniversary.jpg">
                    </a>
                </div>
                <div class="gfb-img">
                    <a href="#">
                        <img src="images/caffeine.jpg">
                    </a>
                </div>
            </div>
            <div class="row gfb-footer">
                <div class="col-6 gfb-left">
                    <div class="gfb-like">
                        <button class="post-like post-liked">Like</button>
                    </div>
                    <div class="gfb-comment">
                        <button class="flex"><i class="fa fa-comment-o"></i>&nbsp;Comment</button>
                    </div>
                    <div class="gfb-share">
                        <button class="flex"><i class="fa fa-share"></i>&nbsp;Share</button>
                        <div class="share-div">
                            <i class="fa fa-facebook"></i>
                            <i class="fa fa-facebook"></i>
                            <i class="fa fa-facebook"></i>                                    
                        </div>
                    </div>
                </div>
                <div class="col-6 gfb-right">
                    <div class="gfb-like-count">
                        <p><span>10</span> Likes</p>
                    </div>
                    <div class="gfb-comment-count">
                        <p><span>10</span> Comments</p>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</div>





<script>

    $(document).on('click', '.group_join', function(event){
        var grpjoin_obj = this;
        var uid = $(this).data('uid');
        var gpid = $(this).data('gid');

        $.ajax({
            type : "POST",
            url : "{{ route('user.sendJoinGroupRequest') }}",
            data : { gid : gpid, uid : uid},
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : function(response){
                response = JSON.parse(response);
                    console.log(response);
                    if(response.status == 'success'){
                        $('.nogrp span').text('Join Request Send');
                        $('.nogrp').removeClass( "group_join" )
                        $('.succsess-msg').show();
                    }
                },
            error : function(){
            }
        }); 
    });


    //alert($('#gid').val());
    var ENDPOINT = "{{ url('/') }}";
    var page = 1;
    //infinteLoadMore(page);
    if($('#gid').val() != ''){
        load_more(page);
    }
    $(window).scroll(function () {
        //if($(window).scrollTop()>= $(document).height() - $(window).height() - $('.community-footer').height() - 100) {
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
                url: ENDPOINT + "/user/groupcontents?gid="+ $('#gid').val()+"&page=" + page,
                datatype: "html",
                type: "get",
                beforeSend: function () {
                    $('.auto-load').show();
                }
            })
            .done(function (response) {
                console.log(response.startsWith('<script'))
                //if (response.length == 0) {
                if(response.startsWith('<script') == true){
                    //alert("jj");
                    //$('.auto-load').html('');
                    $('.auto-load').hide();
                    $('.loadmore').hide();
                    return false;
                    //$('.more_products').html('No more records!');
                    return;
                } else {
                    $('.auto-load').hide();
                    $("#data-wrapper").append(response);
                }


                // if(response.startsWith('<script')){    
                //     $('.auto-load').html('');
                //     $('.loadmore').hide();
                //     return false;
                // }
                // $('.auto-load').hide();
                // $("#data-wrapper").append(response);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log('Server error occured');
            });
        
    }
</script>

@include('partials.footer')

@endsection