@extends('layouts.app', ['title' => __('Pages')])

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Pages') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('pages.create') }}" class="btn btn-sm btn-primary">{{ __('Add page') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        @include('partials.flash')
                    </div>
                    @if(count($pages))
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Content') }}</th>
                                    <th scope="col">{{ __('Show as link') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($pages as $page)
                                <tr>
                                    <td>{{ $page->title }} </td>
                                    <td><a href="{{ route('pages.edit', $page) }}">{{ __('Click for details') }}</a></td>
                                    <td>
                                        <label class="custom-toggle">
                                            <input type="checkbox" id="showAsLink" class="showAsLink" pageid="{{ $page->id }}" <?php if($page->showAsLink == 1){echo "checked";} ?>>
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label>
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <form action="{{ route('pages.destroy', $page) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this page?") }}') ? this.parentElement.submit() : ''">
                                                        {{ __('Delete') }}
                                                     </button>
                                                </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <div class="card-footer py-4">
                        @if(count($pages))
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $pages->links() }}
                            </nav>
                        @else
                            <h4>{{ __('You don`t have any pages') }} ...</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
