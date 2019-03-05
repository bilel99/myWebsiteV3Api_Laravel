<?php
/**
 * Created by PhpStorm.
 * User: bilel
 * Date: 02/03/2019
 * Time: 00:27
 */

namespace App\Http\Controllers\Api;


use App\Blog;
use App\Media;
use App\BlogMedia;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class BlogController extends Controller {


    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index(){
        $blog = Blog::with('users', 'langue', 'media')->get();
        return response([
            'data' => $blog,
            'status' => 200,
        ], 200);
    }

    /**
     * @param BlogRequest $blogRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(BlogRequest $blogRequest){
        $blog = new Blog;
        $blog->user_id = $blogRequest->user_id;
        $blog->langue_id = $blogRequest->langue_id;
        $blog->titre = $blogRequest->titre;
        $blog->introduction = $blogRequest->introduction;
        $blog->description = $blogRequest->description;
        $blog->save();
        return response([
            'data' => $blog,
            'message' => 'blog created',
            'status' => 200
        ], 200);
    }

    /**
     * @param Blog $blog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function show(Blog $blog){
        $blog = Blog::with('users', 'langue', 'media')->where('id', '=', $blog->id)->get();
        return response([
            'data' => $blog,
            'status' => 200
        ], 200);
    }

    /**
     * @param Blog $blog
     * @param BlogRequest $blogRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Blog $blog, BlogRequest $blogRequest){
        $blog->user_id = $blogRequest->user_id;
        $blog->langue_id = $blogRequest->langue_id;
        $blog->titre = $blogRequest->titre;
        $blog->introduction = $blogRequest->introduction;
        $blog->description = $blogRequest->description;
        $blog->save();
        return response([
            'data' => $blog,
            'message' => 'blog updated',
            'status' => 200
        ], 200);
    }

    /**
     * @param Blog $blog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Blog $blog){
        /* Suppression des images en bdd,
         * table media + fichier **/
        $blog = Blog::with('media')->where('id', '=', $blog->id)->get();
        if(count($blog[0]->media) > 0){
            foreach($blog[0]->media as $key => $row){
                // Récupération du nom de l'image
                $filename = explode('/' , $row->filename);
                $file = end($filename);
                // Suppression de l'image sur les server
                $chemin = public_path() . '/uploads/blog/'.$file;
                if($chemin != public_path() . '/uploads/blog/default_blog.png') {
                    if (file_exists($chemin)) {
                        unlink($chemin);
                    }
                }
                // Delete du media en BDD
                $media = Media::where('id', '=', $row->id)->get();
                $media[0]->delete();
            }
        }
        $blog[0]->delete();
        return response([
            'data' => $blog[0],
            'message' => 'blog deleted and media deleted!',
            'status' => 200
        ], 200);
    }

    /**
     * @param Request $request
     * @param Blog $blog
     * @param $number_filename
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function removeImagesBlog(Request $request, Blog $blog, $number_filename){
        if($number_filename < 1 || $number_filename > 3){
            return response([
                'error' => true,
                'status' => 300,
                'message' => 'number_filename doit être compris entre 1 et 3'
            ], 300);
        } else {
            $blog = Blog::with('media')->where('id', '=', $blog->id)->get();
            switch($number_filename){
                case 1:
                    // Récupération du nom de l'image
                    $filename = explode('/' , $blog[0]->media[0]->filename);
                    $file = end($filename);
                    // Suppression de l'image sur les server
                    $chemin = public_path() . '/uploads/blog/'.$file;
                    if($chemin != public_path() . '/uploads/blog/default_blog.png') {
                        if (file_exists($chemin)) {
                            unlink($chemin);
                        }
                    }
                    // Delete du media en BDD
                    $media = Media::where('id', '=', $blog[0]->media[0]->id)->get();
                    $media[0]->delete();

                    return response([
                        'media' => $media[0],
                        'blog' => $blog,
                        'status' => 200,
                        'message' => 'media deleted!'
                    ], 200);
                    break;
                case 2:
                    // Récupération du nom de l'image
                    $filename = explode('/' , $blog[0]->media[1]->filename);
                    $file = end($filename);
                    // Suppression de l'image sur les server
                    $chemin = public_path() . '/uploads/blog/'.$file;
                    if($chemin != public_path() . '/uploads/blog/default_blog.png') {
                        if (file_exists($chemin)) {
                            unlink($chemin);
                        }
                    }
                    // Update du media en BDD
                    $media = Media::where('id', '=', $blog[0]->media[1]->id)->get();
                    $media[0]->delete();

                    return response([
                        'media' => $media[0],
                        'blog' => $blog,
                        'status' => 200,
                        'message' => 'media deleted!'
                    ], 200);
                    break;
                case 3:
                    // Récupération du nom de l'image
                    $filename = explode('/' , $blog[0]->media[2]->filename);
                    $file = end($filename);
                    // Suppression de l'image sur les server
                    $chemin = public_path() . '/uploads/blog/'.$file;
                    if($chemin != public_path() . '/uploads/blog/default_blog.png') {
                        if (file_exists($chemin)) {
                            unlink($chemin);
                        }
                    }
                    // Update du media en BDD
                    $media = Media::where('id', '=', $blog[0]->media[2]->id)->get();
                    $media[0]->delete();

                    return response([
                        'media' => $media[0],
                        'blog' => $blog,
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
     * @param Blog $blog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function editUploadsBlog(Request $request, Blog $blog, $number_filename)
    {
        $blog = Blog::with('media')->where('id', '=', $blog->id)->get();

        $field = $request->file();
        switch($number_filename){
            case 1:
                // Vérification
                if(isset($blog[0]->media[0])){
                    // Récupération du nom de l'image
                    $filename = explode('/', $blog[0]->media[0]->filename);
                    $file = end($filename);
                    // Suppression de l'image sur les server
                    $chemin = public_path() . '/uploads/blog/' . $file;
                    if ($chemin != public_path() . '/uploads/blog/default_blog.png') {
                        if (file_exists($chemin)) {
                            unlink($chemin);
                        }
                    }

                    // Upload Blog Files
                    $destinationPath = public_path() . '/uploads/blog/';
                    $fileName = 'blog_' . strtotime('now') . '_' . $field['filename']->getClientOriginalName();
                    $field['filename']->move($destinationPath, $fileName);

                    // Update du media en BDD
                    $media = Media::where('id', '=', $blog[0]->media[0]->id)->get();
                    $media[0]->filename = $request->root() . '/uploads/blog/' . $fileName;
                    $media[0]->save();

                    return response([
                        'media' => $media[0],
                        'status' => 200
                    ], 200);
                    break;
                } else {
                    // Upload Blog Files
                    $destinationPath = public_path() . '/uploads/blog/';
                    $fileName = 'blog_' . strtotime('now') . '_' . $field['filename']->getClientOriginalName();
                    $field['filename']->move($destinationPath, $fileName);

                    // Update du media en BDD
                    $media = new Media;
                    $media->nom = 'Blog';
                    $media->filename = $request->root() . '/uploads/blog/' . '' . $fileName;
                    $media->save();

                    $blogMedia = new BlogMedia;
                    $blogMedia->blog_id = $blog[0]->id;
                    $blogMedia->media_id = $media->id;
                    $blogMedia->save();

                    return response([
                        'media' => $media,
                        'blogMedia' => $blogMedia,
                        'status' => 200
                    ], 200);
                    break;
                }
            case 2:
                // Vérification
                if(isset($blog[0]->media[1])){
                    // Récupération du nom de l'image
                    $filename = explode('/', $blog[0]->media[1]->filename);
                    $file = end($filename);

                    // Suppression de l'image sur les server
                    $chemin = public_path() . '/uploads/blog/' . $file;
                    if ($chemin != public_path() . '/uploads/blog/default_blog.png') {
                        if (file_exists($chemin)) {
                            unlink($chemin);
                        }
                    }

                    // Upload Blog Files
                    $destinationPath = public_path() . '/uploads/blog/';
                    $fileName = 'blog_' . strtotime('now') . '_' . $field['filename2']->getClientOriginalName();
                    $field['filename2']->move($destinationPath, $fileName);

                    // Update du media en BDD
                    $media = Media::where('id', '=', $blog[0]->media[1]->id)->get();
                    $media[0]->filename = $request->root() . '/uploads/blog/' . $fileName;
                    $media[0]->save();

                    return response([
                        'media' => $media[0],
                        'status' => 200
                    ], 200);
                    break;
                } else {
                    // Upload Blog Files
                    $destinationPath = public_path() . '/uploads/blog/';
                    $fileName = 'blog_' . strtotime('now') . '_' . $field['filename2']->getClientOriginalName();
                    $field['filename2']->move($destinationPath, $fileName);

                    // Update du media en BDD
                    $media = new Media;
                    $media->nom = 'Blog';
                    $media->filename = $request->root() . '/uploads/blog/' . '' . $fileName;
                    $media->save();

                    $blogMedia = new BlogMedia;
                    $blogMedia->blog_id = $blog[0]->id;
                    $blogMedia->media_id = $media->id;
                    $blogMedia->save();

                    return response([
                        'media' => $media,
                        'blogMedia' => $blogMedia,
                        'status' => 200
                    ], 200);
                    break;
                }
            case 3:
                // Vérification
                if(isset($blog[0]->media[2])){
                    // Récupération du nom de l'image
                    $filename = explode('/', $blog[0]->media[2]->filename);
                    $file = end($filename);
                    // Suppression de l'image sur les server
                    $chemin = public_path() . '/uploads/blog/' . $file;
                    if ($chemin != public_path() . '/uploads/blog/default_blog.png') {
                        if (file_exists($chemin)) {
                            unlink($chemin);
                        }
                    }

                    // Upload Blog Files
                    $destinationPath = public_path() . '/uploads/blog/';
                    $fileName = 'blog_' . strtotime('now') . '_' . $field['filename3']->getClientOriginalName();
                    $field['filename3']->move($destinationPath, $fileName);

                    // Update du media en BDD
                    $media = Media::where('id', '=', $blog[0]->media[2]->id)->get();
                    $media[0]->filename = $request->root() . '/uploads/blog/' . $fileName;
                    $media[0]->save();

                    return response([
                        'media' => $media[0],
                        'status' => 200
                    ], 200);
                    break;
                } else {
                    // Upload Blog Files
                    $destinationPath = public_path() . '/uploads/blog/';
                    $fileName = 'blog_' . strtotime('now') . '_' . $field['filename3']->getClientOriginalName();
                    $field['filename3']->move($destinationPath, $fileName);

                    // Update du media en BDD
                    $media = new Media;
                    $media->nom = 'Blog';
                    $media->filename = $request->root() . '/uploads/blog/' . '' . $fileName;
                    $media->save();

                    $blogMedia = new BlogMedia;
                    $blogMedia->blog_id = $blog[0]->id;
                    $blogMedia->media_id = $media->id;
                    $blogMedia->save();

                    return response([
                        'media' => $media,
                        'blogMedia' => $blogMedia,
                        'status' => 200
                    ], 200);
                    break;
                }
            default:
                return response([
                    'error' => true,
                    'status' => 300,
                    'message' => 'number_filename doit être compris entre 1 et 3'
                ], 300);
                break;
        }
    }

    /**
     * @param Request $request
     * @param Blog $blog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function uploadsBlog(Request $request, Blog $blog) {
        foreach($request->all() as $file){
            $destinationPath = public_path() . '/uploads/blog/';
            $fileName = 'blog_'. strtotime('now') . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $data[] = $fileName;
        }

        foreach($data as $key => $item){
            $media = new Media;
            $media->nom = 'Blog';
            $media->filename = $request->root().'/uploads/blog/'.''.$item;
            $media->save();

            $blogMedia = new BlogMedia;
            $blogMedia->blog_id = $blog->id;
            $blogMedia->media_id = $media->id;
            $blogMedia->save();
        }

        return response([
            'media' => $media,
            'blogMedia' => $blogMedia,
            'status' => 200
        ], 200);
    }

    /**
     * @param Blog $blog
     * @return $this
     * @throws \Exception
     */
    public function getBlogMedia(Blog $blog) {
        $blog = Blog::with('media')->where('id', '=', $blog->id)->get();
        if($blog){
            foreach($blog as $b){
                foreach($b->media as $m){
                    $image[] = array(
                        'blog_id' => $m->pivot->blog_id,
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
        ), 200);
    }
}