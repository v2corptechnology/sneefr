<?php namespace Sneefr\Repositories\Tag;

class EloquentTagRepository implements TagRepository
{
    /**
     * Return the model type this tag needs
     *
     * @param string $type
     *
     * @return string|null
     */
    public function guessModel($type)
    {
        switch($type) {
            case 'ad':
                return \Sneefr\Models\Ad::class;
                break;
            case 'search':
                return \Sneefr\Models\Search::class;
                break;
        }
    }
}
