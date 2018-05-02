<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ForgotPassStep1Mail;
use App\User;
use Illuminate\Support\Facades\Mail;

class AuthUserController extends Controller {


    /**
     * @param RegisterRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function register(RegisterRequest $request){
        $user = new User;

        $req = User::where('email', $request->email)->count();
        if($req == 0){
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->email = $request->email;
            $user->role_id = 1;
            $user->password = \Hash::make($request->password.\Config::get('const.salt'));
            $user->save();
            return response([
                'data' => $user,
                'status' => 200
            ], 200);
        } else {
            return response([
                'error' => 'Votre compte existe déjà !',
                'status' => 300
            ]);
        }
    }


    /**
     * @param LoginRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function login(LoginRequest $request)
    {
        // On recupère les données postés par le formulaire
        $email      = $request->email;
        $password   = $request->password.\Config::get('const.salt');
        $remember   = $request->remember;

        // Si l'email et le mot de passe saisis sont correctes
        if (\Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            $user = User::where('email', '=', $email)->get();
            return response([
                'data' => $user,
                'status' => 200
            ]);
        } else {
            return response([
                'error' => 'Email ou Mot de passe incorrect !',
                'status' => 300
            ]);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request){
        $email = $request->email;

        // Création d'un mot de passe aléatoire
        $chaine = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890@&';
        $nb_car = 10;
        $nb_lettres = strlen($chaine) - 1;
        $generation = '';
        for($i=0; $i < $nb_car; $i++)
        {
            $pos = mt_rand(0, $nb_lettres);
            $car = $chaine[$pos];
            $generation .= $car;
        }

        // Vérification que le mail existe en base de données
        $user = new User();
        $user = User::where('email', '=', $email)->count();

        if ($user > 0) {
            // Insertion du mot de passe provisoire en base de données
            $id_user = User::where('email', '=', $request->email)->get();
            $id = $id_user[0]->id;

            $user = User::find($id);
            $user->forgot = $generation;
            $user->save();

            // Envoie du mail
            Mail::to($request->email)->send(new ForgotPassStep1Mail($request->email, $generation));

            return response([
                'success' => 'Un email contenant votre mot de passe provisoir vous a était envoyé !',
                'status' => '200'
            ]);
        }else{
            return response([
                'error' => 'Erreur lors de l\'envoie du mail',
                'status' => '200'
            ]);
        }
    }

    public function forgotPassStep2(ResetPasswordRequest $request){
        // Récupération des champs html5
        $email = $request->email;
        $forgot = $request->forgot;
        $password = $request->password;

        // Vérification que le couple mail/old_password est valide
        $user = new User();
        $user = User::where('email', '=', $email)->where('forgot','=', $forgot)->count();
        if ($user > 0) {
            // password identique à confirmation password
            $id_user = User::where('email', '=', $email)->get();
            $id = $id_user[0]->id;

            $user = User::find($id);
            $user->password = \Hash::make($request->password.\Config::get('const.salt'));
            $user->forgot = null;
            $user->save();

            return response([
                'data' => $user,
                'status' => 200
            ]);
        }else{
            return response([
                'error' => 'Incorrect old password',
                'status' => 300
            ]);
        }
    }



}