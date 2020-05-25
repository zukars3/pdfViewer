<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = ['name', 'path', 'thumbnail'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($document) {
            Storage::disk('public')->delete([
                substr($document->path, 8),
                substr($document->thumbnail, 8),
            ]);
        });
    }
}
