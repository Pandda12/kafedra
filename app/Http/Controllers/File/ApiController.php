<?php

declare(strict_types=1);

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiController extends Controller
{
    public function downloadFile( Request $request ): StreamedResponse
    {
        $fileId = (int)$request->input('file');

        $file = File::findOrFail($fileId);

        return Storage::download($file->path);
    }
}
