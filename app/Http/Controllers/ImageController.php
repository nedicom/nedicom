<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Article;
use App\Models\Uslugi;
use App\Models\Image;


class ImageController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        if ($req->file()) {
            $pagetype = $req->pagetype;
            $id = $req->id;

            if ($pagetype == 'profile') {
                $filePath = 'usr/' . Auth::user()->id . '/profileimg';
                $fileName = time() . 'profile';
                $user = User::find(Auth::user()->id);
                $user->file_path = 'storage/' . $filePath . '/' . $fileName . '.webp';
                $user->save();
            } elseif ($pagetype == 'profileavatar') {
                $fileName = time() . 'avatar';
                $filePath = 'usr/' . Auth::user()->id . '/avatar';
                $user = User::find(Auth::user()->id);
                $user->avatar_path = 'storage/' . $filePath . '/' . $fileName . '.webp';
                $user->save();
            } elseif ($pagetype == 'article') {
                $fileName = time() . '_' . $req->file->getClientOriginalName();
                $filePath = 'usr/' . Auth::user()->id . '/articleimages/' . $id;
                $article = Article::find($id);
                $article->practice_file_path = 'storage/' . $filePath . '/' . $fileName . '.webp';
                $article->save();
            } else {
                return redirect()->back();
            }

            if (!Storage::exists($filePath)) {
                Storage::makeDirectory($filePath);
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $req->file('file'));
            finfo_close($finfo);

            if ($mime == "image/png") {
                $im = imagecreatefrompng($req->file('file'));
            } else if ($mime == "image/jpeg") {
                $im = imagecreatefromjpeg($req->file('file'));
            } else {
                return redirect()->back();
            }

            $files = Storage::allFiles($filePath);
            Storage::delete($files);

            //Storage::disk('public')->put($filePath, $req->file('file'));
            imagewebp($im, 'storage/' . $filePath . '/' . $fileName . '.webp', 80);
            //Image::make($im)->encode('webp', 80)->save(public_path('storage/'.$filePath.'/'.$fileName.'.webp'));
            imagedestroy($im);
            
            /* for database 
                            $imgModel = new Image;
                            $imgModel->user_id = Auth::user()->id;
                            $imgModel->name = $fileName;
                            $imgModel->file_path = $newfilepath;
                            $imgModel->ext = $req->file->getClientMimeType();
                            $imgModel->size = $req->file->getSize();
                            $imgModel->save();
            */

            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    /**
     * Store an image for usluga type
     *
     * @param  \App\Http\Requests\StoreImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function imguslcreate(StoreImageRequest $req)
    {
        if ($req->file()) {
            $pagetype = $req->pagetype;
            $id = $req->id;
            
            if ($pagetype == 'mobileusluga') {
                $filePath = 'uslugi/' . $id. '/pc'.'/';
                $fileName = time() . 'usluga';
                $usluga = Uslugi::find($id);
                $usluga->file_path = 'storage/' . $filePath . $fileName . '.webp';
                $usluga->save();
            }
            else{
                $filePath = 'uslugi/' . $id. '/mobile'.'/';
                $fileName = time() . 'usluga';
                $usluga = Uslugi::find($id);
                $usluga->file_path = 'storage/' . $filePath . $fileName . '.webp';
                $usluga->save();
            }
                
                if (!Storage::exists($filePath)) {
                    Storage::makeDirectory($filePath);
                }
    
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                
                $mime = finfo_file($finfo, $req->file('file'));
                finfo_close($finfo);
                
    
                if ($mime == "image/png") {
                    $im = imagecreatefrompng($req->file('file'));
                } else if ($mime == "image/jpeg") {
                    $im = imagecreatefromjpeg($req->file('file'));
                } else {
                    return redirect()->back();
                }
    
                $files = Storage::allFiles($filePath);                
                Storage::delete($files);
                //$imgwebppath = 'storage/' . $filePath . '/' . $fileName . '.webp';
                $imgwebppath = $filePath.$fileName . '.webp';                

                Storage::disk('public')->put($filePath.$fileName . '.webp', '');

                imagewebp($im, 'storage/' . $imgwebppath, 80);
               
                imagedestroy($im);   

        }
        else{
            return redirect()->back();
        }
        return redirect()->back();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateImageRequest  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateImageRequest $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        //
    }
}
