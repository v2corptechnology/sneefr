<?php namespace Sneefr\Repositories\Tag;

interface TagRepository
{
    /**
     * Return the model type this tag needs
     *
     * @param string $type
     *
     * @return string|null
     */
    public function guessModel($type);
}
