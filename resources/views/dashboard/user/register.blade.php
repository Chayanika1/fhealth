<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <h4>Registration</h4>
                @if(Session::has('success'))
                    <div class="alert alert-success">{{Session::get('success')}}</div>
                @endif
                @if(Session::has('error'))
                    <div class="alert alert-danger">{{Session::get('error')}}</div>
                @endif
                <form action="{{route('user.create-user')}}" method="POST">
                    @csrf
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}">
                        </div>
                        <span class="text-danger">@error('name'){{$message}}@enderror</span>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="email" name="email" value="{{old('email')}}">
                        </div>
                        <span class="text-danger">@error('email'){{$message}}@enderror</span>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <span class="text-danger">@error('password'){{$message}}@enderror</span>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Confirm Password</label>
                        <div class="col-sm-10">
                        <input type="password" class="form-control" id="cpassword" name="cpassword">
                        </div>
                        <span class="text-danger">@error('cpassword'){{$message}}@enderror</span>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                Already Registered <a href ="{{route('user.login')}}">Sign In</a> Here
            </div>
        </div>
    </div>
</body>
</html>