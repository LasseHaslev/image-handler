<?php

namespace LasseHaslev\Image\Providers;

use Illuminate\Support\ServiceProvider;
use LasseHaslev\Image\Handlers\CropHandler;
use LasseHaslev\Image\Adaptors\FilenameAdaptor;

use Illuminate\Support\Facades\File;

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

            $handler = CropHandler::create( public_path() );

            $path = $handler->setAdaptor( new FilenameAdaptor )
                ->handle( $url )
                ->save( $url )
                ->getCropsFolder( $url );

            $file = File::get( $path );
            return response( $file, 200 )
                ->header( 'Content-Type', $handler->getMimeType() );

        } )->where( 'path', '(.+)-([0-9_]+)x([0-9_]+)(-resize)*(\-\[([\-\d\.]+)x([\-\d\.]+)\])*\.([A-z]+)$' );
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
