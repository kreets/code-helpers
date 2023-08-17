<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class RawSnippet extends Model{

    protected $fillable = ['instruction', 'raw', 'order'];

    protected $casts = [
        'order' => 'integer',
    ];

    public function codeSnippet()
    {
        return $this->belongsTo(CodeSnippet::class);
    }
}
