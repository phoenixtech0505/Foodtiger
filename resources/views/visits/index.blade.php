@extends('general.index', $setup);
@section('tbody')
    @foreach ($setup['items'] as $item)
        <tr>
            <td>{{ $item->table?$item->table->name:"" }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->phone_number }}</td>
            <td>{{ $item->note }}</td>
            <td>{{ $item->by=="1"?__('customers.by_restaurant'):__('customers.him_self') }}</td>
            <td>{{ $item->created_at }}</td>
            @include('partials.tableactions',$setup)
        </tr> 
    @endforeach
@endsection