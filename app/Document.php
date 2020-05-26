<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = ['name', 'path', 'thumbnail'];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function (Document $document) {
            Storage::delete([
                Storage::disk('public')->delete($document->path),
                Storage::disk('public')->delete($document->thumbnail)
            ]);
        });
    }

    public function getDocumentUrl(): string
    {
        return Storage::url($this->path);
    }

    public function getThumbnailUrl(): string
    {
        return Storage::url($this->thumbnail);
    }
}
