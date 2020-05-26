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


Under the hood:

1. Routes
    1. GET routes on '/' and '/documents' that opens all documents view (DocumentsController@index)
    1. POST route on '/documents' that is used for document upload (DocumentsController@create)
    1. DELETE route on '/documents/{document}' that is used for deleting documents (DocumentsController@destroy)
1. DocumentsController
    1. ThumbnailGenerationService is passed in constructor, which allows it to be changed at will
    1. In index function pagination is set and it returns all documents view with documents array
    1. Create function accepts request as its argument, stores the file, creates a Document model
       and asks ThumbnailGeneratorService to generate the thumbnail. After that Document model is
       saved, put in database table 'documents' and the user is redirected to all documents
       view
    1. Destroy function accepts Document model as its argument and deletes it, the user once again is
       redirected to all documents view
1. Document model
    1. In document model all fillable parameters are set
    1. In boot its said to delete associated files when document model is being deleted
    1. Functions getDocumentUrl and getThumbnailUrl return URLs for use in blade view
1. ThumbnailGenerationService
    1. It accepts Document model and files hash name
    1. Both document file and target paths are being declared
    1. Using Imagick package thumbnail is being generated and the settings are optimized for best quality
       with less file size
    1. This function is made in a way that it loads only the first page of the document which is much faster
       than the most other already made solutions and packages available online
    1. Path of thumbnail is returned for further use in DocumentsController
1. Documents view
    1. I used bootstrap for styling as well my own styles that are all written at the end of the file
    1. To pass the document to modal I used JQuery
    1. The upload button is made in a way that if document is selected, its uploaded instantly and there
       are no other inputs necessary from the user
    1. Documents array contents are displayed using a for loop
    1. On hover over thumbnail documents original name is displayed
    1. Pagination is done in DocumentsController using Laravels simplePaginate
    1. Document delete function is a form with a button that calls for DocumentsController destroy function
    1. Until 1200px screen width thumbnails have their original width
    1. Until 992px screen width thumbnails are a little less width than original
    1. Until 768px screen width thumbnails are about half of their original width
    1. Until 574px screen width thumbnails are wider but only in one column
    1. After 574px screen width thumbnails are resized according to screen width
    
