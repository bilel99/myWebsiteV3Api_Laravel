<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AccountRequest;
use App\Media;
use App\User;
use App\Ville;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class AccountController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response([
            'data' => $user,
            'status' => 200
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AccountRequest $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(AccountRequest $request, User $user)
    {
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->sexe = $request->sexe;
        $user->date_naissance = $request->date_naissance;
        $user->mobile = $request->mobile;

        // Insert City
        $entityVille = Ville::where('id', '=', $request->ville_id)->get();
        $user->ville_id = $entityVille[0]->id;
        $user->save();
        return response([
            'data' => array($user),
            'status' => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response([
            'data' => $user,
            'status' => 200
        ]);
    }

    /**
     * @param $cp
     * @return $this
     * @throws \Exception
     */
    public function getVille($cp){
        $villeCodePostal = Ville::where('zipcode', '=', $cp)->get();
        if($villeCodePostal) {
            $ville = array(
                'id' => $villeCodePostal[0]->id,
                'libelle' => $villeCodePostal[0]->libelle
            );
        } else {
            $ville = null;
        }
        // AJAX
        $response = new JsonResponse();
        return $response->setData(array(
            'ville' => $ville
        ));
    }

    public function getVilleIdFromCp($id){
        $villeId = Ville::where('id', '=', $id)->get();
        if($villeId) {
            $ville = array(
                'cp' => $villeId[0]->zipcode,
                'libelle' => $villeId[0]->libelle
            );
        } else {
            $ville = null;
        }
        // AJAX
        $response = new JsonResponse();
        return $response->setData(array(
            'ville' => $ville
        ));
    }

    public function uploadAvatar(Request $request ,User $user){
        $this->validate($request, [
            'filename' => 'required',
            'filename.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $destinationPath = public_path() . '/uploads/avatar/';
        $fileName = 'avatar_' . strtotime('now') . '.' . $request->file('filename')->getClientOriginalExtension();
        $request->file('filename')->move($destinationPath, $fileName);

        $media = new Media;
        $media->nom = 'Avatar';
        $media->filename = $request->root().'/uploads/avatar/'.''.$fileName;
        $media->save();

        $user->media_id = $media->id;
        $user->save();
        return response([
            'data' => $media,
            'status' => 200
        ]);
    }

    public function getAvatar(User $user, Request $request){
        $user = User::with('media')->where('id', '=', $user->id)->get();
        if($user){
            $avatar = array(
                'media_id' => $user[0]->media_id,
                'media_nom' => $user[0]->media->nom,
                'filename' => $user[0]->media->filename
            );
        } else {
            $avatar = null;
        }
        // AJAX
        $response = new JsonResponse();
        return $response->setData(array(
            'data' => $avatar,
            'status' => 200
        ));
    }
}
