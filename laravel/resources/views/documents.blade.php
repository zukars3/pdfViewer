<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    >
    <meta
        http-equiv="X-UA-Compatible"
        content="ie=edge"
    >
    <script src="{{ asset('js/app.js') }}"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Document View</title>
</head>
<body>

<div class="modal fade" id="modal-document">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span id="modal-close" aria-hidden="true">X</span>
                </button>
                <object id="pdf" type="application/pdf" data="" width="100%" height="100%">
                    Could not open the file
                </object>
            </div>
        </div>
    </div>
</div>

<div class="container">

    <div class="text-center upload">
        <form action="{{ route('documents.create') }}" enctype="multipart/form-data" method="post">
            @csrf
            <input id="custom" type="file" name="document" onchange="this.form.submit()" required>
            <label class="btn btn-success">
                Add new document
                <input
                    type="file"
                    name="document"
                    onchange="this.form.submit()">
            </label>

            @error('document')
            <p class="help">{{ $errors->first('document') }}</p>
            @enderror
        </form>
        <div class="row d-flex justify-content-center">
        {{ $documents->links() }}
        </div>
    </div>

    <div class="row">
        @for($i = 0; $i < count($documents); $i++)
            <div class="col-md-3">
                <div class="document">
                    <a href="{{ $documents[$i]->path }}"
                       data-toggle="modal"
                       data-target="#modal-document"
                       data-path="{{ $documents[$i]->path }}"
                    >
                        <img
                            src="{{ $documents[$i]->thumbnail }}"
                            alt="Image of the first page of {{ $documents[$i]->name }}"
                        >
                    </a>
                    <form class="test-form" action="{{ route('documents.destroy', $documents[$i]) }}" method="post">
                        @csrf
                        @method('delete')
                        <button class="btn btn-responsive btn-danger" type="submit">X</button>
                    </form>
                    <div class="overlay">
                        <div class="text">{{ $documents[$i]->name }}</div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
    <div class="row d-flex justify-content-center">
        {{ $documents->links() }}
    </div>
</div>
</body>
</html>

<style>

    body {
        background-image: url("storage/files/background.jpg");
    }

    .text-center.upload input {
        display: none
    }

    .text-center .btn {
        border: 2px solid gray;
        color: gray;
        background-color: white;
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 20px;
        font-weight: bold;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .col-md-3 {
        padding-bottom: 20px;
    }

    .document {
        position: relative;
    }

    .document:before {
        position: absolute;
        width: 100%;
        top: 0;
        left: 0;
    }

    .document img {
        border-radius: 10px;
        display: block;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        width: 100%;
        height: 340px;
    }

    .overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.7);
        overflow: hidden;
        width: 100%;
        height: 0;
        transition: .5s ease;
        border-bottom-left-radius: 9px;
        border-bottom-right-radius: 9px;
    }

    .overlay .text {
        color: white;
        font-size: 14px;
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .document button {
        position: absolute;
        top: 5px;
        left: 10px;
        opacity: 0;
        transition: visibility 0s, opacity 0.5s linear;
        padding: 5px 9px;
        font-size: 10px;
        border-radius: 4px;
    }

    .document:hover button {
        opacity: 1;
    }

    .document:hover .overlay {
        height: 20%;
    }

    .modal-dialog {
        max-width: none;
        width: 100%;
        height: 100%;
        padding: 0;
        margin: 0;
    }

    .modal-content {
        height: 92%;
    }

    .modal-body object {
        height: 95%;
    }

</style>

<script type="text/javascript">
    $(document).ready(function () {
        $('#modal-document').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var recipient = button.data('path')
            var modal = $(this)
            modal.find('#pdf').attr('data', recipient)
        })
    });
</script>
