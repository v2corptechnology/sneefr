<?php

namespace Sneefr\Composers;

use Illuminate\Http\Request;

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
        $view->with([
            'query'         => $this->request->get('q'),
            'type'          => $this->request->get('type', 'ad'),
        ]);
    }

}
