@extends('layouts.master')

@section('title', trans('terms.page_title'))

@section('content')
<div class="container">

    <div class="col-md-8 col-md-offset-2">

        <h1 id="terms">@lang('terms.heading')</h1>

        @foreach(trans('terms.sections') as $sectionKey => $sectionValue)
            <?php $section = 'terms.sections.'.$sectionKey; ?>

            @if (trans($section.'.heading'))
                <h2>@lang($section.'.heading')</h2>
            @endif

            @foreach(trans($section.'.paragraphs') as $paragraphKey => $paragraphValue)
                <?php $paragraph = $section.'.paragraphs.'.$paragraphKey; ?>

                <p>@lang($paragraph)</p>

            @endforeach

        @endforeach

    </div>
</div>
@stop
