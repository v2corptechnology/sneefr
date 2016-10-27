@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="col-md-4">
            @include('admin._sidebar')
        </div>
        <div class="col-md-8">
            <div class="table-responsive">
                <h1 class="content-head">{{ $totals['discussions'] }} Discussions</h1>
                <h2 class="h6 text-muted">Stats....</h2>
                <div class="content">
                    <table class="table table-hover table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Entre...</th>
                                <th>et...</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($discussions as $discussion)
                                <tr>
                                    <td>
                                        {!! HTML::profilePicture($discussion->participants->first()->facebook_id, $discussion->participants->first()->present()->surname(), 20) !!}
                                        {{ $discussion->participants->first()->present()->fullName() }}
                                    </td>
                                    <td>
                                        {!! HTML::profilePicture($discussion->participants->last()->facebook_id, $discussion->participants->last()->present()->surname(), 20) !!}
                                        {{ $discussion->participants->last()->present()->fullName() }}
                                    </td>
                                    <td>{!! HTML::time($discussion->created_at) !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <h2 class="h6">Comptes stripe à faire</h2>
            </div>
        </div>
    </div>
@stop
