<?php namespace Sneefr\Events;

use Illuminate\Queue\SerializesModels;
use Sneefr\Models\Tag;
use Sneefr\Models\User;

class UserWasTagged extends Event
{

    use SerializesModels;
    /**
     * @var User
     */
    public $target;
    /**
     * @var Tag
     */
    public $item;
    /**
     * @var array
     */
    public $data;

    /**
     * Create a new event instance.
     *
     * @param User  $target
     * @param Tag   $tag
     * @param array $data
     */
    public function __construct(User $target, Tag $tag, array $data = [])
    {
        $this->target = $target;
        $this->item = $tag;
        $this->data = $data;
    }

}
