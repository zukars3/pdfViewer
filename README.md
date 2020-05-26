# pdfViewer
### PDF file view with upload and thumbnail generation functionality

![GitHub Logo](usage.gif)

##### Change database settings in .env.example and rename it to .env.
##### Please run following commands:
###### composer install
###### php artisan key:generate
###### php artisan storage:link
##### To run server run command:
###### php artisan serve


To create this app I chose to use Laravel because I am more familiar with it.


The app has following functions:

* Add new document
* Automatically generated thumbnail
* Every new document appears at the end of the thumbnails list
* Document name appears when hovering over thumbnail
* Document opens in full screen modal and is in browsers pdf viewer with all the options for pdf files
* Delete a document

The app use MySQL as its database and bootstrap for frontend, the code is PSR-2 valid
and this repository has StyleCI connected to it


Under the hood:

1. Routes
    * GET routes on '/' and '/documents' that opens all documents view (DocumentsController@index)
    * POST route on '/documents' that is used for document upload (DocumentsController@create)
    * DELETE route on '/documents/{document}' that is used for deleting documents (DocumentsController@destroy)
1. DocumentsController
    * ThumbnailGenerationService is passed in constructor, which allows it to be changed at will
    * In index function pagination is set and it returns all documents view with documents array
    * Create function accepts request as its argument, stores the file, creates a Document model
       and asks ThumbnailGeneratorService to generate the thumbnail. After that Document model is
       saved, put in database table 'documents' and the user is redirected to all documents
       view
    * Destroy function accepts Document model as its argument and deletes it, the user once again is
       redirected to all documents view
1. Document model
    * In document model all fillable parameters are set
    * In boot its said to delete associated files when document model is being deleted
    * Functions getDocumentUrl and getThumbnailUrl return URLs for use in blade view
1. ThumbnailGenerationService
    * It accepts Document model and files hash name
    * Both document file and target paths are being declared
    * Using Imagick package thumbnail is being generated and the settings are optimized for best quality
       with less file size
    * This function is made in a way that it loads only the first page of the document which is much faster
       than the most other already made solutions and packages available online
    * Path of thumbnail is returned for further use in DocumentsController
1. Documents view
    * I used bootstrap for styling as well my own styles that are all written at the end of the file
    * To pass the document to modal I used JQuery
    * The upload button is made in a way that if document is selected, its uploaded instantly and there
       are no other inputs necessary from the user
    * Documents array contents are displayed using a for loop
    * On hover over thumbnail documents original name is displayed
    * Pagination is done in DocumentsController using Laravels simplePaginate
    * Document delete function is a form with a button that calls for DocumentsController destroy function
    * Until 1200px screen width thumbnails have their original width
    * Until 992px screen width thumbnails are a little less width than original
    * Until 768px screen width thumbnails are about half of their original width
    * Until 574px screen width thumbnails are wider but only in one column
    * After 574px screen width thumbnails are resized according to screen width
    
