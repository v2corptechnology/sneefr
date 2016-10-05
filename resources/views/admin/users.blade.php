@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="col-md-4">
            @include('admin._sidebar')
        </div>
        <div class="col-md-8">
            <div class="table-responsive">
                <h1 class="content-head">{{ $totals['users'] }} Utilisateurs</h1>
                <h2 class="h6 text-muted">Position (xxx remplies : xxx% de complétion)</h2>
                <div class="content">
                    <table class="table table-hover table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Ajo-Ven-Sup</th>
                                <th>Inscription</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="{{ $user->trashed() ? 'warning' : '' }}">
                                    <td>
                                        <a href="{{ route('profiles.show', $user) }}" title="{{ $user->getEmail() }}">
                                            {!! HTML::profilePicture($user->facebook_id, $user->present()->surname(), 20, ['img-circle']) !!}
                                            {{ $user->present()->fullName() }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('profiles.show', $user) }}">
                                            {{ $user->total_ads.'-'.$user->sold_ads.'-'.$user->deleted_ads }}
                                        </a>
                                    </td>
                                    <td>{!! HTML::time($user->created_at) !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
