@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="col-md-4">
            @include('admin._sidebar')
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <h1 class="content-head">{{ $totals['searches'] }} Recherches</h1>
                        <div class="content">
                            <table class="table table-hover table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th>Recherche</th>
                                    <th>Par</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($searched as $slug => $search)
                                        <tr>
                                            <td>
                                                <a href="{{ route('search.index', ['q' => $search->term]) }}">
                                                    {{ $search->term }}
                                                </a>
                                            </td>
                                            <td>
                                                @if ($search->user)
                                                    <a href="{{ route('profiles.show', $search->user) }}">
                                                        {!! HTML::profilePicture($search->user->facebook_id, $search->user->present()->surname(), 20) !!}
                                                        {{ $search->user->present()->fullName() }}.
                                                    </a>
                                                @else
                                                    Visiteur
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive">
                        <h1 class="content-head">{{ $totals['shared_searches'] }} Recherches partagées</h1>
                        <div class="content">
                            <table class="table table-hover table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th>Recherche</th>
                                    <th>Par</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($shared as $share)
                                        <tr>
                                            <td>
                                                {{ $share->body }}
                                            </td>
                                            <td>
                                                <a href="{{ route('profiles.show', $share->user) }}">
                                                    {!! HTML::profilePicture($share->user->facebook_id, $share->user->present()->surname(), 20) !!}
                                                    {{ $share->user->present()->fullName() }}.
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
