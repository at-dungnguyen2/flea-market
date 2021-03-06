<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePasswordRequest;
use BreadcrumbsHelper;
use App\Models\User;
use App\Models\Post;
use Auth;
use Redirect;
use Hash;
use Storage;
use Image as ImageIntervention;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request, BreadcrumbsHelper $bc)
    {
        $crumbs = $bc->getCrumbs($request->path());
        return view('frontend.users.profile', ['crumbs' => $crumbs]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id, BreadcrumbsHelper $bc)
    {
        $crumbs = $bc->getCrumbs($request->path());
        return view('frontend.users.edit', ['crumbs' => $crumbs]);
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
      $image = $request->file('image');
      try {
        if ($image) {
              $user = User::findOrFail(Auth::user()->id);
              
              $path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
              if ($image) {
                  $filename = $user->id.'.'.$image->getClientOriginalExtension();
                  // store images to storage
                  ImageIntervention::make($image)->resize(\Config::get('common.IMAGE_WIDTH'), \Config::get('common.IMAGE_HEIGHT'))
                  ->save($path. \Config::get('common.DIRECTORY_SEPARATOR'). $filename);
                  $user->avatar = url(\Config::get('common.DIRECTORY_SEPARATOR')).\Config::get('common.DIRECTORY_SEPARATOR').'images'.\Config::get('common.DIRECTORY_SEPARATOR').$filename;
                  $user->update();

                  return Redirect::back()->withMessage(trans('common.user.update_avatar_successfully'));
              }

              return Redirect::back()->withErrors(trans('common.user.update_unsuccessfully'));
        } else {
            $request['birthday'] = date("Y-m-d", strtotime($request['birthday']));
            $input = $request->all();
            $user = User::findOrFail(Auth::user()->id);
            $user->fill($input);
            $user->save();
            return Redirect::back()
            ->withMessage(trans('common.post.update_successfully'))
            ->withInput();
        }
      } catch (Exception $saveException) {
         return Redirect::back()->withErrors(trans('users.error_message'));
      }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
      try {
            $user = User::findOrFail(Auth::user()->id);
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = $request->password;
                $user->save();
                return Redirect::back()->withMessage(trans('common.user.change_password_successfully'));
            }
            return Redirect::back()->withErrors(trans('common.user.password_not_match'));
        } catch (Exception $saveException) {
            return Redirect::back()->withErrors(trans('common.error_message'));
        }
    }
    
    /**
     * Update user avatar.
     *
     * @param Request $request hold all data from request
     * @param int     $id      determine specific user
     *
     * @return \Illuminate\Http\Response
     */
    public function updateAvatar(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $image = $request->file('image');
            
            $path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
            if ($image) {
                $filename = $user->id.'.'.$image->getClientOriginalExtension();
                // store images to storage
                ImageIntervention::make($image)->resize(\Config::get('common.IMAGE_WIDTH'), \Config::get('common.IMAGE_HEIGHT'))
                ->save($path. '/'. $url);
                $user->avatar = $filename;
                $user->update();

                return Redirect::back()->withMessage(trans('users.edit.edit_avatar_successful_message'));
            }

            return Redirect::back()->withErrors(trans('users.edit.error_password_incorrect'));
        } catch (Exception $saveException) {
            return Redirect::back()->withErrors(trans('users.error_message'));
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getApprovalPosts(Request $request, BreadcrumbsHelper $bc)
    {
      $crumbs = $bc->getCrumbs($request->path());
      $approvalPosts = Post::getPostsByState(\Config::get('common.TYPE_POST_ACTIVE'));
      $waitingPosts = Post::getPostsByState(\Config::get('common.TYPE_POST_WAITING'));
      $hiddenPosts = Post::getPostsByState(\Config::get('common.TYPE_POST_HIDDEN'));
      $rejectedPosts = Post::getPostsByState(\Config::get('common.TYPE_POST_REJECTED'));
      $data = array(
          'crumbs'  => $crumbs,
          'approvalPosts' => $approvalPosts,
          'waitingPosts' => $waitingPosts,
          'hiddenPosts' => $hiddenPosts,
          'rejectedPosts' => $rejectedPosts
      );
                    
                    
      return view('frontend.users.posts')->with($data);
    }
    
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}