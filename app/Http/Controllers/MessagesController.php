<?php

namespace Sneefr\Http\Controllers;

use Sneefr\Events\MessageWasPosted;
use Sneefr\Http\Requests\StoreMessageRequest;
use Sneefr\Models\Ad;
use Sneefr\Models\Message;

class MessagesController extends Controller
{
    /**
     * @param \Sneefr\Models\Ad                         $ad
     * @param \Sneefr\Http\Requests\StoreMessageRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Ad $ad, StoreMessageRequest $request)
    {
        $message = new Message($request->all());
        $message->ad_id = $ad->getId();
        $message->save();

        event(new MessageWasPosted($message, auth()->user()));

        return redirect()->route('items.show', $ad)
            ->with('success', 'Great, your message has been sent!');
    }
}
