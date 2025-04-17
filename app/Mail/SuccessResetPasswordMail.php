<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SuccessResetPasswordMail extends Mailable

{
    use Queueable, SerializesModels;

    public $email;
    public $name;

    public function __construct($email, $name)
    {
        $this->email = $email;
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject('Successful Password Reset - ' . config('app.name'))
                    ->view('emails.success_reset_password')
                    ->with([
                        'email' => $this->email,
                        'name' => $this->name,
                    ]);
    }
}
