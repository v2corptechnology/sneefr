@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="col-md-4">
            <ul class="summary">
                <li class="summary__item">
                    <h2 class="summary__head">
                        <i class="fa fa-tag summary__icon"></i>
                        <a href="{{ route('admin.tools') }}">Tags</a>
                    </h2>
                </li>
                <li class="summary__item">
                    <h2 class="summary__head">
                        <i class="fa fa-diamond summary__icon"></i>
                        <a href="{{ route('highlightedShops.index') }}">Highlighted shops</a>
                    </h2>
                </li>
            </ul>
        </div>
        <div class="col-md-8">
            <div class="content">
                <table class="table table-hover table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>url</th>
                            <th>title</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tags as $tag)
                            <tr>
                                <td>/{{ $tag->alias }}</td>
                                <td>
                                    <form action="{{ route('admin.tools.update', $tag->id) }}" method="POST">
                                        {!! csrf_field() !!}
                                        {!! method_field('put') !!}
                                        <div class="row">
                                            <div class="col-md-9"><input type="text" name="title" class="form-control" value="{{ $tag->title }}" autocomplete="off"></div>
                                            <div class="col-md-3"><button class="btn btn-default btn-block" type="submit">Save</button></div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
