<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response ;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PaginationRequest;
use App\Models\Post;
use App\Models\Image;
use App\Models\Province;
use App\Models\Category;
use App\Models\Notification;
use Storage;
use Auth;
use Carbon\Carbon;
use BreadcrumbsHelper;
use DB;
use View;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PostRequest $request, BreadcrumbsHelper $bc)
    {
      
      $total = Post::where('state', '=', \Config::get('common.TYPE_POST_ACTIVE'))->count();
      $number = \Config::get('common.NUMBER_ITEM_PER_PAGE');
      
      $crumbs = $bc->getCrumbs($request->path());
      
      // input request parameters
      $q = $request->input('q');
      $address = $request->input('address');
      $type = $request->input('type');
      $order = $request->input('order');
      $category = $request->input('category');
      
      // start checking if page input is null or not
      $page = is_null($request->input('page')) ? 1 : $request->input('page');
      // end checking if page input is null or not
      $offset = 1;
      $offset = ($page - 1) * $number;
      // get posts
      $searchResult = Post::search($q, $address, $type, $category, $order, $total, $offset);
      $totalPages = ceil($searchResult['total'] / $number);
      $posts = $searchResult['posts'];
      $x = new Province();
      
      $data = array(
          'posts'  => $posts,
          'totalPages' => $totalPages,
          'crumbs' => $crumbs,
          'page' => $page,
          'addresses' => $x->getAddress(),
          'q' => $q,
          'address' => $address,
          'type' => $type,
          'category' => $category,
          'order' => $order
      );
      
      return view('frontend.posts.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, BreadcrumbsHelper $bc)
    {
      $crumbs = $bc->getCrumbs($request->path());
      return view('frontend.posts.create', ['crumbs' => $crumbs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request, Category $category)
    {
        // store post
        $user_id = Auth::user()->id;
        $request['user_id'] = $user_id;
        $request['title'] = $request->input('title'). ' ' . strtotime(Carbon::now());
        $request['slug'] = str_slug($request->input('title'), '-');
        $request['description'] = str_replace(' ', '&nbsp;', $request['description']);
        $request['category_id'] = $category->getIdBySlug($request['category']);
        $post = Post::create($request->all());

        // store images
        $images = $request->file('images');
        $post->storeImages($images);
        return redirect()->action('Frontend\PostController@create')->withMessage(trans('common.post.create_successfully'));
    }

    public function getPostImages($filename)
    {
      $file = Storage::disk('local')->get($filename);
      return new Response($file, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, BreadcrumbsHelper $bc)
    {
        $states = array(
            \Config::get('common.TYPE_POST_ACTIVE'),
            \Config::get('common.TYPE_POST_HIDDEN')
        );
        try {
            $crumbs = $bc->getCrumbs($request->path());
            $post = Post::where('slug', '=', $id)->with('images')->firstOrFail();
            $states = array_diff($states, [$post->state]);
            if($request['notification']) {
              $notification = Notification::findOrFail($request['notification']);
              $notification->read_at = Carbon::now();
              $notification->save();
            }
            return view('frontend.posts.show', ['post' => $post, 'crumbs' => $crumbs, 'states' => $states]);
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
        //
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
            return redirect()->route('posts.show',['id' => $post->slug])
                             ->withMessage(trans('common.post.update_successfully'));
        } catch (Exception $modelNotFound) {
            return redirect()->route('posts.show',['id' => $post->slug])->withErrors(trans('common.post.not_found_post'));
        }
        return redirect()->route('posts.show',['id' => $post->slug])->withErrors($errors);
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
