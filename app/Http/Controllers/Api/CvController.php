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
     * @param CvRequest $cvRequest
     * @param FormationRequest $formationRequest
     * @param CompetenceRequest $competenceRequest
     * @param ExperienceRequest $experienceRequest
     * @param CompetenceLangueRequest $competenceLangueRequest
     * @param LoisirRequest $loisirRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(
        CvRequest $cvRequest,
        FormationRequest $formationRequest,
        CompetenceRequest $competenceRequest,
        ExperienceRequest $experienceRequest,
        CompetenceLangueRequest $competenceLangueRequest,
        LoisirRequest $loisirRequest){

        // Si un utilisateur à déjà un CV alors
        $cv = Cv::where('user_id', '=', $cvRequest->user_id)->count();
        if($cv === 0){
            // Insertion dans la table CV
            $cv = new Cv;
            $cv->user_id = $cvRequest->user_id;
            $cv->adresse = $cvRequest->adresse;
            $cv->ville = $cvRequest->ville;
            $cv->situation = $cvRequest->situation;
            $cv->nationalite = $cvRequest->nationalite;
            $cv->permis = $cvRequest->permis;
            $cv->titre = $cvRequest->titre;
            $cv->description = $cvRequest->description;
            $cv->about = $cvRequest->about;
            $cv->mobile = $cvRequest->mobile;
            $cv->email = $cvRequest->email;
            $cv->save();

            // Insertion dans la table Formation
            $formation = new Formation;
            $formation->titre = $formationRequest->titre;
            $formation->date_debut = $formationRequest->date_debut;
            $formation->date_fin = $formationRequest->date_fin;
            $formation->sujet = $formationRequest->sujet;
            $formation->ecole = $formationRequest->ecole;
            $formation->save();

            // insertion dans la table pivot cv_formation l'id du cv et l'id de la formation
            $cvFormation = new CvFormation;
            $cvFormation->cv_id = $cv->id;
            $cvFormation->formation_id = $formation->id;
            $cvFormation->save();

            // insertion dans la table competence
            $competence = new Competence;
            $competence->competenceGroup_id = $competenceRequest->competenceGroup_id;
            $competence->nom = $competenceRequest->nom;
            $competence->savoir = $competenceRequest->savoir;
            $competence->niveau = $competenceRequest->niveau;
            $competence->save();

            // insertion dans la table pivot cv_competence l'id du cv et l'id de la competence
            $cvCompetence = new CvCompetence;
            $cvCompetence->cv_id = $cv->id;
            $cvCompetence->competence_id = $competence->id;
            $cvCompetence->save();

            // Insertion dans la table Experience
            $experience = new Experience;
            $experience->titre = $experienceRequest->titre;
            $experience->date_debut = $experienceRequest->date_debut;
            $experience->date_fin = $experienceRequest->date_fin;
            $experience->sujet = $experienceRequest->sujet;
            $experience->description = $experienceRequest->description;
            $experience->contrat = $experienceRequest->contrat;
            $experience->save();

            // insertion dans la table pivot cv_experience l'id du cv et l'id de l'experience
            $cvExperience = new CvExperience;
            $cvExperience->cv_id = $cv->id;
            $cvExperience->experience_id = $experience->id;
            $cvExperience->save();

            // insertion dans la table competenceLangue
            $competenceLangue = new CompetenceLangue;
            $competenceLangue->langue = $competenceRequest->langue;
            $competenceLangue->niveau = $competenceRequest->niveau;
            $competenceLangue->save();

            // insertion dans la table pivot cv_competenceLangue l'id du cv et l'id de la competenceLangue
            $cvCompetenceLangue = new CvCompetenceLangue;
            $cvCompetenceLangue->cv_id = $cv->id;
            $cvCompetenceLangue->competence_langue_id = $competenceLangue->id;
            $cvCompetenceLangue->save();

            // Insertion dans la table Loisir
            $loisir = new Loisir;
            $loisir->loisir = $loisirRequest->loisir;
            $loisir->save();

            // insertion dans la table pivot cv_loisir l'id du cv et l'id du loisir
            $cvLoisir = new CvLoisir;
            $cvLoisir->cv_id = $cv->id;
            $cvLoisir->loisir_id = $loisir->id;
            $cvLoisir->save();

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
     * @param CvRequest $cvRequest
     * @param FormationRequest $formationRequest
     * @param CompetenceRequest $competenceRequest
     * @param ExperienceRequest $experienceRequest
     * @param CompetenceLangueRequest $competenceLangueRequest
     * @param LoisirRequest $loisirRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Cv $cv,
                           CvRequest $cvRequest,
                           FormationRequest $formationRequest,
                           CompetenceRequest $competenceRequest,
                           ExperienceRequest $experienceRequest,
                           CompetenceLangueRequest $competenceLangueRequest,
                           LoisirRequest $loisirRequest){

        // Update dans la table CV
        $cv->user_id = $cvRequest->user_id;
        $cv->adresse = $cvRequest->adresse;
        $cv->ville = $cvRequest->ville;
        $cv->situation = $cvRequest->situation;
        $cv->nationalite = $cvRequest->nationalite;
        $cv->permis = $cvRequest->permis;
        $cv->titre = $cvRequest->titre;
        $cv->description = $cvRequest->description;
        $cv->about = $cvRequest->about;
        $cv->mobile = $cvRequest->mobile;
        $cv->email = $cvRequest->email;
        $cv->save();

        // Traitement
        $cvFormation = CvFormation::where('cv_id', '=', $cv->id)->get();
        foreach($cvFormation as $f){
            $formation = Formation::where('id', '=', $f->formation_id)->get();
            // Update dans la table Formation
            $formation[0]->titre = $formationRequest->titre;
            $formation[0]->date_debut = $formationRequest->date_debut;
            $formation[0]->date_fin = $formationRequest->date_fin;
            $formation[0]->sujet = $formationRequest->sujet;
            $formation[0]->ecole = $formationRequest->ecole;
            $formation[0]->save();
        }

        // Traitement
        $cvCompetence = CvCompetence::where('cv_id', '=', $cv->id)->get();
        foreach($cvCompetence as $c){
            $competence = Competence::where('id', '=', $c->competence_id)->get();
            // Update dans la table competence
            $competence[0]->competenceGroup_id = $competenceRequest->competenceGroup_id;
            $competence[0]->nom = $competenceRequest->nom;
            $competence[0]->savoir = $competenceRequest->savoir;
            $competence[0]->niveau = $competenceRequest->niveau;
            $competence[0]->save();
        }

        // Traitement
        $cvExperience = CvExperience::where('cv_id', '=', $cv->id)->get();
        foreach($cvExperience as $e){
            $experience = Experience::where('id', '=', $e->experience_id)->get();
            // Update dans la table Experience
            $experience[0]->titre = $experienceRequest->titre;
            $experience[0]->date_debut = $experienceRequest->date_debut;
            $experience[0]->date_fin = $experienceRequest->date_fin;
            $experience[0]->sujet = $experienceRequest->sujet;
            $experience[0]->description = $experienceRequest->description;
            $experience[0]->contrat = $experienceRequest->contrat;
            $experience[0]->save();
        }

        // Traitement
        $cvCompetenceLangue = CvCompetenceLangue::where('cv_id', '=', $cv->id)->get();
        foreach($cvCompetenceLangue as $cl){
            $competenceLangue = CompetenceLangue::where('id', '=', $cl->competence_langue_id)->get();
            // Update dans la table competenceLangue
            $competenceLangue[0]->langue = $competenceRequest->langue;
            $competenceLangue[0]->niveau = $competenceRequest->niveau;
            $competenceLangue[0]->save();
        }


        // Update dans la table Loisir
        $cvLoisir = CvLoisir::where('cv_id', '=', $cv->id)->get();
        foreach($cvLoisir as $l){
            $loisir = Loisir::where('id', '=', $l->loisir_id)->get();
            // Update dans la table Loisir
            $loisir[0]->loisir = $loisirRequest->loisir;
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