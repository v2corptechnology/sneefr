@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="col-md-4">
            Edit the tags
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
                        <tr>
                            <td>Create tag</td>
                            <td>
                                <form action="{{ route('admin.tools.create') }}" method="POST">
                                    {!! csrf_field() !!}
                                    <div class="row">
                                        <div class="col-md-9"><input type="text" name="title" class="form-control" autocomplete="off"></div>
                                        <div class="col-md-3"><button class="btn btn-primary btn-block" type="submit">Add</button></div>
                                    </div>
                                </form>
                            </td>
                        </tr>
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
                <a name="bottom" id="bottom"></a>
            </div>
        </div>
    </div>
@stop
