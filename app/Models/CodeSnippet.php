<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CodeSnippet extends Model{

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function rawSnippets()
    {
        return $this->hasMany(RawSnippet::class)->orderBy('order');
    }
}
