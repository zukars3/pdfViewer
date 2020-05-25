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
            Storage::delete([
                Storage::disk('public')->delete($document->path),
                Storage::disk('public')->delete($document->thumbnail)
            ]);
        });
    }

    public function getDocumentUrl() {
        return Storage::url($this->path);
    }

    public function getThumbnailUrl() {
        return Storage::url($this->thumbnail);
    }
}
