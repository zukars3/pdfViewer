<?php

namespace App\Http\Controllers;

use App\Document;
use App\Http\Requests\CreateDocumentRequest;
use App\ThumbnailGenerationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Imagick;

class DocumentsController extends Controller
{

    private ThumbnailGenerationService $thumbnailGenerationService;

    public function __construct(ThumbnailGenerationService $thumbnailGenerationService)
    {
        $this->thumbnailGenerationService = $thumbnailGenerationService;
    }

    public function index(): View
    {
        $documents = Document::simplePaginate(20);

        return view('documents')->with('documents', $documents);
    }

    public function create(CreateDocumentRequest $request): RedirectResponse
    {
        $fileName = $request->file('document')->getClientOriginalName();
        $hashName = substr($request->file('document')->hashName(), 0, -3);

        $documentPath = $request->file('document')->store('files/documents', 'public');

        $document = new Document([
            'name' => $fileName,
            'path' => $documentPath,
        ]);

        $thumbnailPath = $this->thumbnailGenerationService->generate($document, $hashName);

        $document->thumbnail = $thumbnailPath;

        $document->save();

        return redirect(route('documents.index'));
    }

    public function destroy(Document $document): RedirectResponse
    {
        $document->delete();

        return redirect(route('documents.index'));
    }
}
