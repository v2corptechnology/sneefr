@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="col-md-4">
            @include('admin._sidebar')
        </div>
        <div class="col-md-8">
            <div class="row">
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
                                                {!! HTML::profilePicture($search->user->facebook_id, $search->user->present()->surname(), 20) !!}
                                                {{ $search->user->present()->fullName() }}.
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
        </div>
    </div>
@stop
