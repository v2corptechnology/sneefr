<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <a href="/">
                    <img class="img-responsive footer__logo" src="{{ asset('/img/logo-sneefr-negative.svg') }}" alt="Buy from great local trusted shops in your city, all in one place">
                </a>
            </div>
            <div class="col-sm-4 col-md-3">
                <ul class="footer__list">
                    <li><a href="{{ route('privacy') }}">@lang('common.footer.legal_notice')</a></li>
                    <li><a href="{{ route('terms') }}">@lang('common.footer.terms_condition')</a></li>
                </ul>
            </div>
            <div class="col-sm-2">
                <ul class="footer__list">
                    <li><a href="#">@lang('common.footer.contact')</a></li>
                    <li><a href="#">@lang('common.footer.about')</a></li>
                </ul>
            </div>
            <div class="col-sm-2">
                <ul class="footer__list">
                    <li>
                        <a href="{{ route('help') }}" title="@lang('navigation.help_title')">
                            <i class="fa fa-life-ring"></i>
                            @lang('navigation.help')
                        </a>
                    </li>
                    <li><a href="#"><i class="fa fa-facebook-official"></i> Facebook</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
