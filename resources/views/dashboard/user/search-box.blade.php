<ul>
    @foreach($data as $value)
    <!-- <li onclick='fill("{{$value->name}}")'>{{$value->name}}</li> -->
    <li><a href="{{ route('user.group', [base64_encode($value->id)]) }}"><i class="fa fa-search"></i>{{$value->name}}</a></li>
    @endforeach
</ul>