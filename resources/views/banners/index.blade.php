@extends('general.index', $setup);
@section('tbody')
    @foreach ($setup['items'] as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->type == 0 ? $item->restaurant->name : $item->page->title}}</td>
            <td>{{ $item->active_from }}</td>
            <td>{{ $item->active_to }}</td>
            @include('partials.tableactions',$setup)
        </tr>
    @endforeach
@endsection
