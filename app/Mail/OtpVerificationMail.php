<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OtpVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $expiresInMinutes;
    public $email;


    /**
     * Create a new message instance.
     *
     * @param string $otp
     * @param int $expiresInMinutes
     * @param string $email
     */
    public function __construct(string $otp, string $email, int $expiresInMinutes = 5)
    {
        $this->otp = $otp;
        $this->expiresInMinutes = $expiresInMinutes;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Verification Code - ' . config('app.name'))
                   ->view('emails.otp_verification')
                   ->with([
                       'otp' => $this->otp,
                       'expiresInMinutes' => $this->expiresInMinutes,
                       'email' => $this->email
                   ]);
    }
}
