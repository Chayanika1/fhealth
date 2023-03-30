@foreach($comments as $comment)


<li class="ppsbc-all-cmnt">
    <div class="cmnt-author">
        <a href="#">
            <img src="{{ asset('images/1946429.png')}}">
        </a>
        <p class="ppshd-name">{{ $comment->user->name }}</p>
    </div>
    <div class="written-cmnt">
        <p>{{ $comment->comment }}</p>
        <div class="below-cmnts-buttons">
            <!-- <div class="comment-like-btn">
                <button class="post-like post-liked">Like</button>
            </div> -->
            {{App\Http\Helper::comment_time_ago($comment->created_at)}}
            <div class="comment-reply-btn">
                <button class="cmt_reply" data-cid="{{ $comment->id }}">Reply</button>
            </div>
        </div>
        <div class="sub-cmnt-reply snd-rply-{{ $comment->id }}" style="display:none">
            <input type="hidden" name="post_id" value="{{ $pid }}" />
            <input type="hidden" name="comment_id" value="{{ $comment->id }}" />
            <input type="text" name="parent_comment_child" id="parent_comment_child_{{ $comment->id }}" placeholder="Write a reply">
            <button class="send-cmnt-child" data-uid="{{$uid}}" data-pid="{{$pid}}" data-parentid="{{ $comment->id }}">&#10146;</button>
        </div>
    </div>

    <ul class="below-cmnt">
        <li class="ppsbc-all-cmnt ajax-child-comment-{{ $comment->id }}"></li>
        @include('dashboard.user.replys', ['comments' => $comment->replies])
    </ul>
</li>

<script>
    
</script>

@endforeach