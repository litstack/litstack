<?php

namespace AwStudio\Fjord\Fjord\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use File;

class FileController extends Controller
{
    public function fjordJs()
    {
        return $this->sendFile(fjord_path('public/js/app.js'))
            ->header('Content-Type', 'application/javascript; charset=utf-8');
    }

    public function fjordCss()
    {
        return $this->sendFile(fjord_path('public/css/app.css'))
            ->header('Content-Type', 'text/css');
    }

    public function fjordLogo()
    {
        return $this->sendFile(fjord_path('public/images/fjord-logo.png'))
            ->header('Content-Type', 'image/png');
    }

    protected function sendFile($path)
    {
        return response(File::get($path), 200);
            /*->header('Content-Length', $file->get_filesize())
            ->header('Cache-Control', 'max-age=' . $image->get_expires())
            ->header('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + $image->get_expires()))
            ->header('Last-Modified', filemtime($path));*/
    }
}
