<?php

namespace Sneefr\Composers;

use Illuminate\Http\Request;
use Sneefr\Models\Message;
use Sneefr\Models\Notification;

class NotificationComposer
{
    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Construct
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Compose
     *
     * @param View
     */
    public function compose($view)
    {
        if (auth()->check()) {

            $unreadMessages = Message::where('to_user_id', auth()->id())->unread()->with('discussion')->get();

            if (auth()->user()->shop) {
                $unreadShopDiscussions = $unreadMessages->filter(function ($message) {
                    return $message->discussion->isShopDiscussion();
                })->groupBy('discussion_id')->count();
            }

            $unreadPersonnalDiscussions = $unreadMessages->reject(function ($message) {
                return $message->discussion->isShopDiscussion();
            })->groupBy('discussion_id')->count();

            $notifications = Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
        }

        $view->with([
            'unread'        => $unreadPersonnalDiscussions ?? null,
            'unreadShop'    => $unreadShopDiscussions ?? null,
            'notifications' => $notifications ?? null,
            'query'         => $this->request->get('q'),
            'type'          => $this->request->get('type', 'ad'),
        ]);
    }

}
