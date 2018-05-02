<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PortfoliosRequest;
use App\Media;
use App\Portfolio;
use App\Http\Controllers\Controller;
use App\PortfolioMedia;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PortfoliosController extends Controller
{

    public function index() {
        $porfolios = Portfolio::with('media')->get();
        return response([
            'data' => $porfolios,
            'status' => 200
        ]);
    }

    public function show(Portfolio $portfolio){
        $portfolio = Portfolio::with('media')->where('id', '=', $portfolio->id)->get();
        return response([
            'data' => $portfolio,
            'status' => 200
        ]);
    }

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

    public function destroy(Portfolio $portfolio) {
        $portfolio->delete();
        return response([
            'data' => $portfolio,
            'status' => 200
        ], 200);
    }

    public function uploadsPortfolio(Request $request, Portfolio $portfolio) {
        /*$this->validate($request, [
            'filename' => 'required',
            'filename.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);*/
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

    public function getLastPortfolio(Portfolio $portfolio){
        $portfolio = Portfolio::orderBy('id', 'desc')->limit(1)->get();
        return response([
            'data' => $portfolio,
            'status' => 200
        ], 200);
    }

    public function getPortfolioImage(Portfolio $portfolio) {
        $portfolio = Portfolio::with('media')->where('id', '=', $portfolio->id)->get();
        if($portfolio){
            foreach($portfolio as $p){
                foreach($p->media as $m){
                    $image = array(
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
