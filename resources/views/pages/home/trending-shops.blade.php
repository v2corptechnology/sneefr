@foreach($highlights->chunk(6) as $shops)

    <?php $heading = cache()->get('highlighted_shops_headings', collect())->get($loop->index); ?>

    @if ($heading)

        <div class="col-sm-12">
            <h2 class="section-title">

                {{ $heading['title'] }}

                <span class="section-title__extra">
                    {{ $heading['description']  }}
                </span>

            </h2>
        </div>

        @foreach($shops as $shop)
            <div class="col-sm-4">

                <?php $classes = $loop->remaining < ($loop->count / 2) ? 'hidden-xs' : null; ?>

                @include('shops.card', ['shop' => $shop, 'coverSize' => '410x200', 'classes' => $classes])

            </div>
        @endforeach
    @endif

@endforeach
