{{--@foreach($results as $data)   
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
@endforeach--}}

@foreach($results as $data)
@php
    $pid = $data->id; 
    $uid = Auth::guard('web')->user()->id;
    $grpDtls = App\Http\Helper::get_groupDetails($data->gid);
    //print_r($grpDtls);
@endphp
<div class="row main-post singlepost{{$pid}}">
    <div class="col-12 row main-post-head">
        <div class="col-1 mph-dp flex">
            <a href="#">
                <img src="{{ asset('images/1946429.png')}}">
            </a>
        </div>
        <div class="col-6 mph-details">
            <p class="mph-name">{{$data->name}}</p>
            <p class="mph-date">{{date('jS M,Y', strtotime('+5 hour +30 minutes',strtotime($data->created_at)))}} at {{date('g:i a', strtotime('+5 hour +30 minutes',strtotime($data->created_at)))}}</p>
        </div>
        <div class="col-5 mph-group-name d-flex justify-content-end align-items-center">
            <p>Posted in {{$grpDtls->name}}</p>
        </div>
    </div>
    <div class="col-12 main-post-body">
        <div class="mpb-txt">
            <p>
                @if(strlen($data->content) > 500)
                    {!! substr($data->content, 0, 499) !!}..
                    <span class="d-flex justify-content-end">
                        <a href="{{ route('user.article', [base64_encode($pid)]) }}" style="font-weight: 500;">Read Full Article</a>
                    </span>
                @else
                    {!! $data->content !!}
                @endif
                
            </p>
            
        </div>
        <div class="mpb-images d-flex justify-content-between">
            @foreach($data->post_images as $img)
            <div class="mpb-img">
                <a href="{{ route('user.article', [$pid]) }}">
                    <img src="{{ asset('uploads/images/'.$img['image'])}}">
                </a>
            </div>
            @endforeach
        </div>
    </div>
    <div class="col-12 row main-post-footer">
        <div class="col-6 mpf-left">
            <div class="mpfl-like">
                @php
                  $is_liked = App\Http\Helper::check_liked($uid, $pid);
                @endphp
                <button class="post-like @if($is_liked > 0) {{ 'post-liked' }} @endif" data-uid="{{$uid}}" data-pid="{{$pid}}">Like</button>
                <!-- <button class="flex like" data-uid="{{$data->uid}}" data-pid="{{$data->pid}}"><i class="fa fa-thumbs-o-up"></i>&nbsp;Like</button>
                <button class="flex"><i class="fa fa-thumbs-up"></i>&nbsp;Liked</button> -->

                <!-- @php
                  $is_liked = App\Http\Helper::check_liked($data->uid, $data->pid);
                @endphp
                @if($is_liked > 0)
                    <button class="flex like act pliked" data-uid="{{$data->uid}}" data-pid="{{$data->pid}}"><i class="fa fa-thumbs-up"></i>&nbsp;Liked</button>
                @else
                    <button class="flex like plike" data-uid="{{$data->uid}}" data-pid="{{$data->pid}}"><i class="fa fa-thumbs-o-up"></i>&nbsp;Like</button>
                @endif -->
            </div>
            <div class="mpfl-comment">
                <button class="flex"><i class="fa fa-comment"></i>&nbsp;Comment</button>
            </div>
            <div class="mpfl-share">
                <button class="flex shareButton" data-pid="{{$pid}}"><i class="fa fa-share"></i>&nbsp;Share</button>
                
                <div class="shareplaceholder{{$pid}}" style="display:none">
                    <div class="share-div ">
                        {!! Share::page(route('user.article', [$pid]))->facebook()->twitter()->linkedin()->whatsapp()->telegram() !!}                                  
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 mpf-right">
            @php
                $getTotalLiked = App\Http\Helper::get_liked($pid);
                $getTotalComment = App\Http\Helper::get_totalcomment($pid);
                //echo $getTotalLiked;
            @endphp
            <div class="mpfr-like-count">
                <p><span>{{$getTotalLiked}}</span> Likes</p>
                
            </div>
            <div class="mpfr-comment-count">
                <p><span>{{$getTotalComment}}</span> Comments</p>
            </div>
        </div>
    </div>
</div>
@endforeach



<script>
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

        let likeNo = parseInt($( ".singlepost"+pid ).find( ".mpfr-like-count p span" ).text());

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
                            //console.log($( ".singlepost"+pid ).find( ".mpfr-like-count p span" ).text());
                            likeNo = likeNo + 1;
                            $( ".singlepost"+pid ).find( ".mpfr-like-count p span" ).text(likeNo);
                        }else{
                            $(like_obj).removeClass('post-liked');
                            likeNo = likeNo - 1;
                            $( ".singlepost"+pid ).find( ".mpfr-like-count p span" ).text(likeNo);
                        }
                    }
                },
            error : function(){
            }
        });  
    });
</script>

