<?php

namespace Ignite\Application\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class FileController extends Controller
{
    /**
     * app.js.
     *
     * @return Response
     */
    public function litJs()
    {
        return $this->sendFile(lit_vendor_path('public/js/app.js'))
            ->header('Content-Type', 'application/javascript; charset=utf-8');
    }

    /**
     * app2.js.
     *
     * @return Response
     */
    public function lit2Js()
    {
        return $this->sendFile(lit_vendor_path('public/js/app2.js'))
            ->header('Content-Type', 'application/javascript; charset=utf-8');
    }

    /**
     * prism.js.
     *
     * @return Response
     */
    public function prismJs()
    {
        return $this->sendFile(lit_vendor_path('public/js/prism.js'))
            ->header('Content-Type', 'application/javascript; charset=utf-8');
    }

    /**
     * app.css.
     *
     * @return Response
     */
    public function litCss()
    {
        return $this->sendFile(lit_vendor_path('public/css/app.css'))
            ->header('Content-Type', 'text/css');
    }

    /**
     * lit-logo.png.
     *
     * @return Response
     */
    public function litLogo()
    {
        return $this->sendFile(lit_vendor_path('public/images/logo.png'))
            ->header('Content-Type', 'image/png');
    }

    /**
     * favicon-32x32.png.
     *
     * @return Response
     */
    public function litFaviconBig()
    {
        return $this->sendFile(lit_vendor_path('public/favicon/favicon-32x32.png'))
            ->header('Content-Type', 'image/png');
    }

    /**
     * favicon-16x16.png.
     *
     * @return Response
     */
    public function litFaviconSmall()
    {
        return $this->sendFile(lit_vendor_path('public/favicon/favicon-16x16.png'))
            ->header('Content-Type', 'image/png');
    }

    /**
     * Send file.
     *
     * @param  string   $path
     * @return Response
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
