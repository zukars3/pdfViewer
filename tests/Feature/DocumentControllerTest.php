<?php

namespace Tests\Feature;

use App\Document;
use App\Http\Controllers\DocumentsController;
use App\Http\Requests\CreateDocumentRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testFirstIndexRoute()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testSecondIndexRoute()
    {
        $response = $this->get('/documents');

        $response->assertStatus(200);
    }

    public function testDocumentUpload()
    {
        $file = UploadedFile::createFromBase(
            (new \Symfony\Component\HttpFoundation\File\UploadedFile(
                Storage::disk('public')->path('testing/documents/sample-pdf-file.pdf'),
                'sample-pdf-file.pdf',
                'application/pdf'
            ))
        );

        $request = new CreateDocumentRequest();
        $request->files->set('document', $file);

        (new DocumentsController(app('ThumbnailGenerationService')))->create($request);

        $document = Document::where('name', '=', 'sample-pdf-file.pdf')->firstOrFail();

        $this->assertDatabaseHas('documents', [
            'name' => $document->name
        ]);

        Storage::disk('public')->assertExists($document->path);
        Storage::disk('public')->assertExists($document->thumbnail);

        Storage::delete([
            Storage::disk('public')->delete($document->path),
            Storage::disk('public')->delete($document->thumbnail)
        ]);
    }

    public function testDocumentDestroy()
    {
        $file = UploadedFile::createFromBase(
            (new \Symfony\Component\HttpFoundation\File\UploadedFile(
                Storage::disk('public')->path('testing/documents/sample-pdf-file.pdf'),
                'sample-pdf-file.pdf',
                'application/pdf'
            ))
        );

        $request = new CreateDocumentRequest();
        $request->files->set('document', $file);

        $controller = new DocumentsController(app('ThumbnailGenerationService'));
        $controller->create($request);

        $document = Document::where('name', '=', 'sample-pdf-file.pdf')->firstOrFail();

        $controller->destroy($document);

        Storage::disk('public')->assertMissing($document->path);
        Storage::disk('public')->assertMissing($document->thumbnail);
    }
}
