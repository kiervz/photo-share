<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Post\PostCollection;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;

use App\Models\Post;

use Illuminate\Support\Facades\Storage;
use Auth;
use Image;

class PostController extends Controller
{
    public function __construct()
    {
        if (auth('sanctum')->check()) $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        /** Ordering the data. Default value is 'latest' */
        $sort = $request->sort ?? 'latest';
        /** Sequence pf data. Default value is '10' */
        $paginate = $request->paginate ?? 10;

        /**
         * Get all posts with comments and vote from logged in users
         * in order to bind the color of upvote and downvote in the front end.
         */
        $posts = Post::with(['comments', 'votes' => function($query) {
            $query->where('user_id', auth()->id());
        }]);

        /**
         * Set Sort based on user preference
         */
        if ($sort === 'latest') {
            $posts = $posts->latest();
        } else if ($sort === 'oldest') {
            $posts = $posts->oldest();
        } else if ($sort === 'highest-votes') {
            $posts = $posts->orderBy('total_votes', 'DESC');
        } else if ($sort === 'lowest-votes') {
            $posts = $posts->orderBy('total_votes', 'ASC');
        }

        $posts = $posts->paginate($paginate);

        return $this->customResponse('Successfully fetched!', new PostCollection($posts));
    }

    /**
     * Store a newly created Post
     *
     * @param  App\Http\Requests\Post\StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        /** Upload an image to AWS S3 and return the image's name. */
        $photo = $this->uploadImageToS3($request->photo);

        auth()->user()->posts()->create([
            'description' => $request->description,
            'photo' => $photo
        ]);

        return $this->customResponse('Successfully uploaded!', [], Response::HTTP_CREATED);
    }

    /**
     * Upload image file to AWS S3 Bucket
     *
     * @param  object $photo The image uploaded by the user
     * @return String
     */
    private function uploadImageToS3(object $photo): string
    {
        /** Get extension file name */
        $extension = $photo->getClientOriginalExtension();

        /** Generate photo name base on random number and timestamp. */
        $photoName = Auth::id() . rand(0, 100) . time() . '.' . $extension;

        /** Create a new image instance from photo object.  */
        $img = Image::make($photo);

        /**  Save image to the photos folder on AWS S3. */
        Storage::disk('s3')->put('photos/'.$photoName, $img->stream());

        /** Set the image file's visibility to public. */
        Storage::disk('s3')->setVisibility('photos/'.$photoName, 'public');

        /** Return the photo name */
        return $photoName;
    }

    /**
     * Display the specified Post
     *
     * @param  int $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /**
         * Get a specific post with comments and vote from logged in users
         * in order to bind the color of upvote and downvote in the front end.
         */
        $post = Post::where('id', $id)->with(['comments', 'votes' => function($query) {
            $query->where('user_id', auth()->id());
        }])->first();

        return $this->customResponse('Successfully fetched!', new PostResource($post));
    }

    /**
     * Update the specified Post
     *
     * @param  App\Http\Requests\Post\UpdateRequest $request
     * @param  App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Post $post)
    {
        /**
         * Return 404 Page Not Found if the Post's user_id
         * is not equal to logged in user's id.
         */
        if ($post->user_id != Auth::id()) {
            return $this->customResponse('Post not found.', [], Response::HTTP_NOT_FOUND, false);
        }

        $post->update($request->validated());

        return $this->customResponse('Successfully updated!', new PostResource($post));
    }

    /**
     * Remove the specified Post
     *
     * @param  App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        /**
         * Return 404 Page Not Found if the Post's user_id
         * is not equal to logged in user's id.
         */
        if ($post->user_id != Auth::id()) {
            return $this->customResponse('Post not found.', [], Response::HTTP_NOT_FOUND, false);
        }

        $post->delete();

        return $this->customResponse('Successfully deleted!', [], Response::HTTP_NO_CONTENT);
    }
}
