<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Comment\StoreRequest;

class CommentController extends Controller
{
    public function store(StoreRequest $request)
    {
        dd("store");
    }
}
