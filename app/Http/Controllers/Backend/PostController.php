<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Image;
use App\Models\Notification;
use Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $posts = Post::with('user')->orderBy('created_at', 'desc')->get();
        $data = array(
            'posts'  => $posts
        );
        return view('backend.posts.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      try {
          $post = Post::where('id', '=', $id)->with('images', 'user')->firstOrFail();
          $states = array(
              \Config::get('common.TYPE_POST_ACTIVE'), 
              \Config::get('common.TYPE_POST_HIDDEN'), 
              \Config::get('common.TYPE_POST_REJECTED'),
              \Config::get('common.TYPE_POST_WAITING')
          );
          $states = array_diff($states, [$post->state]);
          return view('backend.posts.show', ['post' => $post, 'states' => $states]);
      } catch (NotFoundHttpException $ex) {
          return redirect()->action('PostController@index')
                           ->withErrors(trans('common.post.not_found_post'));
      }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $errors = trans('common.post.update_unsuccessfully');
        try {
            $post = Post::findOrFail($request['id']);
            $post->state = $request['state'];
            $post->save();
            $data = array();
            $data['message'] = 'Bài đăng ' . $post->title . ' của bạn đã được cập nhật thành '. $request['state'];
            $data['approver'] = Auth::user()->avatar;
            $data['url'] = url('/posts').'/'.$post->slug;
            $notification = Notification::create([
                'user_id' => $post->user_id,
                'data' => json_encode($data)
            ]);
            // Announce that a new message has been posted
            event(new \App\Events\PostApprovalEvent($post->user, $post, $notification));
            
            return redirect()->route('admin.post.show',['id' => $request['id']])
                             ->withMessage(trans('common.post.update_successfully'));
        } catch (Exception $modelNotFound) {
            return redirect()->route('admin.post.show',['id' => $request['id']])->withErrors(trans('common.post.delete_unsuccessfully'));
        }
        return redirect()->route('admin.post.show',['id' => $request['id']])->withErrors($errors);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getRejectedPosts(Request $request)
    {
      
      $posts = Post::with(['images'=>function($query) {
                        return $query->limit(1);
                    }])
                    ->Where('state', '=', \Config::get('common.TYPE_POST_REJECTED'))
                    ->orderBy('created_at', 'desc')
                    ->paginate(\Config::get('common.PAGINATION_LIMIT'));
      return view('backend.posts.rejectedPosts', ['posts' => $posts]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getWaitingPosts(Request $request)
    {
      
      $posts = Post::with(['images'=>function($query) {
                        return $query->limit(1);
                    }])
                    ->Where('state', '=', \Config::get('common.TYPE_POST_WAITING'))
                    ->orderBy('created_at', 'desc')
                    ->paginate(\Config::get('common.PAGINATION_LIMIT'));
      return view('backend.posts.waitingPosts', ['posts' => $posts]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getApprovalPosts(Request $request)
    {
      
      $posts = Post::with(['images'=>function($query) {
                        return $query->limit(1);
                    }])
                    ->Where('state', '=', \Config::get('common.TYPE_POST_ACTIVE'))
                    ->orderBy('created_at', 'desc')
                    ->paginate(\Config::get('common.PAGINATION_LIMIT'));
      return view('backend.posts.approvalPosts', ['posts' => $posts]);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $errors = trans('common.post.delete_unsuccessfully');
        try {
            $post = Post::findOrFail($id);
            $post->deleteReferences();
            $post->delete();
            return redirect()->route('admin.post.index')
                             ->withMessage(trans('common.post.delete_successfully'));
        } catch (Exception $modelNotFound) {
            return redirect()->route('admin.post.index')->withErrors($errors);
        }
        return redirect()->route('admin.post.index')->withErrors($errors);
    }
}
