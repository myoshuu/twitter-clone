<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class FilterController extends Controller
{
  public function filter(Request $request)
  {
    $tag = Tag::where('name', $request->name)->get()->load(['tagTweet', 'tagTweet.tweet', 'tagTweet.tweet.user', 'tagComment', 'tagComment.comment', 'tagComment.comment.user']);
    return $tag;
  }
}
