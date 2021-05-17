@extends('general.index', $setup);
@section('tbody')
    @foreach ($setup['items'] as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->code }}</td>
            <td>{{ $item->type == 0 ? $item->price." ".config('settings.cashier_currency') : $item->price." %"}}</td>
            <td>{{ $item->active_from }}</td>
            <td>{{ $item->active_to }}</td>
            <td>{{ $item->limit_to_num_uses }}</td>
            <td>{{ $item->used_count }}</td>
            @include('partials.tableactions',$setup)
        </tr>
    @endforeach
@endsection
