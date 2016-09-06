<div class="messages-container js-messages-container"
     id="js-discussion-{{ $chosenDiscussion->id() }}-content"
     data-discussion-id="{{ $chosenDiscussion->id() }}">

    {{-- Navbar/tools for this discussion --}}
    @include('discussions._navbar', [
        'userAds' => auth()->user()->ads,
        'recipient' => $chosenDiscussion->recipient(),
        'currentDiscussion' => $chosenDiscussion
    ])

    {{-- Mesages and ads for the discussion --}}
    <div class="messages">
        <ul class="media-list">

            @foreach($chosenDiscussion->contents() as $item)
                @if ($item instanceof \Sneefr\Models\Message)

                    <?php $sender = ($item->from_user_id === auth()->id())
                            ? auth()->user()
                            : $chosenDiscussion->recipient(); ?>

                    @include('discussions._message', ['message' => $item, 'sender' => $sender, 'shop' => $chosenDiscussion->shop])

                @else

                    @include('discussions._ad', [
                        'currentDiscussion' => $chosenDiscussion,
                        'ad' => $item,
                        'loggedUser' => auth()->user(),
                        'correspondent' => $chosenDiscussion->recipient()
                    ])

                @endif
            @endforeach

            {{-- Display a warning when the recipient has deleted his account --}}
            @if ($chosenDiscussion->recipient()->trashed())
                <li class="media media--separated">
                    @lang('message.user.deleted', ['user' => $chosenDiscussion->recipient()->present()->givenName()])
                </li>
            @endif

            {{-- This is only an anchor to the very bottom of the discussion--}}
            <li id="latest" class="js-latest" name="latest"></li>
        </ul>
    </div>
</div>
