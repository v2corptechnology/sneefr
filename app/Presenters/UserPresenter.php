<?php namespace Sneefr\Presenters;

use Laracodes\Presenter\Presenter;

class UserPresenter extends Presenter
{
    public function fullName() : string
    {
        if (auth()->check()) {
            return $this->model->given_name . ' ' . $this->model->surname;
        }

        return $this->truncatedName();
    }

    public function givenName() : string
    {
        return (string) $this->model->given_name ?? '';
    }

    public function surname() : string
    {
        return $this->model->surname ?? '';
    }

    public function truncatedName() : string
    {
        return $this->model->given_name . ' ' . mb_substr($this->model->surname, 0, 1) . '.';
    }

    public function protectedEmail()
    {
        $email = $this->model->getEmail();

        // If no email provided
        if (! $email) {
            return;
        }

        return $this->replaceBetween($email, 4, strpos($email, '@'));
    }

    public function protectedPhone()
    {
        $number = $this->model->phone->getNumber();

        // If no phone provided
        if (! $number) {
            return;
        }

        return $this->replaceBetween($number, 4, strlen($number) - 3);
    }

    private function replaceBetween(string $str, int $start, int $end, string $replacement = 'x')
    {
        $replaced = '';

        foreach (str_split($str) as $i => $letter) {
            if ($i < $start || $i >= $end) {
                $replaced .= $letter;
                continue;
            }

            $replaced .= $replacement;
        }

        return $replaced;
    }
}
