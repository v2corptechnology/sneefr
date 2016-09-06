<?php namespace Sneefr\Jobs;

use Illuminate\Queue\SerializesModels;
use Sneefr\Models\Discussion;
use Sneefr\Models\Message;
use Sneefr\Models\User;
use Vinkla\Pusher\Facades\Pusher;

class PushMessage extends Job
{
    use SerializesModels;

    /**
     * @var \Sneefr\Models\Discussion
     */
    protected $discussion;

    /**
     * @var \Sneefr\Models\Message
     */
    protected $message;

    /**
     * Create a new job instance.
     *
     * @param \Sneefr\Models\Discussion $discussion
     * @param \Sneefr\Models\Message    $message
     */
    public function __construct(Discussion $discussion, Message $message)
    {
        $this->discussion = $discussion;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send the message back to the sender
        $data = $this->buildMessageDataFor($this->message->from);
        Pusher::trigger('private-' . $this->message->from->getRouteKey(), 'sent_message', $data);

        // Send the message to the recipient
        $data = $this->buildMessageDataFor($this->message->to);
        Pusher::trigger('private-' . $this->message->to->getRouteKey(), 'new_message', $data);
    }

    /**
     * Get the passed push data.
     *
     * @param \Sneefr\Jobs\User|\Sneefr\Models\User $user
     *
     * @return array
     */
    protected function buildMessageDataFor(User $user) : array
    {
        $target = $this->getTargetForPush($this->discussion, $user);

        return [
            'target'        => $target,
            'discussion_id' => $this->discussion->id(),
            'message_body'  => view('discussions._message', [
                'sender'  => $this->message->from,
                'message' => $this->message,
                'shop'    => $this->discussion->shop])->render(),
        ];
    }

    /**
     * Get the target we need to update on the client side.
     *
     * @param \Sneefr\Models\Discussion $discussion
     * @param \Sneefr\Models\User       $user
     *
     * @return string
     */
    protected function getTargetForPush(Discussion $discussion, User $user) : string
    {
        // Send the notification to the shop only when it is the owner
        if ($discussion->shop && $discussion->shop->user_id == $user->getId()) {
            return $discussion->shop->getRouteKey();
        }

        return $user->getRouteKey();
    }
}
