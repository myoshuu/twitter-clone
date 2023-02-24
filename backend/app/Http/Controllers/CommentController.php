<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tag;
use App\Models\TagComment;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'comment' => 'required',
      'tweet_id' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => 'Invalid input'], 422);
    }

    $tweet = Tweet::find($request->tweet_id);
    if (!$tweet) {
      return response()->json(['message' => 'Tweet not found'], 404);
    }

    $user = auth()->user();

    $pict = $request->file('media');
    if ($pict) {
      $pict_url = $pict->store('uploads');
      $pict->move(public_path('uploads'), $pict_url);
    }

    $comment = $tweet->comment()->create([
      'comment' => $request->comment,
      'user_id' => $user->id,
      'media' => !isset($pict_url) ? null : $pict_url
    ]);

    /**
     * Search for hashtag in tweet
     * separate hashtag from tweet
     * Store hashtag in database
     */
    $strArray = explode(' ', $request->comment);
    foreach ($strArray as $str) {
      if (strpos($str, '#') !== false) {
        $hashTag = array_map('trim', explode('#', $str))[1];

        $tag = Tag::updateOrCreate([
          'name' => $hashTag
        ], []);

        TagComment::create([
          'tag_id' => $tag->id,
          'comment_id' => $comment->id,
        ]);
      }
    }

    return response()->json(['message' => 'Comment posted'], 200);
  }

  /**
   * Display the specified resource.
   */
  public function show(Comment $comment)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update($id, Request $request)
  {
    $validator = Validator::make($request->all(), [
      'comment' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => 'Invalid input'], 401);
    }

    $comment = Comment::find($id);
    TagComment::where(['comment_id' => $id])->delete();

    $updateData =  ["comment" => $request->comment];

    $pict = $request->file('media');
    if ($pict) {
      $pict_url = $pict->store('uploads');
      $pict->move(public_path('uploads'), $pict_url);
      $updateData['media'] = $pict_url;
    }

    $comment->update($updateData);

    $strArray = explode(' ', $request->comment);
    foreach ($strArray as $str) {
      if (strpos($str, '#') !== false) {
        $hashTag = array_map('trim', explode('#', $str))[1];

        $tag = Tag::updateOrCreate([
          'name' => $hashTag
        ], []);

        TagComment::create([
          'tags_id' => $tag->id,
          'comment_id' => $comment->id,
        ]);
      }
    }

    return response()->json(['message' => 'Update success'], 200);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    Comment::find($id)->delete();
    return response()->json(['message' => 'Delete success'], 200);
  }
}
