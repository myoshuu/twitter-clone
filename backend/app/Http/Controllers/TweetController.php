<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tag;
use App\Models\TagTweet;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TweetController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return Tweet::all()->sortByDesc('created_at')->load(['user', 'tagTweet', 'tagTweet.tag', 'comment', 'comment.tagComment.tag', 'comment.user'])->values();
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'tweet' => 'required|string',
      'media' => 'image|nullable'
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => 'Invalid input'], 422);
    }

    $user = auth()->user();

    $pict = $request->file('media');
    if ($pict) {
      $pict_url = $pict->store('uploads');
      $pict->move(public_path('uploads'), $pict_url);
    }

    $tweet = Tweet::create([
      'tweet' => $request->tweet,
      'user_id' => $user->id,
      'media' => !isset($pict_url) ? null : $pict_url
    ]);

    /**
     * Search for hashtag in tweet
     * separate hashtag from tweet
     * Store hashtag in database
     */
    $strArray = explode(' ', $request->tweet);
    foreach ($strArray as $str) {
      if (strpos($str, '#') !== false) {
        $hashTag = array_map('trim', explode('#', $str))[1];

        $tag = Tag::updateOrCreate([
          'name' => $hashTag
        ], []);

        TagTweet::create([
          'tag_id' => $tag->id,
          'tweet_id' => $tweet->id,
        ]);
      }
    }

    return response()->json(['message' => 'Tweet success', 'tweet' => $tweet], 200);
  }

  /**
   * Display the specified resource.
   */
  public function show(Tweet $tweet)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update($id, Request $request)
  {
    $validator = Validator::make($request->all(), [ // validating user input (strict form name and data type)
      'tweet' => 'required|string',
      'media' => 'image|nullable'
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => 'Invalid input'], 422); // if validator fails (tidak sesuai dengan rules)
    }

    $user = auth()->user();

    $updateData = [
      'tweet' => $request->tweet,
      'user_id' => $user->id
    ];

    $pict = $request->file('media');
    if ($pict) {
      $pict_url = $pict->store('uploads');
      $pict->move(public_path('uploads'), $pict_url);
      $updateData['media'] = $pict_url;
    }

    $tweet = Tweet::find($id);
    $tweet->update($updateData);

    TagTweet::where(['tweet_id' => $tweet->id])->delete();

    /**
     * Search for hashtag in tweet
     * separate hashtag from tweet
     * Store hashtag in database
     */
    $strArray = explode(' ', $request->tweet);
    foreach ($strArray as $str) {
      if (strpos($str, '#') !== false) {
        $hashTag = array_map('trim', explode('#', $str))[1];

        $tag = Tag::updateOrCreate([
          'name' => $hashTag
        ], []);

        TagTweet::create([
          'tag_id' => $tag->id,
          'tweet_id' => $tweet->id,
        ]);
      }
    }

    return response()->json(['message' => 'Update tweet success'], 200);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $tweet = Tweet::find($id);

    TagTweet::where(['tweet_id' => $tweet->id])->delete();
    $tweet->delete();
    Comment::where(['tweet_id' => $tweet->id])->delete();

    return response()->json(['message' => 'Delete tweet success'], 200);
  }
}
