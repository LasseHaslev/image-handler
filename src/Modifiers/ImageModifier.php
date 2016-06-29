<?php

namespace LasseHaslev\Image\Modifiers;

use Exception;
use Illuminate\Support\Facades\File;

/**
 * Class ImageModifier
 * @author Lasse S. Haslev
 */
class ImageModifier
{

    protected $originalImagePath;
    protected $image;
    protected $imageType;

    /**
     * Quickly create a new instance
     *
     * @return ImageModifier instance
     */
    public static function create( $path )
    {
        return new static( $path );
    }


    /**
     * @param string $originalImagePath
     */
    public function __construct( $originalImagePath )
    {
        $this->originalImagePath = $originalImagePath;

        $this->createImageObject();

    }

    /**
     * Reset image object to originalImagePath
     *
     * @return static
     */
    public function reset()
    {

        $this->destroy();

        $this->createImageObject();

        return $this;
    }


    /**
     * Get the mime type of the working image
     *
     * @return String
     */
    public function mimeType()
    {
        return image_type_to_mime_type( $this->imageType );
    }


    /**
     * Create GD Image object based on the mime_type
     *
     * @return GD object resource
     */
    protected function createImageObject()
    {

        // Check if the file is a valid file
        if ( ! is_file( $this->originalImagePath ) ) {
            throw new Exception( sprintf( '%s: No image found.', $this->originalImagePath ) );
        }

        // Get the mimetype
        $this->imageType = exif_imagetype( $this->originalImagePath );

        // Get the width and height of image
        list($this->width, $this->height) = getimagesize($this->originalImagePath);


        // Create GD object based on the mime type
        switch ( $this->imageType ) {
            case IMAGETYPE_GIF:
                $this->image = imagecreatefromgif( $this->originalImagePath );
                break;

            case IMAGETYPE_PNG:
                $this->image = @imagecreatefrompng( $this->originalImagePath );
                break;

            case IMAGETYPE_JPEG:
                $this->image = @imagecreatefromjpeg( $this->originalImagePath );
                break;

            case IMAGETYPE_BMP:
                $this->image = imagecreatefromwbmp( $this->originalImagePath );
                break;

            // If the image is not one of the previous mimetype
            // We throw an exception
            default:
                throw new Exception( sprintf( 'This MimeType is not supported: %s. Image processed: %s', image_type_to_mime_type( $this->imageType ), $this->originalImagePath ) );
                break;
        }

        // Check if the object is successfully opened
        if( ! $this->image ) {
            throw new Exception( sprintf( 'We could not open this image: %s', $this->originalImagePath ) );
        }

    }

    /**
     * Save the working image to the new path
     *
     * @return $this
     */
    public function save($path)
    {

        switch ( $this->imageType ) {
            case IMAGETYPE_GIF:
                $returnData = imagegif( $this->image, $path );
                break;

            case IMAGETYPE_PNG:
                $returnData = imagepng( $this->image, $path );
                break;

            case IMAGETYPE_JPEG:
                $returnData = imagejpeg( $this->image, $path );
                break;

            case IMAGETYPE_BMP:
                $returnData = imagewbmp( $this->image, $path );
                break;

            // If the image is not one of the previous mimetype
            // We throw an exception
            default:
                throw new Exception( sprintf( 'This MimeType is not supported: %s. Image processed: %s', image_type_to_mime_type( $this->imageType ), $this->originalImagePath ) );
                break;
        }

        // Return this for chaning
        return $this;
    }

    /**
     * Crop image To the width and height of the image
     *
     * @return $this
     */
    public function cropToFit($width, $height, $focusPointX = 0, $focusPointY = 0)
    {

        // Check if the image is a valid resource
        if (!is_resource($this->image)) {
            throw new RuntimeException('No image set');
        }

        // Get the width and height of element
        $originalWidth = imagesx( $this->image );
        $originalHeight = imagesy( $this->image );

        // Check if any of the width and height is null, and set it to the $source size
        $width = $width ?: $originalWidth;
        $height = $height ?: $originalHeight;

        // Calculate the aspect ratio of the existing image
        $originalAspectRatio = $originalWidth / $originalHeight;

        // Calculate the aspect ratio of the desired image
        $desiredAspectRatio = $width / $height;

        // Check what value we should use with the aspect ratio
        if ( $originalAspectRatio > $desiredAspectRatio ) {
            $temp_height = $height;
            $temp_width = ( int ) ($height * $originalAspectRatio);
        }
        else {
            $temp_width = $width;
            $temp_height = ( int ) ($width / $originalAspectRatio);
        }

        // Resize the image into the desired image
        $this->resize( $temp_width, $temp_height );

        // Get the center of image based on the focusPoint
        $x0 = ( $temp_width - $width ) * ( ( $focusPointX + 1 ) * .5 );
        $y0 = ( $temp_height - $height ) * ( ( $focusPointY + 1 ) * .5 );

        // Create canvas for the new image
        $canvas = $this->createCanvas( $width, $height );

        // Do the cropping of image
        imagecopy( $canvas, $this->image, 0, 0, $x0, $y0, $width, $height );

        // Replace the image
        $this->replace( $canvas );

        // Return $this for chaining
        return $this;
    }


    public function crop( $x1, $y1, $x2, $y2 ) {

        // Check if the image is a valid resource
        if (!is_resource($this->image)) {
            throw new RuntimeException('No image set');
        }

        // Check if the first parameter is an array
        // and Then convert to crop positions
        if (is_array($x1) && 4 == count($x1)) {
            list($x1, $y1, $x2, $y2) = $x1;
        }

        // Get the max value
        $x1 = max($x1, 0);
        $y1 = max($y1, 0);

        // Get the minimum value
        $x2 = min($x2, $this->width);
        $y2 = min($y2, $this->height);

        // Calculate the width and height
        $width = $x2 - $x1;
        $height = $y2 - $y1;

        // Do the cropping
        $temp = $this->createCanvas($width, $height);
        imagecopy($temp, $this->image, 0, 0, $x1, $y1, $width, $height);

        // Replace the image
        $this->replace($temp);

        // Return $this for chaining
        return $this;

    }

    /**
     * Resize the GD Image
     *
     * @return $this
     */
    public function resize( $width = null, $height = null )
    {

        // Get the source width and height
        $sourceWidth = imagesx( $this->image );
        $sourceHeight = imagesy( $this->image );

        // Check if any of the width and height is null, and set it to the $source size
        $width = $width ?: $sourceWidth;
        $height = $height ?: $sourceHeight;

        $maxCanvasWidth = $width;
        $maxCanvasHeight = $height;

        $sourceAspectRatio = $sourceWidth / $sourceHeight;
        $thumbnailAspectRatio = $maxCanvasWidth / $maxCanvasHeight;

        // Find the right aspect ratio width and height of the image
        if ($sourceWidth <= $maxCanvasWidth && $sourceHeight <= $maxCanvasHeight) {
            $width = $sourceWidth;
            $height = $sourceHeight;
        } elseif ($thumbnailAspectRatio > $sourceAspectRatio) {
            $width = (int) ($maxCanvasHeight * $sourceAspectRatio);
            $height = $maxCanvasHeight;
        } else {
            $width = $maxCanvasWidth;
            $height = (int) ($maxCanvasWidth / $sourceAspectRatio);
        }

        // Create canvas for the new image
        $canvas = $this->createCanvas( $width, $height );

        // Do the resizing of the image
        imagecopyresampled($canvas, $this->image, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

        // Replace the current image
        $this->replace( $canvas );

        // Return this for linking
        return $this;
    }

    /**
     * Create a new canvas element
     *
     * @return Resource
     */
    protected function createCanvas($width, $height)
    {
        // Create the canvas element
        $canvas = imagecreatetruecolor( $width, $height );

        // Check if we should preserve the transparency
        if ( $this->imageType == IMAGETYPE_GIF || $this->imageType == IMAGETYPE_PNG ) {
            imagealphablending( $canvas, false );
            imagesavealpha( $canvas, true );
        }

        // Return the canvas
        return $canvas;
    }


    /**
     * Replaces the image with the new image
     *
     * @return $this
     */
    public function replace($res)
    {

        // Check if the image we should replace with is resource
        if (!is_resource($res)) {
            throw new UnexpectedValueException('Invalid resource');
        }

        // Check if we can destroy the image
        if (is_resource($this->image)) {
            imagedestroy($this->image);
        }

        // Set the new image as image
        $this->image = $res;

        // Return $this for chaining
        return $this;
    }

    /**
     * Destroy image GD object
     *
     * @return $this
     */
    public function destroy()
    {
        imagedestroy( $this->image );
        return $this;
    }

    /**
     * Getter for width
     *
     * return Integer
     */
    public function getWidth()
    {
        return imagesx( $this->image );
    }

    /**
     * Getter for height
     *
     * return Integer
     */
    public function getHeight()
    {
        return imagesy( $this->image );
    }

}
