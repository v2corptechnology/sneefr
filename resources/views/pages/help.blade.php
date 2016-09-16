@extends('layouts.master')

@section('title', trans('help.page_title'))

@section('content')
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="page-header">
                <h1>@lang('help.header_big')</h1>
                <p class="lead">@lang('help.we_are_transparent')</p>
                <p><a class="btn btn-facebook btn-lg" href="https://www.facebook.com/SneefRusa" role="button" title="@lang('help.ask_button_title')"><i class="fa fa-facebook"></i> @lang('help.ask_button')</a></p>
            </div>
        </div>

        <div class="col-md-8 col-md-offset-2">

            <h1 id="faq">FAQ</h1>

            <div class="row">
                @foreach(trans('help.sections') as $sectionKey => $sectionValue)
                    <?php $section = 'help.sections.'.$sectionKey; ?>

                    <div class="col-md-6">
                        <h4>@lang($section.'.heading')</h4>

                        @foreach(trans($section.'.chapters') as $chapterKey => $chapterValue)
                            <?php $chapter = $section.'.chapters.'.$chapterKey; ?>

                            <h5>@lang($chapter.'.heading')</h5>

                            <ul>
                                @foreach(trans($chapter.'.questions') as $questionKey => $questionValue)
                                    <?php $question = $chapter.'.questions.'.$questionKey; ?>

                                    <li>
                                        <a href="#{{ str_slug(trans($question.'.question')) }}">@lang($question.'.question')</a>
                                    </li>

                                @endforeach
                            </ul>

                        @endforeach
                    </div>
                @endforeach
            </div>

        </div>

        <div class="col-md-8 col-md-offset-2">

            @foreach(trans('help.sections') as $sectionKey => $sectionValue)
                <?php $section = 'help.sections.'.$sectionKey; ?>

                <h2>@lang($section.'.heading')</h2>

                @foreach(trans($section.'.chapters') as $chapterKey => $chapterValue)
                    <?php $chapter = $section.'.chapters.'.$chapterKey; ?>

                    <h3>@lang($chapter.'.heading')</h3>

                    <dl>
                        @foreach(trans($chapter.'.questions') as $questionKey => $questionValue)
                            <?php $question = $chapter.'.questions.'.$questionKey; ?>

                            <dt id="{{ str_slug(trans($question.'.question')) }}">- @lang($question.'.question')</dt>
                            <dd>
                                <p>@lang($question.'.answer')</p>
                                <p class="link-to-top"><a href="#faq">Back to top</a></p>
                            </dd>

                        @endforeach
                    </dl>

                @endforeach

            @endforeach

        </div>

        <div class="col-md-8 col-md-offset-2">

            <h1 id="{{ str_slug(trans('faq_pro.heading')) }}">@lang('faq_pro.heading')</h1>

            <div class="row">
                @foreach(trans('faq_pro.sections') as $sectionKey => $sectionValue)
                    <?php $section = 'faq_pro.sections.'.$sectionKey; ?>

                    <div class="col-md-6">
                        <h4>@lang($section.'.heading')</h4>

                        <ul>
                            @foreach(trans($section.'.questions') as $questionKey => $questionValue)
                                <?php $question = $section.'.questions.'.$questionKey; ?>

                                <li>
                                    <a href="#{{ str_slug(trans($question.'.question')) }}">@lang($question.'.question')</a>
                                </li>

                            @endforeach
                        </ul>

                    </div>
                @endforeach
            </div>

        </div>
        <div class="col-md-8 col-md-offset-2">

            @foreach(trans('faq_pro.sections') as $sectionKey => $sectionValue)
                <?php $section = 'faq_pro.sections.'.$sectionKey; ?>

                <h4>@lang($section.'.heading')</h4>

                <dl>
                    @foreach(trans($section.'.questions') as $questionKey => $questionValue)
                        <?php $question = $section.'.questions.'.$questionKey; ?>

                        <dt id="{{ str_slug(trans($question.'.question')) }}">- @lang($question.'.question')</dt>
                        <dd>
                            <p>@lang($question.'.answer')</p>
                            <p class="link-to-top"><a href="#{{ str_slug(trans('faq_pro.heading')) }}">Back to top</a></p>
                        </dd>

                    @endforeach
                </dl>

            @endforeach

        </div>
    </div>
@stop
