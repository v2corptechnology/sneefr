<div class="col-sm-12">
    <h2 class="section-title">
        @lang('home.highlighted_shops')

        <span class="section-title__extra">
            @lang('home.highlighted_description')
        </span>
    </h2>
</div>

@foreach($shops as $shop)
    <div class="col-sm-4">

        <?php $classes = $loop->remaining < ($loop->count / 2) ? 'hidden-xs' : null; ?>

        @include('shops.card', ['shop' => $shop, 'coverSize' => '410x200', 'classes' => $classes])

    </div>
@endforeach
