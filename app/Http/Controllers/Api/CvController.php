<?php
/**
 * Created by PhpStorm.
 * User: bilel
 * Date: 04/11/2018
 * Time: 22:12
 */

namespace App\Http\Controllers\Api;

use App\Competence;
use App\CompetenceGroup;
use App\CompetenceLangue;
use App\Cv;
use App\CvCompetence;
use App\CvCompetenceLangue;
use App\CvExperience;
use App\CvFormation;
use App\CvLoisir;
use App\Experience;
use App\Formation;
use App\Http\Requests\CompetenceGroupRequest;
use App\Http\Requests\CompetenceLangueRequest;
use App\Http\Requests\CompetenceRequest;
use App\Http\Requests\CvRequest;
use App\Http\Requests\ExperienceRequest;
use App\Http\Requests\FormationRequest;
use App\Http\Requests\LoisirRequest;
use App\Loisir;
use App\Media;
use Symfony\Component\HttpFoundation\Request;

class CvController {

    /**
     * TODO ###############IMPORTANT##################################
     * Sur cette class pour la fonction store() et update()
     * récupèrer en JSON un tableau de tableau:
     * ACTUELLEMENT LE 01/03/2019 MON RETOUR JSON EST:
     [
     {
    "cv_user_id":"30",
    "cv_adresse":"9 rue des chalets",
    "cv_ville":"Colombes",...
    "formation_titre_1":"Formation 1",
    "formation_sujet_1":"Formation 1"...
    "competence_competenceGroup_id_1":"2",
    "competence_savoir_1":"Competence 1",...
    "experience_titre_1":"Experience 1",
    "experience_date_debut_1":"1991-12-13",
    "experience_date_fin_1":"1991-12-14",...
    "competenceLinguistique_langue_1":"Langue 111",
    "competenceLinguistique_niveau_1":"Faible",...
    "loisir_loisir_1":"Loisir 11",
    "loisir_loisir_2":"Loisir 2",...
    }
    ]; (voir Logiciel Insomnia || Postman)
     IL FAUT FAIRE UN TABLEAU DE TABLEAU parce que la
     * fonction FORMBUILDER DE ANGULAR me transforme mon JSON en tableau
     * de tableau "A TRAVAILLER":
     * example:
     * [
     * {
     * "cv_name":"toto",
     * "cv_image":"filename.png",
     * "cv_email":"email@gmail.com",
     * etc...,
     * formation[
     * "formation_name":"formation1",
     * "formation_ecole":"ecole1",
     * etc...,
     * ],
     * experience[
     * "experience_name":"experience",
     * "experience_langage":"language1",
     * etc...,
     * ],
     * loisir[
     * "loisir":"loisir1"
     * ],
     * etc...,
     * }
     * ];
     */
    //TODO#################IMPORTANT################################

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index(){
        $cv = Cv::with('media', 'user', 'formation', 'competence', 'experience', 'competenceLangue', 'loisir')->get();
        return response([
            'data' => $cv,
            'status' => 200
        ]);
    }

    /**
     * @param Cv $cv
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function show(Cv $cv){
        $cv = Cv::with(
            'media',
            'user',
            'formation',
            'competence',
            'experience',
            'competenceLangue',
            'loisir')
            ->where('id', '=', $cv->id)
            ->get();

        return response([
            'data' => $cv,
            'status' => 200
        ], 200);
    }

    /**
     * @param $user_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function myCv($user_id){
        if($user_id == null){
            return response([
                'error' => 'Error, user_id est NULL',
                'status' => 301
            ], 301);
        } else {
            $cv = Cv::with(
                'media',
                'user',
                'formation',
                'competence',
                'experience',
                'competenceLangue',
                'loisir')
                ->where('user_id', '=', $user_id)
                ->get();

            return response([
                'data' => $cv,
                'status' => 200
            ], 200);
        }
    }

    public function myGroupCompetence($competenceGroupId){
        $groupCompetence = CompetenceGroup::where('id', '=', $competenceGroupId)->get();
        return response([
            'data' => $groupCompetence,
            'status' => 200,
        ], 200);
    }

    /**
     * @param CompetenceGroupRequest $competenceGroupRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function addCompetenceGroup(CompetenceGroupRequest $competenceGroupRequest){
        $competenceGroup = new CompetenceGroup;
        $competenceGroup->user_id = $competenceGroupRequest->user_id;
        $competenceGroup->nom = $competenceGroupRequest->nom;
        $competenceGroup->save();

        return response([
            'data' => $competenceGroup,
            'status' => 200
        ], 200);
    }

    /**
     * @param CompetenceGroup $competenceGroup
     * @param CompetenceGroupRequest $competenceGroupRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function updateCompetenceGroup(CompetenceGroup $competenceGroup, CompetenceGroupRequest $competenceGroupRequest){
        $competenceGroup->user_id = $competenceGroupRequest->user_id;
        $competenceGroup->nom = $competenceGroupRequest->nom;
        $competenceGroup->save();

        return response([
            'data' => $competenceGroup,
            'status' => 200
        ], 200);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getCompetenceGroup(){
        $competenceGroup = CompetenceGroup::get();
        return response([
            'data' => $competenceGroup,
            'status' => 200
        ], 200);
    }

    /**
     * @param CompetenceGroup $competenceGroup
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function showCompetenceGroup(CompetenceGroup $competenceGroup){
        return response([
            'data' => $competenceGroup,
            'status' => 200
        ], 200);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request){

        // Si un utilisateur à déjà un CV alors
        $cv = Cv::where('user_id', '=', $request->user_id)->count();
        if($cv === 0){
            // Insertion dans la table CV
            $cv = new Cv;
            $cv->user_id = $request->cv_user_id;
            $cv->adresse = $request->cv_adresse;
            $cv->ville = $request->cv_ville;
            $cv->situation = $request->cv_situation;
            $cv->nationalite = $request->cv_nationalite;
            $cv->permis = $request->cv_permis;
            $cv->titre = $request->cv_titre;
            $cv->description = $request->cv_description;
            $cv->mobile = $request->cv_mobile;
            $cv->email = $request->cv_email;
            $cv->save();

            // Insertion dans la table Formation
            for($i = 1; $i <= count($request->request); $i++) {
                if($request->input('formation_titre_'.$i) == NULL){
                    // Si request == null ? on sort de la boucle : continue
                    break;
                } else {
                    $formation = new Formation;
                    $formation->titre = $request->input('formation_titre_'.$i);
                    $formation->date_debut = $request->input('formation_date_debut_'.$i);
                    $formation->date_fin = $request->input('formation_date_fin_'.$i);
                    $formation->sujet = $request->input('formation_sujet_'.$i);
                    $formation->ecole = $request->input('formation_ecole_'.$i);
                    $formation->save();

                    // insertion dans la table pivot cv_formation l'id du cv et l'id de la formation
                    $cvFormation = new CvFormation;
                    $cvFormation->cv_id = $cv->id;
                    $cvFormation->formation_id = $formation->id;
                    $cvFormation->save();
                }
            }

            // insertion dans la table competence
            for($i = 1; $i <= count($request->request); $i++) {
                if ($request->input('competence_savoir_'.$i) == NULL) {
                    // Si request == null ? on sort de la boucle : continue
                    break;
                } else {
                    $competence = new Competence;
                    $competence->competenceGroup_id = $request->input('competence_competenceGroup_id_'.$i);
                    $competence->savoir = $request->input('competence_savoir_'.$i);
                    $competence->niveau = $request->input('competence_niveau_'.$i);
                    $competence->save();

                    // insertion dans la table pivot cv_competence l'id du cv et l'id de la competence
                    $cvCompetence = new CvCompetence;
                    $cvCompetence->cv_id = $cv->id;
                    $cvCompetence->competence_id = $competence->id;
                    $cvCompetence->save();
                }
            }

            // Insertion dans la table Experience
            for($i = 1; $i <= count($request->request); $i++) {
                if ($request->input('experience_titre_'.$i) == NULL) {
                    // Si request == null ? on sort de la boucle : continue
                    break;
                } else {
                    $experience = new Experience;
                    $experience->titre = $request->input('experience_titre_'.$i);
                    $experience->date_debut = $request->input('experience_date_debut_'.$i);
                    $experience->date_fin = $request->input('experience_date_fin_'.$i);
                    $experience->sujet = $request->input('experience_sujet_'.$i);
                    $experience->description = $request->input('experience_description_'.$i);
                    $experience->contrat = $request->input('experience_contrat_'.$i);
                    $experience->save();

                    // insertion dans la table pivot cv_experience l'id du cv et l'id de l'experience
                    $cvExperience = new CvExperience;
                    $cvExperience->cv_id = $cv->id;
                    $cvExperience->experience_id = $experience->id;
                    $cvExperience->save();
                }
            }

            // insertion dans la table competenceLangue
            for($i = 1; $i <= count($request->request); $i++) {
                if ($request->input('competenceLinguistique_langue_'.$i) == NULL) {
                    // Si request == null ? on sort de la boucle : continue
                    break;
                } else {
                    $competenceLangue = new CompetenceLangue;
                    $competenceLangue->langue = $request->input('competenceLinguistique_langue_'.$i);
                    $competenceLangue->niveau = $request->input('competenceLinguistique_niveau_'.$i);
                    $competenceLangue->save();

                    // insertion dans la table pivot cv_competenceLangue l'id du cv et l'id de la competenceLangue
                    $cvCompetenceLangue = new CvCompetenceLangue;
                    $cvCompetenceLangue->cv_id = $cv->id;
                    $cvCompetenceLangue->competence_langue_id = $competenceLangue->id;
                    $cvCompetenceLangue->save();
                }
            }

            // Insertion dans la table Loisir
            for($i = 1; $i <= count($request->request); $i++) {
                if ($request->input('loisir_loisir_'.$i) == NULL) {
                    // Si request == null ? on sort de la boucle : continue
                    break;
                } else {
                    $loisir = new Loisir;
                    $loisir->loisir = $request->input('loisir_loisir_'.$i);
                    $loisir->save();

                    // insertion dans la table pivot cv_loisir l'id du cv et l'id du loisir
                    $cvLoisir = new CvLoisir;
                    $cvLoisir->cv_id = $cv->id;
                    $cvLoisir->loisir_id = $loisir->id;
                    $cvLoisir->save();
                }
            }

            return response([
                'data' => $cv,
                'message' => 'Creation effectué avec success!',
                'status' => 200
            ], 200);
        } else {
            return response([
                'message' => 'Error, cette utilisateur à déjà un CV!',
                'status' => 301
            ], 301);
        }
    }

    /**
     * @param Request $request
     * @param Cv $cv
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function uploadFile(Request $request, Cv $cv){
        // Remove File and Remove img row in Table *Media*
        $relationCv = Cv::with('media')->get();
        if($relationCv[0]->media_id != NULL && $relationCv[0]->media_id != '' && $relationCv[0]->media->nom != 'default_cv') {
            // Remove file and table row Media and User
            foreach($relationCv as $row){
                $filename = explode('/' ,$row->media->filename);
                $file = end($filename);

                $chemin = public_path() . '/uploads/cv/'.$file;
                if(file_exists($chemin)){
                    unlink($chemin);
                }
                // Remove row in table media and replace field media_id = NULL
                $media = Media::where('id', '=', $cv->media_id)->get();
                $cv->media_id = NULL;
                $cv->save();
                $media[0]->delete();
            }
            // Upload New File
            $destinationPath = public_path() . '/uploads/cv/';
            $fileName = 'cv_' . strtotime('now') . '.' . $request->file('filename')->getClientOriginalExtension();
            $request->file('filename')->move($destinationPath, $fileName);

            $media = new Media;
            $media->nom = 'CV';
            $media->filename = $request->root().'/uploads/cv/'.''.$fileName;
            $media->save();

            $cv->media_id = $media->id;
            $cv->save();
            return response([
                'data' => $media,
                'status' => 200
            ]);
        } else {
            // Upload CV file
            $destinationPath = public_path() . '/uploads/cv/';
            $fileName = 'cv_' . strtotime('now') . '.' . $request->file('filename')->getClientOriginalExtension();
            $request->file('filename')->move($destinationPath, $fileName);

            $media = new Media;
            $media->nom = 'CV';
            $media->filename = $request->root().'/uploads/cv/'.''.$fileName;
            $media->save();

            $cv->media_id = $media->id;
            $cv->save();
            return response([
                'data' => $media,
                'status' => 200
            ]);
        }
    }

    /**
     * @param Cv $cv
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Cv $cv, Request $request){

        // Update dans la table CV
        $cv->user_id = $request->cv_user_id;
        $cv->adresse = $request->cv_adresse;
        $cv->ville = $request->cv_ville;
        $cv->situation = $request->cv_situation;
        $cv->nationalite = $request->cv_nationalite;
        $cv->permis = $request->cv_permis;
        $cv->titre = $request->cv_titre;
        $cv->description = $request->cv_description;
        $cv->mobile = $request->cv_mobile;
        $cv->email = $request->cv_email;
        $cv->save();

        // Traitement
        $cvFormation = CvFormation::where('cv_id', '=', $cv->id)->get();
        //dump($cvFormation);
        foreach($cvFormation as $i => $f){
            $formation = Formation::where('id', '=', $f->formation_id)->get();
            $i = $i+=1;
            // Update dans la table Formation
            $formation[0]->titre = $request->input('formation_titre_'.$i);
            $formation[0]->date_debut = $request->input('formation_date_debut_'.$i);
            $formation[0]->date_fin = $request->input('formation_date_fin_'.$i);
            $formation[0]->sujet = $request->input('formation_sujet_'.$i);
            $formation[0]->ecole = $request->input('formation_ecole_'.$i);
            $formation[0]->save();
        }

        // Traitement
        $cvCompetence = CvCompetence::where('cv_id', '=', $cv->id)->get();
        foreach($cvCompetence as $i => $c){
            $competence = Competence::where('id', '=', $c->competence_id)->get();
            $i = $i+=1;
            // Update dans la table competence
            $competence[0]->competenceGroup_id = $request->input('competence_competenceGroup_id_'.$i);
            $competence[0]->savoir = $request->input('competence_savoir_'.$i);
            $competence[0]->niveau = $request->input('competence_niveau_'.$i);
            $competence[0]->save();
        }

        // Traitement
        $cvExperience = CvExperience::where('cv_id', '=', $cv->id)->get();
        foreach($cvExperience as $i => $e){
            $experience = Experience::where('id', '=', $e->experience_id)->get();
            $i = $i+=1;
            // Update dans la table Experience
            $experience[0]->titre = $request->input('experience_titre_'.$i);
            $experience[0]->date_debut = $request->input('experience_date_debut_'.$i);
            $experience[0]->date_fin = $request->input('experience_date_fin_'.$i);
            $experience[0]->sujet = $request->input('experience_sujet_'.$i);
            $experience[0]->description = $request->input('experience_description_'.$i);
            $experience[0]->contrat = $request->input('experience_contrat_'.$i);
            $experience[0]->save();
        }

        // Traitement
        $cvCompetenceLangue = CvCompetenceLangue::where('cv_id', '=', $cv->id)->get();
        foreach($cvCompetenceLangue as $i => $cl){
            $competenceLangue = CompetenceLangue::where('id', '=', $cl->competence_langue_id)->get();
            $i = $i+=1;
            // Update dans la table competenceLangue
            $competenceLangue[0]->langue = $request->input('competenceLinguistique_langue_'.$i);
            $competenceLangue[0]->niveau = $request->input('competenceLinguistique_niveau_'.$i);
            $competenceLangue[0]->save();
        }

        // Update dans la table Loisir
        $cvLoisir = CvLoisir::where('cv_id', '=', $cv->id)->get();
        foreach($cvLoisir as $i => $l){
            $loisir = Loisir::where('id', '=', $l->loisir_id)->get();
            $i = $i+=1;
            // Update dans la table Loisir
            $loisir[0]->loisir = $request->input('loisir_loisir_'.$i);
            $loisir[0]->save();
        }

        return response([
            'data' => $cv,
            'message' => 'Modification effectué avec success!',
            'status' => 200
        ], 200);
    }

    /**
     * @param Cv $cv
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function addFormation(Cv $cv, Request $request){
        // Si un utilisateur à déjà un CV alors
        if($cv != NULL) {
            // Insertion dans la table Formation
            for ($i = 1; $i <= count($request->request); $i++) {
                if ($request->input('formation_titre_'.$i) == NULL) {
                    // Si request == null ? on sort de la boucle : continue
                    break;
                } else {
                    $formation = new Formation;
                    $formation->titre = $request->input('formation_titre_'.$i);
                    $formation->date_debut = $request->input('formation_date_debut_'.$i);
                    $formation->date_fin = $request->input('formation_date_fin_'.$i);
                    $formation->sujet = $request->input('formation_sujet_'.$i);
                    $formation->ecole = $request->input('formation_ecole_'.$i);
                    $formation->save();

                    // insertion dans la table pivot cv_formation l'id du cv et l'id de la formation
                    $cvFormation = new CvFormation;
                    $cvFormation->cv_id = $cv->id;
                    $cvFormation->formation_id = $formation->id;
                    $cvFormation->save();
                }
            }
            return response([
                'data' => $formation,
                'status' => 200
            ], 200);
        }
        return response([
            'message' => 'Cv unknown',
            'status' => 301
        ], 301);
    }

    /**
     * @param Cv $cv
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function addCompetence(Cv $cv, Request $request){
        // Si un utilisateur à déjà un CV alors
        if($cv != NULL) {
            // Insertion dans la table Formation
            for ($i = 1; $i <= count($request->request); $i++) {
                if ($request->input('competence_savoir_'.$i) == NULL) {
                    // Si request == null ? on sort de la boucle : continue
                    break;
                } else {
                    $competence = new Competence;
                    $competence->competenceGroup_id = $request->input('competence_competenceGroup_id_'.$i);
                    $competence->savoir = $request->input('competence_savoir_'.$i);
                    $competence->niveau = $request->input('competence_niveau_'.$i);
                    $competence->save();

                    // insertion dans la table pivot cv_competence l'id du cv et l'id de la competence
                    $cvCompetence = new CvCompetence;
                    $cvCompetence->cv_id = $cv->id;
                    $cvCompetence->competence_id = $competence->id;
                    $cvCompetence->save();
                }
            }
            return response([
                'data' => $competence,
                'status' => 200
            ], 200);
        }
        return response([
            'message' => 'Cv unknown',
            'status' => 301
        ], 301);
    }

    /**
     * @param Cv $cv
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function addExperience(Cv $cv, Request $request){
        // Si un utilisateur à déjà un CV alors
        if($cv != NULL) {
            // Insertion dans la table Formation
            for ($i = 1; $i <= count($request->request); $i++) {
                if ($request->input('experience_titre_'.$i) == NULL) {
                    // Si request == null ? on sort de la boucle : continue
                    break;
                } else {
                    $experience = new Experience;
                    $experience->titre = $request->input('experience_titre_'.$i);
                    $experience->date_debut = $request->input('experience_date_debut_'.$i);
                    $experience->date_fin = $request->input('experience_date_fin_'.$i);
                    $experience->sujet = $request->input('experience_sujet_'.$i);
                    $experience->description = $request->input('experience_description_'.$i);
                    $experience->contrat = $request->input('experience_contrat_'.$i);
                    $experience->save();

                    // insertion dans la table pivot cv_experience l'id du cv et l'id de l'experience
                    $cvExperience = new CvExperience;
                    $cvExperience->cv_id = $cv->id;
                    $cvExperience->experience_id = $experience->id;
                    $cvExperience->save();
                }
            }
            return response([
                'data' => $experience,
                'status' => 200
            ], 200);
        }
        return response([
            'message' => 'Cv unknown',
            'status' => 301
        ], 301);
    }

    /**
     * @param Cv $cv
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function addCompetenceLangue(Cv $cv, Request $request){
        // Si un utilisateur à déjà un CV alors
        if($cv != NULL) {
            // Insertion dans la table Formation
            for ($i = 1; $i <= count($request->request); $i++) {
                if ($request->input('competenceLinguistique_langue_'.$i) == NULL) {
                    // Si request == null ? on sort de la boucle : continue
                    break;
                } else {
                    $competenceLangue = new CompetenceLangue;
                    $competenceLangue->langue = $request->input('competenceLinguistique_langue_'.$i);
                    $competenceLangue->niveau = $request->input('competenceLinguistique_niveau_'.$i);
                    $competenceLangue->save();

                    // insertion dans la table pivot cv_competenceLangue l'id du cv et l'id de la competenceLangue
                    $cvCompetenceLangue = new CvCompetenceLangue;
                    $cvCompetenceLangue->cv_id = $cv->id;
                    $cvCompetenceLangue->competence_langue_id = $competenceLangue->id;
                    $cvCompetenceLangue->save();
                }
            }
            return response([
                'data' => $competenceLangue,
                'status' => 200
            ], 200);
        }
        return response([
            'message' => 'Cv unknown',
            'status' => 301
        ], 301);
    }

    /**
     * @param Cv $cv
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function addLoisir(Cv $cv, Request $request){
        // Si un utilisateur à déjà un CV alors
        if($cv != NULL) {
            // Insertion dans la table Formation
            for ($i = 1; $i <= count($request->request); $i++) {
                if ($request->input('loisir_loisir_'.$i) == NULL) {
                    // Si request == null ? on sort de la boucle : continue
                    break;
                } else {
                    $loisir = new Loisir;
                    $loisir->loisir = $request->input('loisir_loisir_'.$i);
                    $loisir->save();

                    // insertion dans la table pivot cv_loisir l'id du cv et l'id du loisir
                    $cvLoisir = new CvLoisir;
                    $cvLoisir->cv_id = $cv->id;
                    $cvLoisir->loisir_id = $loisir->id;
                    $cvLoisir->save();
                }
            }
            return response([
                'data' => $loisir,
                'status' => 200
            ], 200);
        }
        return response([
            'message' => 'Cv unknown',
            'status' => 301
        ], 301);
    }

    /**
     * @param Formation $formation
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function removeFormation(Formation $formation){
        $formation->delete();
        return response([
            'data' => $formation,
            'status' => 200
        ], 200);
    }

    /**
     * @param Competence $competence
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function removeCompetence(Competence $competence){
        $competence->delete();
        return response([
            'data' => $competence,
            'status' => 200
        ], 200);
    }

    /**
     * @param Experience $experience
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function removeExperience(Experience $experience){
        $experience->delete();
        return response([
            'data' => $experience,
            'status' => 200,
        ], 200);
    }

    /**
     * @param CompetenceLangue $competenceLangue
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function removeCompetenceLangue(CompetenceLangue $competenceLangue){
        $competenceLangue->delete();
        return response([
            'data' => $competenceLangue,
            'status' => 200
        ], 200);
    }

    /**
     * @param Loisir $loisir
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function removeLoisir(Loisir $loisir){
        $loisir->delete();
        return response([
            'data' => $loisir,
            'status' => 200
        ], 200);
    }

    /**
     * @param Cv $cv
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Cv $cv){
        /* Suppression des images en bdd,
         * table media + fichier **/
        $cv = Cv::with('media')->where('id', '=', $cv->id)->get();
        $cv[0]->delete();
        // Delete du media en BDD
        if($cv[0]->media != null){
            // Récupération du nom de l'image
            $filename = explode('/' , $cv[0]->media->filename);
            $file = end($filename);
            // Suppression de l'image sur les server
            $chemin = public_path() . '/uploads/cv/'.$file;
            if($chemin != public_path() . '/uploads/cv/default_cv.png') {
                if (file_exists($chemin)) {
                    unlink($chemin);
                }
            }
            $media = Media::where('id', '=', $cv[0]->media_id)->get();
            $media[0]->delete();
        }
        return response([
            'data' => $cv[0],
            'message' => 'Cv and Media deleted!',
            'status' => 200
        ], 200);
    }
}