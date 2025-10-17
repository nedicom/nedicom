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
use Illuminate\Support\Facades\Log;
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
        Log::info('Image upload started', [
            'pagetype' => $req->pagetype,
            'id' => $req->id,
            'has_file' => $req->hasFile('file'),
            'user_id' => Auth::id()
        ]);

        if ($req->file()) {
            try {
                $pagetype = $req->pagetype;
                $id = $req->id;

                Log::debug('File details', [
                    'file_name' => $req->file('file')->getClientOriginalName(),
                    'file_size' => $req->file('file')->getSize(),
                    'file_mime' => $req->file('file')->getMimeType()
                ]);

                if ($pagetype == 'profile') {
                    $filePath = 'usr/' . Auth::user()->id . '/profileimg';
                    $fileName = time() . 'profile';
                    $user = User::find(Auth::user()->id);
                    $user->file_path = 'storage/' . $filePath . '/' . $fileName . '.webp';
                    $user->save();
                    
                    Log::info('Profile image processing', [
                        'file_path' => $filePath,
                        'file_name' => $fileName,
                        'user_id' => Auth::id()
                    ]);

                } elseif ($pagetype == 'profileavatar') {
                    $fileName = 'avatar';
                    $filePath = 'usr/' . Auth::user()->id . '/avatar';
                    $user = User::find(Auth::user()->id);
                    $user->avatar_path = 'storage/' . $filePath . '/' . $fileName . '.webp';
                    $user->save();
                    
                    Log::info('Profile avatar processing', [
                        'file_path' => $filePath,
                        'file_name' => $fileName,
                        'user_id' => Auth::id()
                    ]);

                } elseif ($pagetype == 'article') {
                    $fileName = time() . '_' . $req->file->getClientOriginalName();
                    $filePath = 'usr/' . Auth::user()->id . '/articleimages/' . $id;
                    $article = Article::find($id);
                    $article->practice_file_path = 'storage/' . $filePath . '/' . $fileName . '.webp';
                    $article->save();
                    
                    Log::info('Article image processing', [
                        'file_path' => $filePath,
                        'file_name' => $fileName,
                        'article_id' => $id,
                        'user_id' => Auth::id()
                    ]);
                } else {
                    Log::warning('Unknown pagetype', ['pagetype' => $pagetype]);
                    return redirect()->back();
                }

                // Проверяем и создаем директорию
                if (!Storage::exists($filePath)) {
                    Log::info('Creating directory', ['directory' => $filePath]);
                    Storage::makeDirectory($filePath);
                } else {
                    Log::debug('Directory already exists', ['directory' => $filePath]);
                }

                // Определяем MIME тип
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $req->file('file'));
                finfo_close($finfo);

                Log::debug('MIME type detected', ['mime_type' => $mime]);

                if ($mime == "image/png") {
                    Log::debug('Processing PNG image');
                    $im = imagecreatefrompng($req->file('file'));
                } else if ($mime == "image/jpeg") {
                    Log::debug('Processing JPEG image');
                    $im = imagecreatefromjpeg($req->file('file'));
                } else {
                    Log::warning('Unsupported file type', ['mime_type' => $mime]);
                    return redirect()->back();
                }

                // Удаляем старые файлы
                $files = Storage::allFiles($filePath);
                Log::debug('Deleting old files', [
                    'file_count' => count($files),
                    'files' => $files
                ]);
                Storage::delete($files);

                // Сохраняем новое изображение
                $fullPath = 'storage/' . $filePath . '/' . $fileName . '.webp';
                Log::debug('Saving new image', ['full_path' => $fullPath]);

                $success = imagewebp($im, $fullPath, 80);
                
                if ($success) {
                    Log::info('Image saved successfully', ['path' => $fullPath]);
                } else {
                    Log::error('Failed to save image', ['path' => $fullPath]);
                }

                imagedestroy($im);

                // Проверяем, что файл действительно создан
                if (file_exists($fullPath)) {
                    $fileSize = filesize($fullPath);
                    Log::info('File verification passed', [
                        'path' => $fullPath,
                        'size' => $fileSize
                    ]);
                } else {
                    Log::error('File not created after imagewebp', ['path' => $fullPath]);
                }

                Log::info('Image upload completed successfully');
                return redirect()->back();

            } catch (\Exception $e) {
                Log::error('Image upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'pagetype' => $pagetype ?? 'unknown',
                    'user_id' => Auth::id()
                ]);
                return redirect()->back();
            }
        } else {
            Log::warning('No file in request', [
                'request_data' => $req->all(),
                'files' => $req->allFiles()
            ]);
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
                $filePath = 'uslugi/' . $id . '/pc' . '/';
                $fileName = time() . 'usluga';
                $usluga = Uslugi::find($id);
                $usluga->mob_file_path = 'storage/' . $filePath . $fileName . '.webp';
                $usluga->save();
            } else {
                $filePath = 'uslugi/' . $id . '/mobile' . '/';
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
            $imgwebppath = $filePath . $fileName . '.webp';

            Storage::disk('public')->put($filePath . $fileName . '.webp', '');

            imagewebp($im, 'storage/' . $imgwebppath, 80);

            imagedestroy($im);
        } else {
            return redirect()->back();
        }
        return redirect()->back();
    }


    /**
     * Store an image in square form
     *
     * @param  \App\Http\Requests\StoreImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function square(StoreImageRequest $req)
    {
        if ($req->file()) {
            
            $file = $req->file('file');

            if ($file->extension() != "png") {
                return redirect()->back()->with('message', 'Только png формат');
            }

            if ($file->getSize() > 1000000) {
                return redirect()->back()->with('message', 'Размер не больше 1 мегабайт');
            };

            $filePath = 'uslugi/' . $req->id . '/square';

            $files = Storage::allFiles($filePath);
            Storage::delete($files);

            Storage::putFileAs($filePath, $req->file('file'), "1." . $file->extension());
        } else {
            return redirect()->back()->with('message', 'Что-то пошло не так');
        }
        return redirect()->back()->with('message', 'Изображение загружено успешно');
    }
}
