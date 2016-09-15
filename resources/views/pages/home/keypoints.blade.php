<div class="keypoints__container container">
    <div class="keypoint">
        <img src="{{ asset('img/particular_pig.png') }}"
             alt="@lang('login.img_professional')" class="keypoint__image">
        <h1 class="keypoint__heading">@lang('login.particular_heading')</h1>
        <p class="keypoint__text">@lang('login.particular_text')</p>
        <div class="keypoint__cta">
            <a class="btn btn-primary btn-primary2"
               href="{{ route('items.create') }}"
               title="@lang('login.btn_particular_title')">@lang('login.btn_particular')</a>
        </div>
    </div>
    <div class="keypoint">
        <img src="{{ asset('img/pro_pig.png') }}"
             alt="@lang('login.img_professional')" class="keypoint__image">
        <h1 class="keypoint__heading">@lang('login.professional_heading')</h1>
        <p class="keypoint__text">@lang('login.professional_text')</p>
        <div class="keypoint__cta">
            <a class="btn btn-primary btn-primary2 cta__btn"
               href="{{ route('pricing') }}"
               title="@lang('login.btn_professional_title')">@lang('login.btn_professional')</a>
        </div>
    </div>
</div>
