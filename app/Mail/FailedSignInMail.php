<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FailedSignInMail extends Mailable

{
    use Queueable, SerializesModels;

    public $email;
    public $name;
    public $device;
    public $location;
    public $ip;
    public $time;

    public function __construct($email, $name, $device, $location, $ip, $time)
    {
        $this->email = $email;
        $this->name = $name;
        $this->device = $device;
        $this->location = $location;
        $this->ip = $ip;
        $this->time = $time;
    }

    public function build()
    {
        return $this->subject('Suspicious Login Attempt Detected - ' . config('app.name'))
            ->view('emails.failed_sign_in')
            ->with([
                'email' => $this->email,
                'name' => $this->name,
                'device' => $this->device,
                'location' => $this->location,
                'ip' => $this->ip,
                'time' => $this->time,
            ]);
    }
}
