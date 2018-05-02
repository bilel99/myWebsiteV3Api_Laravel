<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lastUser = User::orderBy('id', 'desc')->first();
        return $this->view('mail.nouveau_inscris')
            ->with([
                'object' => 'Bienvenue sur mon site web',
                'expediteur' => 'Admin',
                'nom' => $lastUser->nom,
                'prenom' => $lastUser->prenom,
                'email' => $lastUser->email
            ]);
    }
}
