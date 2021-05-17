@extends('general.index', $setup);
@section('thead')
    <th>{{ __('Rating') }}</th>
    <th>{{ __('Comment') }}</th>
    <th>{{ __('Order') }}</th>
    <th>{{ __('User') }}</th>
    <th>{{ __('Actions') }}</th>
@endsection
@section('tbody')
@foreach ($setup['items'] as $item)
<tr>
    <td>{{ $item->rating }}</td>
    <td>{{ $item->comment }}</td>
<td><a href="{{ route('orders.show',['order'=>$item->order->id]) }}">{{ "#".$item->order->id }}</a></td>
    <td>{{ $item->user->name }}</td>
    <td><a href="{{ route("reviews.destroyget",["rating"=>$item->id]) }}" class="btn btn-danger btn-sm">{{ __('Delete') }}</a></td>
</tr> 
@endforeach

@endsection