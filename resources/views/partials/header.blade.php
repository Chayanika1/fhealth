<div class="row community-head">
    <div class="col-3 d-flex justify-content-center">
        <p class="Community-title">Community</p>
    </div>
    <div class="col-6 community-head-middle">
        <div class="community-form-group"> 	
            <span><i class="fa fa-search"></i></span>
            <!-- <span>&#x1F50E;</span> -->
            <input type="text" class="form-control" id="search" placeholder="Search groups, posts, profiles etc." autocomplete="off">

        </div>
        
        <!-- Suggestions will be displayed in below div. -->
        <!-- <div id="display"></div> -->
        <div class="main-page-search" id="display" style="display: none;">
        </div>
        
    </div>
    <div class="col-3 d-flex justify-content-center">
        <div class="notification flex">
            <a href="#">
                <i class="fa fa-bell"></i>
            </a>
        </div>
        <div class="account flex">
            <div class="account-logo">
                <!-- <i class="fa-regular fa-circle-user"></i> -->
                <a href="#">
                    <img src="{{ asset('images/1946429.png')}}">
                </a>
            </div>
            <div class="account-div">
                <ul class="head-order-select" id="chngcurrency" style="display: none;">
                    <li><a href="#" alt="Login">SIGN IN</a></li>
                    <li><a href="#" alt="Order Status">MY PROFILE</a></li>   
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function fill(Value) {
   $('#search').val(Value);
   $('#display').hide();
}
$(document).ready(function() {
   
    $("#search").keyup(function() {
        var searchkey = $('#search').val();
        if (searchkey == "") {
           $("#display").html("");
           $("#display").css("display", "none");
        }
        else {
            $.ajax({
            type : "POST",
            url : "{{ route('user.searchkeyword') }}",
            data : { search : searchkey },
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : function(html){
                //console.log(html);
                $("#display").html(html).show();
            },
            error : function(){
            }
        });  
       }
    });
});
</script>