<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagComment extends Model
{
  use HasFactory;
  protected $guarded = [];

  public function tag()
  {
    return $this->belongsTo(Tag::class);
  }

  public function comment()
  {
    return $this->belongsTo(Comment::class);
  }
}
