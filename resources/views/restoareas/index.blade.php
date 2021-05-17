@extends('general.index', $setup);
@section('tbody')
@foreach ($setup['items'] as $item)
<tr>
    <td>{{ $item->name }}</td>
    @include('partials.tableactions',$setup)
</tr> 
@endforeach

@endsection