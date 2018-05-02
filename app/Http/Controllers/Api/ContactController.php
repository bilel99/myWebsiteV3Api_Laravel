<?php

namespace App\Http\Controllers\Api;

use App\Contact;
use App\Http\Requests\ContactRequest;
use App\Http\Controllers\Controller;
use App\Mail\NewContactForWebsiteMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{


    public function create(ContactRequest $request){
        $contact = new Contact;
        $contact->email = $request->email;
        $contact->sujet = $request->sujet;
        $contact->text = $request->text;
        $contact->done = 0;
        $contact->save();

        // Envoie du mail
        Mail::to('bilel.bekkouche@gmail.com')->send(new NewContactForWebsiteMail());

        return response([
            'data' => $contact,
            'status' => 200
        ]);
    }
}
