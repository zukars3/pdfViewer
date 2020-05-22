<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Imagick;

class DocumentsController extends Controller
{
    public function index()
    {
        $documents = Document::simplePaginate(20);
        return view('documents')->with('documents', $documents);
    }

    public function show(Document $document)
    {
        return redirect($document->path);
    }

    public function create(Request $request)
    {
        request()->validate([
            'document' => 'required|mimes:pdf|max:12000'
        ]);

        $fileName = $request->file('document')->getClientOriginalName();
        $hashName = substr($request->file('document')->hashName(), 0, -3);

        request()->file('document')->store('public/files/documents');

        Document::create([
            'name' => $fileName,
            'path' => 'storage/files/documents/' . $hashName . 'pdf',
            'thumbnail' => 'storage/files/images/' . $hashName . 'jpg'
        ]);

        $this->generateThumbnail(
            'storage/files/documents/' . $hashName . 'pdf',
            '/../images/' . $hashName . 'jpg'
        );

        return redirect(route('documents.index'));
    }

    public function destroy(Document $document)
    {
        $document->delete();
        Storage::disk('public')->delete([
            substr($document->path, 8),
            substr($document->thumbnail, 8)
        ]);

        return redirect(route('documents.index'));
    }

    public function generateThumbnail($source, $target, $size = 256)
    {
        if (file_exists($source) && !is_dir($source)) {
            if (mime_content_type($source) != 'application/pdf') {
                return false;
            }

            $target = dirname($source) . '/' . $target;
            $page = 0;

            $image = new Imagick();
            $image->setResolution(180, 180);
            $image->readImage($source . "[$page]");
            $image->setImageFormat('jpeg');
            $image->setImageCompression(imagick::COMPRESSION_JPEG);
            $image->setImageCompressionQuality(0);
            $image->setImageBackgroundColor('white');
            $image->setImageAlphaChannel(11);
            $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

            if (!is_dir(dirname($target))) {
                mkdir(dirname($target), 0777, true);
            }

            $image->writeimage($target);
            $image->clear();
            $image->destroy();

        }
        return false;
    }
}
