<?php

namespace LasseHaslev\Image\Handlers;

use App\Modules\Images\Image;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Modules\Images\Modifiers\ImageModifier;

/**
 * Class ImageCropHandler
 * @author Lasse S. Haslev
 */
class ImageCropHandler
{

    protected $modifier;
    protected $fullImagePath;
    protected $imageName;

    /**
     * Handle the image url
     *
     * @return void
     */
    public function handleUrl( $url )
    {

        // Get data
        list( $name, $width, $height, $resize, $extension ) = $this->getData( $url );

        // Get the image object
        $image = Image::where( 'path', $name . $extension )
            ->first();

        return $this->handle( $image, $width, $height, $resize );


    }


    /**
     * Handle image crop
     *
     * @return void
     */
    public function handle( Image $image, $width = null, $height = null, $resize = false )
    {

        // Create the image modifier
        $this->modifier = ImageModifier::create( $image->fullPath );

        // Create the new url
        // $newName = url( basename( $url ) );
        $this->imageName = $this->buildImageName( $image, $width, $height, $resize );
        $this->fullImagePath = public_path( sprintf( 'uploads/images/%s', $this->imageName ) );

        // Prevent processing of image if it already exists
        if ( File::exists( $this->fullImagePath ) ) {
            return $this;
        }


        // Check if we should resize or crop
        // If one of the width or height is _ This is still a resize
        if ( $resize || ( !$width || !$height ) ) {
            $this->modifier
                ->resize( $width, $height );
        }
        else {
            $this->modifier
                ->cropToFit( $width, $height, $image->focus_point_x, $image->focus_point_y );
        }

        // Save the new image and return the response as image
        $this->modifier
            ->save( $this->fullImagePath )
            ->destroy();

        // Display Image
        return $this;
    }

    /**
     * Get the image as a response
     *
     * @return Response
     */
    public function getResponse()
    {
            return $this->modifier->getResponse( $this->fullImagePath );
    }

    /**
     * Get the full Image path
     *
     * @return String
     */
    public function fullImagePath()
    {
        return $this->fullImagePath;
    }



    /**
     * Get the information out of image url
     *
     * @return Array
     */
    protected function getData( $url )
    {

        $matches = [];

        preg_match( '/([A-z0-9]+)-([0-9_]+)x([0-9_]+)(-[0-9a-zA-Z(),\-._]+)*(\..+)/', basename( $url ), $matches );

        return [
            $matches[ 1 ], // Name

            // If the value is _ we returns null
            $matches[ 2 ] != '_' ? $matches[ 2 ] : null, // Width
            $matches[ 3 ] != '_' ? $matches[ 3 ] : null, // Height

            $matches[ 4 ] == '-resize', // Resize
            $matches[ 5 ], // Extension
        ];

    }

    /**
     * Build the image name ( {name}-{width}x{height}-resize
     *
     * @return string
     */
    protected function buildImageName( Image $image, $width, $height, $resize )
    {

        // Modify values
        $width = $width ?: '_';
        $height = $height ?: '_';
        $resize = $resize ? '-resize' : '';

        $matches = [];
        $replaceString = sprintf( '$1-%sx%s%s$2', $width, $height, $resize ); // $1 = filename and $2 = extension
        return preg_replace( '/^([A-z0-9]+)(\.[A-z]+)/', $replaceString, $image->getOriginal( 'path' ) );
    }

}
