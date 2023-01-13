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
    public function index()
    {
        $posts = Post::paginate(10);

        return $this->customResponse('Successfully fetched!', new PostCollection($posts));
    }

    public function store(StoreRequest $request)
    {
        $photo = $this->uploadImage($request->photo);

        auth()->user()->posts()->create([
            'description' => $request->description,
            'photo' => $photo
        ]);

        return $this->customResponse('Successfully uploaded!', [], Response::HTTP_CREATED);
    }

    private function uploadImage(object $photo): string
    {
        $extension = $photo->getClientOriginalExtension();

        $photoName = Auth::id() . rand(0, 100) . time() . '.' . $extension;

        $img = Image::make($photo);

        Storage::disk('s3')->put('photos/'.$photoName, $img->stream());

        Storage::disk('s3')->setVisibility('photos/'.$photoName, 'public');

        $file_url = Storage::disk('s3')->url('photos/'.$photoName);

        return $photoName;
    }

    public function show(Post $post)
    {
        return $this->customResponse('Successfully fetched!', new PostResource($post));
    }

    public function update(UpdateRequest $request, $id)
    {
        $post = Post::where([
            'id' => $id,
            'user_id' => Auth::id()
        ])->first();

        if (!$post) {
            return $this->customResponse('Post not found.', [], Response::HTTP_NOT_FOUND, false);
        }

        $post->update($request->validated());

        return $this->customResponse('Successfully updated!', new PostResource($post));
    }
}
