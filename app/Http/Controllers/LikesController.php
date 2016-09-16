<?php namespace Sneefr\Http\Controllers;

use Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Sneefr\Jobs\Notify;
use Sneefr\Models\Place;

class LikesController extends Controller
{
    /**
     * Create a like.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|array
     */
    public function store(Request $request)
    {
        $model = $this->buildObjectFromPayload($request);

        // If the model is already liked, remove the like
        if ($model->liked()) {
            $model->unlike();
        } else {
            $like = $model->like();

            // Don't trigger notification on a followed place
            if (! $model instanceof Place) {
                // Notification only happens on Like creation to avoid spamming
                $this->dispatch(new Notify($like));
            }
        }

        return $request->ajax() ? ['success'] : back();
    }

    /**
     * Decrypt the encrypted payload of a request.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function buildObjectFromPayload(Request $request) : Model
    {
        $payload = $request->input('payload');

        $serialized = Crypt::decrypt($payload);

        $data = unserialize($serialized);

        return (new $data['classname'])->find($data['id']);
    }
}
