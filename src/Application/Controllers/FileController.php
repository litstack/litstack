<?php

namespace Fjord\Application\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class FileController extends Controller
{
    /**
     * app.js.
     *
     * @return response
     */
    public function fjordJs()
    {
        return $this->sendFile(fjord_path('public/js/app.js'))
            ->header('Content-Type', 'application/javascript; charset=utf-8');
    }

    /**
     * app2.js.
     *
     * @return response
     */
    public function fjord2Js()
    {
        return $this->sendFile(fjord_path('public/js/app2.js'))
            ->header('Content-Type', 'application/javascript; charset=utf-8');
    }

    /**
     * app.css.
     *
     * @return response
     */
    public function fjordCss()
    {
        return $this->sendFile(fjord_path('public/css/app.css'))
            ->header('Content-Type', 'text/css');
    }

    /**
     * fjord-logo.png.
     *
     * @return response
     */
    public function fjordLogo()
    {
        return $this->sendFile(fjord_path('public/images/fjord-logo.png'))
            ->header('Content-Type', 'image/png');
    }

    /**
     * favicon-32x32.png.
     *
     * @return response
     */
    public function fjordFaviconBig()
    {
        return $this->sendFile(fjord_path('public/favicon/favicon-32x32.png'))
            ->header('Content-Type', 'image/png');
    }

    /**
     * favicon-16x16.png.
     *
     * @return response
     */
    public function fjordFaviconSmall()
    {
        return $this->sendFile(fjord_path('public/favicon/favicon-16x16.png'))
            ->header('Content-Type', 'image/png');
    }

    /**
     * Send file.
     *
     * @param string $path
     *
     * @return void
     */
    protected function sendFile(string $path)
    {
        return response(File::get($path), 200);
        /*->header('Content-Length', $file->get_filesize())
            ->header('Cache-Control', 'max-age=' . $image->get_expires())
            ->header('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + $image->get_expires()))
            ->header('Last-Modified', filemtime($path));*/
    }
}
