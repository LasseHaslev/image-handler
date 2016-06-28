<?php

namespace LasseHaslev\Image\Handlers;

use LasseHaslev\Image\Modifiers\ImageModifier;
use LasseHaslev\Image\Adaptors\UrlAdaptor;
use LasseHaslev\Image\Adaptors\CropAdaptorInterface;

/**
 * Class ImageHandler
 * @author yourname
 */
class ImageHandler extends ImageModifier
{

    /**
     * Folder where the crops should be saved
     */
    protected $cropsFolder;

    /**
     * Image information sets when handling image
     */
    protected $setWidth;
    protected $setHeight;
    protected $isResized = false;

    /**
     * Quickly create a new instance
     *
     * @return ImageModifier instance
     */
    public static function create( $path, $cropsFolder = null )
    {
        return new static( $path, $cropsFolder );
    }

    /**
     * @param string $originalImagePath
     */
    public function __construct( $originalImagePath, $cropsFolder = null )
    {

        // Set cropsFolder or get the directory of $originalImagePath
        $this->cropsFolder = $cropsFolder ?: dirname( $originalImagePath );

        return parent::__construct( $originalImagePath );

    }

    /**
     * Getter for cropsFolder
     *
     * return string
     */
    public function getCropsFolder()
    {
        return $this->cropsFolder;
    }

    /**
     * Setter for cropsFolder
     *
     * @param string $cropsFolder
     * @return ImageHandler
     */
    public function setCropsFolder($cropsFolder)
    {
        $this->cropsFolder = $cropsFolder;

        return $this;
    }

    /**
     * Quickly remove crops
     *
     * @return $instance
     */
    public static function deleteCrops( $originalFilePath, $cropsFolder = null )
    {
        $self = static::create( $originalFilePath, $cropsFolder );
        return $self->removeCrops();
    }

    /**
     * Reset all created crops for the image
     *
     * @return $this
     */
    public function removeCrops()
    {

        // Get original file name
        $justTheName = pathinfo($this->originalImagePath, PATHINFO_FILENAME);

        // Get all the files to remove
        $filesToRemove = glob( sprintf( '%s/%s-*', $this->cropsFolder, $justTheName ) );

        // Delete the crops from list
        foreach($filesToRemove as $file){ // iterate files
            // Check if this is a file
            if(is_file($file))
                unlink($file); // delete file
        }

        // Return $this for chaning
        return $this;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function useAdaptor( CropAdaptorInterface $adaptor )
    {

        $adaptorTransformValue = $adaptor->transform();

        // Make shure the adaptor@transform returns array
        if ( ! is_array( $adaptorTransformValue ) ) {
            throw new \Exception( 'The returntype of the transform function in the adaptor need to return an array.' );
        }

        // Overwrite default data with adaptor
        $data = array_merge( [
            'filename'=>null,
            'width'=>null,
            'height'=>null,
            'resize'=>false,
            'originalFolder'=>null,
            'cropsFolder'=>null,
        ], $adaptorTransformValue );

        // Handle the image
        return $this->handle( $filename, $width, $height, $resize, $originalFolder, $cropsFolder );
    }

    /**
     * Handle image based on the parameters you give
     *
     * @return ImageHandler
     */
    public function handle( $filename = null, $width = null, $height = null, $resize = false, $originalFolder = null, $cropsFolder = null )
    {

        // Throw error if filename is not set
        if ( ! $filename ) throw new \Exception( 'You need to set a filename in adaptor' );

        // Throw error if both width and height is null
        if ( ! $width && ! $height ) throw new \Exception( 'The width or the height need to be set' );

        // Check if we should resize or crop
        // If one of the width or height is _ This is still a resize
        if ( $resize || ( !$width || !$height ) ) {
            $this->resize( $width, $height );
        }
        else {
            $this->cropToFit( $width, $height );
        }

        // Save the new image and return the response as image
        $this->save( $filename );
    }


    /**
     * undocumented function
     *
     * @return void
     */
    public function save($path)
    {
        // var_dump( [ $this->getWidth(), $this->getHeight() ] );
        $path = sprintf( '%s/%s', $this->cropsFolder, basename( $path ) );


        if ($path == $this->originalImagePath) {
            throw new \Exception( 'You are not allowed to overwrite original image. Select another save path.' );
        }

        // var_dump( $path );
        $returnValue = parent::save( $path );

        // Reset
        $this->resetImageObject();

        // Return $this-> from parent
        return $returnValue;
    }

    /**
     * Automaticly call functions on this object and check if the functions exists on modifier
     * Shortcuts for calling functions on modifier
     *
     * return mixed
     */
    public function __call( $name, $arguments ) {

        // check if method dont exist on this
        if (! method_exists( $this, $name )) {

            // Check if method exists on modifier
            if ( method_exists( $this->modifier, $name ) ) {

                return call_user_func_array( [ $this->modifier, $name ], $arguments );

            }

        }

        // Else try calling this method on this class
        return call_user_func_array( $name, $arguments );

    }


}
