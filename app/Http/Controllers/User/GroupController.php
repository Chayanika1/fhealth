<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\UserGroup;
use App\Models\Post;
use App\Models\PostImage;
use Auth;


class GroupController extends Controller
{
    public function group_create(Request $request){

        if($request->isMethod('POST')){
            $request->validate([
                'groupname'=>'required',
                'groupcontent'=>'required',
                //'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],[
                'groupname.required'=>'The Group Name is required.',
                'groupcontent.required'=>'The Group Content is required.'
            ]);

            $imageName = '';
            if($request->image){
                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('uploads/groups_image'), $imageName);
            }
            

            $grp = new Group();
            $grp->name = $request->groupname;
            $grp->content = $request->groupcontent;
            $grp->image = $imageName;
            $data = $grp->save();
            if($data){
                return redirect()->route('user.group-create')->with('success', '<b>Thank You..</b>Succesfully creating a group.<br>Kindly give us 24 hrs to approved by Admin. You will received a email notification.');
            }else{
                return redirect()->route('user.group-create')->with('error', 'Sorry... Failed');
            }
            //<b>Thank You..</b>Your joining request is sending to Admin<br>Kindly give us 24 hrs to approved. You will received a email notification.
        }

        return view('dashboard.user.group-create');
    }

    public function groupfeed(Request $request, $gid = null){
        $resultFeed = '';
        $userGroupVerified = 'N';
        $uid = Auth::guard('web')->user()->id;
        $groupId = base64_decode($gid); 

        //$groupResult = Group::where(['id' => $gid, 'admin_verify' => 'Y'])->first();
        $groupResult = Group::where(['id' => $groupId])->first();
        //dd($groupResult);
        if($groupResult){
            //if($groupResult->admin_verify == 'Y'){
                $checkVarified = UserGroup::where(['uid' => $uid, 'gid' => $groupId])->first();
                if($checkVarified){
                    if($checkVarified->admin_verify == 'Y'){
                        $resultFeed = Post::where(['gid' => $groupId, 'admin_verify' => 'Y'])->first();
                        $userGroupVerified = 'Y'; 
                    }else{
                        $userGroupVerified = 'R'; //Request Send Pending
                    }
                    
                }else{
                    $userGroupVerified = 'N';
                }
            //}
        }else{
            abort(404);
        }
        

        return view('dashboard.user.group-feed')->with(['groupResult' => $groupResult, 'userGroupVerified' => $userGroupVerified, 'resultFeed' => $resultFeed, 'groupId'=>$groupId, 'uid'=>$uid]);
    }

    public function getgroupArticles(Request $request){

        //dd($request);
        //$uid = Auth::guard('web')->user()->id;

        $results = Post::join('users', 'users.id', '=', 'posts.uid')
                        ->select('posts.content', 'posts.id', 'posts.gid', 'users.name', 'posts.created_at')
                        ->where(['gid' => $request->gid])
                        ->orderBy('posts.created_at', 'desc')
                        ->paginate(5);

        //dd($results);

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

    public function sendJoinGroupRequest(Request $request){
        if(UserGroup::where(['uid' => $request->uid, 'gid' => $request->gid])->count() == 0){
            UserGroup::create(['uid' => $request->uid, 'gid' => $request->gid, 'admin_verify' => 'R']);
            return json_encode(['type' => 'json', 'status' => 'success']);
        }
    }

}
