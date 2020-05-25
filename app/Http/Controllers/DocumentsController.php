<?php

namespace App\Http\Controllers;

use App\Document;
use App\Http\Requests\CreateDocumentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Imagick;

class DocumentsController extends Controller
{
    public function index(): View
    {
        $documents = Document::simplePaginate(20);

        return view('documents')->with('documents', $documents);
    }

    public function create(CreateDocumentRequest $request): RedirectResponse
    {
        $fileName = $request->file('document')->getClientOriginalName();
        $hashName = substr($request->file('document')->hashName(), 0, -3);

        $documentPath = Storage::url('files/documents/' . $hashName . 'pdf');
        $thumbnailPath = Storage::url('files/images/' . $hashName . 'jpg');

        //Storage::put('files/documents', 'document');

        //dd($documentPath);

        $request->file('document')->store('files/documents', 'public');

        Document::create([
            'name' => $fileName,
            'path' => $documentPath,
            'thumbnail' => $thumbnailPath
        ]);

        app('ThumbnailGenerationService')->generateThumbnail(
            substr($documentPath, 1),
            substr($thumbnailPath, 1)
        );

        return redirect(route('documents.index'));
    }

    public function destroy(Document $document): RedirectResponse
    {
        $document->delete();

        return redirect(route('documents.index'));
    }

    public function generateThumbnail(string $source, string $target): bool
    {
        if (Storage::exists($source)) {
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

            return true;
        }

        return false;
    }
}
