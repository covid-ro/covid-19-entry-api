<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailValidationCode extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private string $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ro.email_validation_code')
            ->subject('Codul de validare adresa de email - Covid Safe@Frontiera')
            ->with(['code' => $this->code]);
    }
}
