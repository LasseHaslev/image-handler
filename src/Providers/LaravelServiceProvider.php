<?php

namespace LasseHaslev\Image\Providers;

use Illuminate\Support\ServiceProvider;
use LasseHaslev\Image\Handlers\ImageHandler;
use LasseHaslev\Image\Adaptors\FilenameAdaptor;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $router = $this->app[ 'router' ];

        $router->get( '{path}', function( $url ) {

            $cropHandler = new ImageHandler;
            $adaptor = new FilenameAdaptor( 'test-image-160x160-resize.jpg' );
            $this->modifier->useAdaptor( $adaptor )
                ->getCropPath();

            return $cropHandler->handleUrl( $url )
                ->getResponse();
        } )->where( 'path', '(.+)-([0-9_]+)x([0-9_]+)(-[0-9a-zA-Z(),\-._]+)*\.(jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF)$' );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Handle the image url
     *
     * @return void
     */
    public function handleUrl( $url )
    {
    }

    /**
     * Download the original image
     *
     * @return Response
     */
    public function download( $image )
    {
        return response()->download( $image->fullPath, $image->name );
    }
}
