<?php

namespace App\Exceptions;

use Exception;

class BookingConflictException extends Exception
{
    public function __construct(string $message = 'The selected time slot is no longer available.')
    {
        parent::__construct($message);
    }

    public function render($request)
    {
        return redirect()
            ->back()
            ->withInput()
            ->withErrors(['start_time' => $this->getMessage()]);
    }
}
