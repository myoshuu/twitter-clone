<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagTweet extends Model
{
  use HasFactory;
  protected $guarded = [];

  // public function tagTweet()
  // {
  //   return $this->belongsTo(Tag::class,);
  // }

  public function tag()
  {
    return $this->belongsTo(Tag::class, 'tag_id');
  }

  public function tweet()
  {
    return $this->belongsTo(Tweet::class);
  }
}
