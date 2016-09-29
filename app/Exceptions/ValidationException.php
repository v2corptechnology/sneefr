<?php namespace Sneefr\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;

/**
 * An exception thrown when a validation fails.
 */
class ValidationException extends \RuntimeException
{
    /**
     * A validator instance.
     *
     * @var \Illuminate\Contracts\Validation\Validator
     */
    protected $validator;

    /**
     * Instantiate a new validation error.
     *
     * @param \Illuminate\Contracts\Validation\Validator  $validator  A validator instance
     * @param string           $message   The Exception message to throw
     * @param int              $code      The Exception code
     * @param \Exception|null  $previous  The previous exception used for the exception chaining
     */
    public function __construct(
        Validator $validator,
        $message = null,
        $code = 0,
        Exception $previous = null
    ) {
        $this->validator = $validator;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the validation error messages.
     *
     * @return \Illuminate\Contracts\Support\MessageBag
     */
    public function errors()
    {
        return $this->validator->errors();
    }
}
