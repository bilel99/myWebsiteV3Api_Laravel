<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPassStep1Mail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $passwordProvisoir)
    {
        $this->email = $email;
        $this->passwordProvisoir = $passwordProvisoir;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.forgotPassword')
            ->with([
                'object' => 'Bienvenue sur notre site',
                'expediteur' => 'Admin',
                'email' => $this->email,
                'passwordProvisoir' => $this->passwordProvisoir
            ]);
    }
}
