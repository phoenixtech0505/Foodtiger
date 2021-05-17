@extends('general.index', $setup);
@section('tbody')
    @foreach ($setup['items'] as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->size }}</td>
            <td>{{ $item->restoarea?$item->restoarea->name:"" }}</td>
            @include('partials.tableactions',$setup)
        </tr> 
    @endforeach
@endsection