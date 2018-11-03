<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PortfoliosRequest;
use App\Media;
use App\Portfolio;
use App\Http\Controllers\Controller;
use App\PortfolioMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class PortfoliosController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index() {
        $porfolios = Portfolio::with('media')->get();
        return response([
            'data' => $porfolios,
            'status' => 200
        ]);
    }

    /**
     * @param Portfolio $portfolio
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function show(Portfolio $portfolio){
        $portfolio = Portfolio::with('media')->where('id', '=', $portfolio->id)->get();
        return response([
            'data' => $portfolio,
            'status' => 200
        ]);
    }

    /**
     * @param PortfoliosRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(PortfoliosRequest $request) {
        $portfolio = new Portfolio;
        $portfolio->user_id = $request->user_id;
        $portfolio->titre = $request->titre;
        $portfolio->description = $request->description;
        $portfolio->role = $request->role;
        $portfolio->client = $request->client;
        $portfolio->date_debut = $request->date_debut;
        $portfolio->date_fin = $request->date_fin;
        $portfolio->save();
        return response([
            'data' => $portfolio,
            'status' => 200
        ], 200);
    }

    /**
     * @param PortfoliosRequest $request
     * @param Portfolio $portfolio
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(PortfoliosRequest $request, Portfolio $portfolio) {
        $portfolio->user_id = $request->user_id;
        $portfolio->titre = $request->titre;
        $portfolio->description = $request->description;
        $portfolio->role = $request->role;
        $portfolio->client = $request->client;
        $portfolio->date_debut = $request->date_debut;
        $portfolio->date_fin = $request->date_fin;
        $portfolio->save();
        return response([
            'data' => $portfolio,
            'status' => 200
        ], 200);
    }

    /**
     * @param Portfolio $portfolio
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Portfolio $portfolio) {
        $portfolio->delete();
        return response([
            'data' => $portfolio,
            'status' => 200
        ], 200);
    }

    public function removeImagesPortfolio(Request $request, Portfolio $portfolio, $number_filename){
        if($number_filename < 1 || $number_filename > 3){
            return response([
                'error' => true,
                'status' => 300,
                'message' => 'number_filename doit être compris entre 1 et 3'
            ], 300);
        } else {
            $portfolio = Portfolio::with('media')->where('id', '=', $portfolio->id)->get();
            switch($number_filename){
                case 1:
                    // Récupération du nom de l'image
                    $filename = explode('/' , $portfolio[0]->media[0]->filename);
                    $file = end($filename);
                    // Suppression de l'image sur les server
                    $chemin = public_path() . '/uploads/portfolios/'.$file;
                    if($chemin != public_path() . '/uploads/portfolios/default_portfolio.png') {
                        if (file_exists($chemin)) {
                            unlink($chemin);
                        }
                    }
                    // Update du media en BDD
                    $media = Media::where('id', '=', $portfolio[0]->media[0]->id)->get();
                    $media[0]->filename = $request->root() . '/uploads/portfolios/' . 'default_portfolio.png';
                    $media[0]->save();

                    return response([
                        'media' => $media[0],
                        'portfolio' => $portfolio,
                        'status' => 200,
                        'message' => 'media deleted!'
                    ], 200);
                    break;
                case 2:
                    // Récupération du nom de l'image
                    $filename = explode('/' , $portfolio[0]->media[1]->filename);
                    $file = end($filename);
                    // Suppression de l'image sur les server
                    $chemin = public_path() . '/uploads/portfolios/'.$file;
                    if($chemin != public_path() . '/uploads/portfolios/default_portfolio.png') {
                        if (file_exists($chemin)) {
                            unlink($chemin);
                        }
                    }
                    // Update du media en BDD
                    $media = Media::where('id', '=', $portfolio[0]->media[1]->id)->get();
                    $media[0]->filename = $request->root() . '/uploads/portfolios/' . 'default_portfolio.png';
                    $media[0]->save();

                    return response([
                        'media' => $media[0],
                        'portfolio' => $portfolio,
                        'status' => 200,
                        'message' => 'media deleted!'
                    ], 200);
                    break;
                case 3:
                    // Récupération du nom de l'image
                    $filename = explode('/' , $portfolio[0]->media[2]->filename);
                    $file = end($filename);
                    // Suppression de l'image sur les server
                    $chemin = public_path() . '/uploads/portfolios/'.$file;
                    if($chemin != public_path() . '/uploads/portfolios/default_portfolio.png') {
                        if (file_exists($chemin)) {
                            unlink($chemin);
                        }
                    }
                    // Update du media en BDD
                    $media = Media::where('id', '=', $portfolio[0]->media[2]->id)->get();
                    $media[0]->filename = $request->root() . '/uploads/portfolios/' . 'default_portfolio.png';
                    $media[0]->save();

                    return response([
                        'media' => $media[0],
                        'portfolio' => $portfolio,
                        'status' => 200,
                        'message' => 'media deleted!'
                    ], 200);
                    break;
                default:
                    return response([
                        'error' => true,
                        'status' => 300,
                        'message' => 'number_filename doit être compris entre 1 et 3'
                    ], 300);
                    break;
            }
        }
    }

    /**
     * Multiple File Upload
     * @param Request $request
     * @param Portfolio $portfolio
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function editUploadsPortfolio(Request $request, Portfolio $portfolio, $number_filename)
    {
        $portfolio = Portfolio::with('media')->where('id', '=', $portfolio->id)->get();

        if(count($portfolio[0]->media) === 0){
            // Upload Portfolios Files
            foreach ($request->all() as $file) {
                $destinationPath = public_path() . '/uploads/portfolios/';
                $fileName = 'portfolios_' . strtotime('now') . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $fileName);
                $data[] = $fileName;
            }

            foreach ($data as $key => $item) {
                $media = new Media;
                $media->nom = 'Portfolios';
                $media->filename = $request->root() . '/uploads/portfolios/' . '' . $item;
                $media->save();

                $portfolioMedia = new PortfolioMedia;
                $portfolioMedia->portfolio_id = $portfolio[0]->id;
                $portfolioMedia->media_id = $media->id;
                $portfolioMedia->save();
            }
            return response([
                'media' => $media,
                'portfolioMedia' => $portfolioMedia,
                'status' => 200
            ], 200);

        } else {
            $field = $request->file();

            switch($number_filename){
                case 1:
                    // Récupération du nom de l'image
                    $filename = explode('/', $portfolio[0]->media[0]->filename);
                    $file = end($filename);
                    // Suppression de l'image sur les server
                    $chemin = public_path() . '/uploads/portfolios/' . $file;
                    if ($chemin != public_path() . '/uploads/portfolios/default_portfolio.png') {
                        if (file_exists($chemin)) {
                            unlink($chemin);
                        }
                    }

                    // Upload Portfolios Files
                    $destinationPath = public_path() . '/uploads/portfolios/';
                    $fileName = 'portfolios_' . strtotime('now') . '_' . $field['filename']->getClientOriginalName();
                    $field['filename']->move($destinationPath, $fileName);


                    // Update du media en BDD
                    $media = Media::where('id', '=', $portfolio[0]->media[0]->id)->get();
                    $media[0]->filename = $request->root() . '/uploads/portfolios/' . $fileName;
                    $media[0]->save();

                    return response([
                        'media' => $media[0],
                        'status' => 200
                    ], 200);
                    break;
                case 2:
                    // Récupération du nom de l'image
                    $filename = explode('/', $portfolio[0]->media[0]->filename);
                    $file = end($filename);
                    // Suppression de l'image sur les server
                    $chemin = public_path() . '/uploads/portfolios/' . $file;
                    if ($chemin != public_path() . '/uploads/portfolios/default_portfolio.png') {
                        if (file_exists($chemin)) {
                            unlink($chemin);
                        }
                    }

                    // Upload Portfolios Files
                    $destinationPath = public_path() . '/uploads/portfolios/';
                    $fileName = 'portfolios_' . strtotime('now') . '_' . $field['filename2']->getClientOriginalName();
                    $field['filename2']->move($destinationPath, $fileName);


                    // Update du media en BDD
                    $media = Media::where('id', '=', $portfolio[0]->media[0]->id)->get();
                    $media[0]->filename = $request->root() . '/uploads/portfolios/' . $fileName;
                    $media[0]->save();

                    return response([
                        'media' => $media[0],
                        'status' => 200
                    ], 200);
                    break;
                case 3:
                    // Récupération du nom de l'image
                    $filename = explode('/', $portfolio[0]->media[0]->filename);
                    $file = end($filename);
                    // Suppression de l'image sur les server
                    $chemin = public_path() . '/uploads/portfolios/' . $file;
                    if ($chemin != public_path() . '/uploads/portfolios/default_portfolio.png') {
                        if (file_exists($chemin)) {
                            unlink($chemin);
                        }
                    }

                    // Upload Portfolios Files
                    $destinationPath = public_path() . '/uploads/portfolios/';
                    $fileName = 'portfolios_' . strtotime('now') . '_' . $field['filename3']->getClientOriginalName();
                    $field['filename3']->move($destinationPath, $fileName);


                    // Update du media en BDD
                    $media = Media::where('id', '=', $portfolio[0]->media[0]->id)->get();
                    $media[0]->filename = $request->root() . '/uploads/portfolios/' . $fileName;
                    $media[0]->save();

                    return response([
                        'media' => $media[0],
                        'status' => 200
                    ], 200);
                    break;
                default:
                    return response([
                        'error' => true,
                        'status' => 300,
                        'message' => 'number_filename doit être compris entre 1 et 3'
                    ], 300);
                    break;
            }
        }
    }

    /**
     * @param Request $request
     * @param Portfolio $portfolio
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function uploadsPortfolio(Request $request, Portfolio $portfolio) {
        foreach($request->all() as $file){
            $destinationPath = public_path() . '/uploads/portfolios/';
            $fileName = 'portfolios_'. strtotime('now') . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $data[] = $fileName;
        }

        foreach($data as $key => $item){
            $media = new Media;
            $media->nom = 'Portfolios';
            $media->filename = $request->root().'/uploads/portfolios/'.''.$item;
            $media->save();

            $portfolioMedia = new PortfolioMedia;
            $portfolioMedia->portfolio_id = $portfolio->id;
            $portfolioMedia->media_id = $media->id;
            $portfolioMedia->save();
        }

        return response([
            'media' => $media,
            'portfolioMedia' => $portfolioMedia,
            'status' => 200
        ], 200);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getLastPortfolio(){
        // information divers et varié sur la table format sql => SHOW TABLE STATUS FROM myWebsiteV3 LIKE 'portfolio'
        $sql = DB::select('SHOW TABLE STATUS FROM myWebsiteV3 LIKE \'portfolio\'');
        return response([
            'data' => $sql[0],
            'status' => 200
        ], 200);
    }

    /**
     * @param Portfolio $portfolio
     * @return $this
     * @throws \Exception
     */
    public function getPortfolioImage(Portfolio $portfolio) {
        $portfolio = Portfolio::with('media')->where('id', '=', $portfolio->id)->get();
        if($portfolio){
            foreach($portfolio as $p){
                foreach($p->media as $m){
                    $image[] = array(
                        'portfolio_id' => $m->pivot->portfolio_id,
                        'media_id' => $m->pivot->media_id,
                        'media_nom' => $m->nom,
                        'filename' => $m->filename
                    );
                }
            }
        } else {
            $image = null;
        }
        // AJAX
        $response = new JsonResponse();
        return $response->setData(array(
            'data' => $image,
            'status' => 200
        ));
    }
}
