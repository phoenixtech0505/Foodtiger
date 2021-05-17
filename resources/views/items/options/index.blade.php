@extends('general.index', $setup);
@section('thead')
    <th>{{ __('Name') }}</th>
    <th>{{ __('Options') }}</th>
    <th>{{ __('Actions') }}</th>
@endsection
@section('tbody')
@foreach ($setup['items'] as $item)
<tr>
    <td>{{ $item->name }}</td>
    <td>{{ $item->options }}</td>
    <td><a href="{{ route("items.options.edit",["option"=>$item->id]) }}" class="btn btn-primary btn-sm">{{ __('Edit') }}</a><a href="{{ route("items.options.delete",["option"=>$item->id]) }}" class="btn btn-danger btn-sm">{{ __('Delete') }}</a></td>
</tr> 
@endforeach

@endsection