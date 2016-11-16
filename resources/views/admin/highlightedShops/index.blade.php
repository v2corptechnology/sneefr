@extends('layouts.master')

@push('footer-js')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">
        $('#js-shop').select2();
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="col-md-4">
            @include('admin._tools_sidebar')
        </div>
        <div class="col-md-8">
            <div class="content">
                <table class="table table-hover table-striped table-condensed">
                    <thead>
                        <tr>
                            <th width="75%">Shop</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($shops->count() < config('sneefr.home_featured_items'))
                            <tr>
                                <td colspan="2">
                                    <div class="row">
                                        <form action="{{ route('highlightedShops.store') }}" method="POST">
                                            {!! csrf_field() !!}
                                            <div class="col-sm-10">
                                                {!! Form::select('shop_id', \Sneefr\Models\Shop::all()->each(function($shop){
                                                    $shop['title'] = $shop->getName();
                                                })->pluck('title', 'id'), null, [
                                                    'class' => 'form-control',
                                                    'id' => 'js-shop',
                                                    'autocomplete' => 'off',
                                                    'required',
                                                ]) !!}
                                            </div>
                                            <div class="col-sm-2 text-right">
                                                <input type="submit" value="Add" class="btn btn-primary">
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        @foreach ($shops as $shop)
                            <tr>
                                <td>{{ $shop->getName() }}</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <form action="{{ route('highlightedShops.update', $shop->id) }}" method="POST">
                                                {!! csrf_field() !!}
                                                {!! method_field('patch') !!}
                                                @unless ($loop->first)
                                                    <input type="hidden" name="direction" value="-1">
                                                    <button class="btn btn-default" type="submit">↑</button>
                                                @endunless
                                            </form>
                                        </div>
                                        <div class="col-md-4">
                                            <form action="{{ route('highlightedShops.update', $shop->id) }}" method="POST">
                                                {!! csrf_field() !!}
                                                {!! method_field('patch') !!}
                                                @unless ($loop->last)
                                                    <input type="hidden" name="direction" value="+1">
                                                    <button class="btn btn-default" type="submit">↓</button>
                                                @endunless
                                            </form>
                                        </div>
                                        <div class="col-md-4">
                                            <form action="{{ route('highlightedShops.destroy', $shop->id) }}" method="POST">
                                                {!! csrf_field() !!}
                                                {!! method_field('delete') !!}
                                                <button class="btn btn-danger" type="submit">-</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
