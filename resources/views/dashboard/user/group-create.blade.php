@extends('layouts.site.app')

@section('content')

@include('partials.header')

<div class="post-wrapper">
    <div class="create-group-section">
        <p class="cg-title">Create Group</p>
        @if(Session::has('success'))
            <div class="alert alert-success">{!!Session::get('success')!!}</div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-danger">{{Session::get('error')}}</div>
        @endif
        <div class="row cg-profile">
            <div class="col-12 row cg-head">
                <div class="col-1 cg-dp flex">
                    <a href="#">
                        <img src="{{ asset('images/1946429.png')}}">
                    </a>
                </div>
                <div class="col-6 cg-details">
                    <p class="cg-name">{{Auth::guard('web')->user()->name}}</p>
                </div>
            </div>
        </div>
        
        <form action="{{route('user.group-create')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="cg-form">
                <div class="cgf-name">
                    <p>Group Name</p>
                    <input type="text" placeholder="Enter the group name" id="groupname" name="groupname" autocomplete="off">
                    <span class="text-danger">@error('groupname'){{$message}}@enderror</span>
                </div>
                
                <div class="cgf-description">
                    <p>About the Group</p>
                    <textarea rows="5" placeholder="Write Something" id="groupcontent" name="groupcontent" autocomplete="off"></textarea>
                    <span class="text-danger">@error('groupcontent'){{$message}}@enderror</span>
                </div>
               
                <div class="cgf-image">
                    <p>Group Image</p>
                    <input type="file" name="image">
                </div>
                <button class="cgf-submit">SUBMIT</button>
            </div>
        </form>
    </div>
</div>

@include('partials.footer')

@endsection