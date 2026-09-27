<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FilePondController extends Controller {
    public function store(Request $request) {
        $folder = uniqid().'-'.now()->timestamp;
        mkdir(storage_path('app/temp/'));
        mkdir(storage_path('app/temp/'.$folder));

        file_put_contents(storage_path('app/temp/'.$folder.'/file.part'), '');

        return $folder;
    }

    public function update(Request $request) {
        $path = storage_path('app/temp/'.$request->query('patch').'/file.part');

        File::append($path, $request->getContent());

        // The code below should probably be going into a POST controller method
        // As the documentation recommends us to actually move the file once it's done.
        if (filesize($path) == $request->header('Upload-Length')) {
            $filename = storage_path('updates/').'update.zip';
            if (file_exists($filename)) {
                unlink($filename);
            }
            File::move($path, $filename);
        }

        return response()->json(['uploaded' => true]);
    }
}
