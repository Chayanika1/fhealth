<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\Group;
use Hash;
use Auth;
use File;

class UserController extends Controller
{
    public function create_user(Request $request){
        //dd($request);
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|max:15',
            'cpassword'=>'required|same:password'
        ],[
            'cpassword.same'=>'The confirm password is required.',
            'cpassword.same'=>'The confirm password and password must match.'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $data = $user->save();
        if($data){
            return redirect()->back()->with('success', 'You have registered successfully');
        }else{
            return redirect()->back()->with('error', 'Registration Failed');
        }
    }

    public function dologin(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:6|max:15',
        ]);
        $check = $request->only('email', 'password');
        if(Auth::guard('web')->attempt($check)){
            return redirect()->route('user.home')->with('success', 'Welcome to Dashboard');
        }else{
            return redirect()->back()->with('error', 'Login Failed');
        }
    }

    public function logout(){
        Auth::guard('web')->logout();
        return redirect('/');
    }

    public function searchkeyword(Request $request){
        $data = [];
        if($request->search != ''){
            $data = Group::where('name', 'like', '%' . $request->search . '%')->get();
        }
        return view('dashboard.user.search-box')->with(['data' => $data]);
    }

    public function homefeed(){
        //$catgory_slug = "Test";
        $uid = Auth::guard('web')->user()->id;

        $groupList = Group::join('user_groups', 'groups.id', '=', 'user_groups.gid')
                            ->select('groups.id','groups.name')
                            ->where(['user_groups.uid' => $uid])
                            ->orderBy('user_groups.created_at', 'desc')
                            ->get();

        //dd($groupList);

        $feedResult = Post::join('users', 'users.id', '=', 'posts.uid')
                        ->select('posts.content', 'posts.id', 'users.name')
                        ->where(['uid' => $uid])
                        ->orderBy('posts.created_at', 'desc')
                        ->get();

        return view('dashboard.user.home')->with(['feedResult' => $feedResult, 'groupList' => $groupList]);
    }

    public function getArticles(Request $request){

        $uid = Auth::guard('web')->user()->id;

        $results = Post::join('users', 'users.id', '=', 'posts.uid')
                        ->select('posts.content', 'posts.id', 'posts.gid', 'users.name', 'posts.created_at')
                        ->where(['uid' => $uid])
                        ->orderBy('posts.created_at', 'desc')
                        ->paginate(5);

        //$results = Blog::orderBy('id')->paginate(10);
        // $artilces = '';
        // if ($request->ajax()) {
        //     foreach ($results as $result) {
        //         $artilces.='<div class="card mb-2"> <div class="card-body">'.$result->content.'</div></div>';
        //     }
        //     return $artilces;
        // }
        return view('dashboard.user.loadmore_post')->with(['results' => $results]);
    }

    public function postLike(Request $request){
        if($request->action == 'R'){
            PostLike::where(['uid' => $request->uid, 'pid' => $request->pid])->delete();
        }else{
            PostLike::create(['uid' => $request->uid, 'pid' => $request->pid]);
        }
        return json_encode(['type' => 'json', 'status' => 'success']);
    }

    public function postComment(Request $request){
        if($data = PostComment::create(['uid' => $request->uid, 'pid' => $request->pid, 'pcid' => $request->parentid, 'comment' => $request->comment])){

        $results = PostComment::join('users', 'users.id', '=', 'post_comments.uid')
                        ->select('post_comments.comment', 'post_comments.id', 'users.name', 'post_comments.created_at')
                        ->where(['post_comments.id' => $data->id])
                        ->first();

        return json_encode(['type' => 'json', 'status' => 'success', 'uname' => $results->name, 'ucomment' => $results->comment, 'cid' => $results->id]);
        }
    }

    public function upload_post(Request $request)
	{
        //dd($request);
		try {

            // $request->validate([
            //     'postgroup'=>'required',
            //     'postcontent'=>'required',
            // ],[
            //     'postgroup.required'=>'Please select the group',
            //     'postcontent.required'=>'The Content is required.'
            // ]);

			$Post = new Post;
            $Post->uid = $request->uid;
            $Post->gid = $request->postgroup;
            $Post->content = $request->postcontent;
            $Post->save();
            $post_id = $Post->id; // this give us the last inserted record id
		}
		catch (\Exception $e) {
			return response()->json(['status'=>'exception', 'msg'=>$e->getMessage()]);
		}
		return response()->json(['status'=>"success", 'post_id'=>$post_id]);
	}

    public function storeimage(Request $request){
        if($request->file('file')){

            $img = $request->file('file');

            //here we are geeting userid alogn with an image
            $postid = $request->postid;

            $imageName = strtotime(now()).rand(11111,99999).'.'.$img->getClientOriginalExtension();
            $user_image = new PostImage();
            $original_name = $img->getClientOriginalName();
            $user_image->image = $imageName;

            if(!is_dir(public_path() . '/uploads/images/')){
                mkdir(public_path() . '/uploads/images/', 0777, true);
            }

            $request->file('file')->move(public_path() . '/uploads/images/', $imageName);

            // we are updating our image column with the help of user id
            //$user_image->where('id', $userid)->update(['image'=>$imageName]);

            $user_image->pid = $postid;
            $user_image->image = $imageName;
            $user_image->save();

            return response()->json(['status'=>"success",'imgdata'=>$original_name,'postid'=>$postid]);
        }
    }

    public function article(Request $request, $pid = null){
        //echo $pid;
        $results = Post::join('users', 'users.id', '=', 'posts.uid')
                        ->select('posts.content', 'posts.id', 'posts.gid', 'users.name', 'posts.created_at')
                        ->where(['posts.id' => base64_decode($pid)])
                        ->first();

        $post = Post::where(['posts.id' => base64_decode($pid)])->first();                
        //dd($post);
        return view('dashboard.user.post')->with(['data' => $results, 'post' => $post]);
    }


    // public function storeimage(Request $request){
    //     //dd($request);
    //     //echo $request->uid; die;
    //     $post_request = [];
    //     $file_request = [];
    //     $post_request['uid']     = $request->uid;
    //     $post_request['content'] = $request->postcontent;
    //     //PersonalPost::create($post_request);


    //     if($post_data = PersonalPost::create($post_request)){
    //         $image = $request->file('file');

    //         $imageName = time().'.'.$image->extension();
    //         $image->move(public_path('images'),$imageName);

    //         $file_request['pid']     = $post_data->id;
    //         $file_request['image']   = $imageName;

    //         PersonalPostImage::create($file_request);
    //     }

    //     return response()->json(['success'=>'success']);

    //     // $image = $request->file('file');

    //     // $imageName = time().'.'.$image->extension();
    //     // $image->move(public_path('images'),$imageName);
   
    //     // return response()->json(['success'=>$imageName]);
    // }
}
