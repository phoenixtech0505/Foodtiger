@extends('layouts.app', ['title' => __('Page')])

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">{{ __('Page Management') }}</h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('pages.index') }}" class="btn btn-sm btn-primary">{{ __('Back to pages') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="heading-small text-muted mb-4">{{ __('Page information') }}</h6>
                    @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="pl-lg-4">
                        <form method="post" action="{{ route('pages.update', $page) }}" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="title">{{ __('Title') }}</label>
                                <input type="text" name="title" id="title" class="form-control form-control-alternative{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title here ...') }}" value="{{ old('title', $page->title) }}" required autofocus>
                                @if ($errors->has('title'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="ckeditor">{{ __('Content') }}</label>
                                <textarea class="form-control" id="ckeditor" name="ckeditor">{{ $page->content }}</textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footers.auth')
</div>
@endsection

@section('js')
    <!-- CKEditor -->
    <script src="{{ asset('ckeditor') }}/ckeditor.js"></script>
    <script>
        "use strict";
        CKEDITOR.replace('ckeditor', {
            removePlugins: 'sourcearea',
            filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
            //allowedContent: 'p h1{text-align}; a[!href]; strong em; p(tip)'
        });
    </script>
@endsection
