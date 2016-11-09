@extends('layouts.master')

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
                            <th width="40%">User</th>
                            <th width="40%">Shop</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($claims as $claim)
                            <tr>
                                <td title="{{ $claim->user->getLocation() }}">
                                    {{ $claim->user->present()->fullName() }}
                                    <br>
                                    <small>
                                        {{ $claim->user->email }}
                                        {{ $claim->user->phone->getNumber() ? ' — '. $claim->user->phone->getNumber() : null }}
                                    </small>
                                </td>
                                <td title="{{ $claim->shop->getLocation() }}">
                                    <a href="{{ route('shops.show', $claim->shop) }}">{{ $claim->shop->getName() }}</a>
                                    <br>
                                    <small>{{ $claim->shop->getDescription() }}</small>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-default btn-sm">Approve</button>
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
