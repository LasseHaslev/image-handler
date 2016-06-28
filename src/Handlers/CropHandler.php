<?php

namespace LasseHaslev\Image\Handlers;

/**
 * Class CropHandler
 * @author Lasse S. Haslev
 */
class CropHandler
{

    protected $handler;

    protected $originalsFolder;
    protected $cropsFolder;

    /**
     * Staticly create an instace of this
     *
     * @return void
     */
    public static function create($originalsFolder = null, $cropsFolder = null)
    {
        return new static( $originalsFolder, $cropsFolder );
    }


    /**
     * @param mixed $originalsFolder = null
     * @param mixed $cropsFolder = null
     */
    public function __construct($originalsFolder = null, $cropsFolder = null)
    {
        $this->originalsFolder = $originalsFolder;
        $this->cropsFolder = $cropsFolder ?: $this->originalsFolder;
    }


    /**
     * undocumented function
     *
     * @return void
     */
    public function useAdaptor( $adaptor )
    {

        if ( ! class_implements( $adaptor ) ) {
            throw new Exception( 'The adaptor need to implement LasseHaslev\Image\Adaptors\CropAdaptorInterface' );
        }

        $adaptorTransformValue = $adaptor->transform();

        // Make shure the adaptor@transform returns array
        if ( ! is_array( $adaptorTransformValue ) ) {
            throw new \Exception( 'The returntype of the transform function in the adaptor need to return an array.' );
        }

        // Handle the image
        return $this->handle( $adaptorTransformValue );
    }

    /**
     * Handle image based on the parameters you give
     *
     * @return ImageHandler
     */
    public function handle( $data )
    {

        // Overwrite default data with data
        $data = array_merge( [
            'name'=>null,
            'skip'=>false,
            'width'=>null,
            'height'=>null,
            'resize'=>false,
            'focus_point_x'=>0,
            'focus_point_y'=>0,
            'originalsFolder'=>$this->originalsFolder,
            'cropsFolder'=>$this->cropsFolder,
        ], $data );

        // Create variables from array keys
        extract( $data );

        // If we want to skip this
        if ( $skip ) return $this;

        // Throw error if filename is not set
        if ( ! $name ) throw new \Exception( 'You need to set a name in adaptor' );

        // Throw error if both width and height is null
        if ( ! $width && ! $height ) throw new \Exception( 'The width or the height need to be set' );

        // Create handler
        $this->handler = ImageHandler::create( $name, $originalsFolder, $cropsFolder );

        // Check if we should resize or crop
        // If one of the width or height is _ This is still a resize
        if ( $resize || ( !$width || !$height ) ) {
            $this->handler->resize( $width, $height );
        }
        else {
            $this->handler->cropToFit( $width, $height, $focus_point_x, $focus_point_y );
        }

        return $this;

    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function save( $name )
    {

        // If the file dont exists we save the image
        if ( ! file_exists( $this->getCropsFolder( $name ) ) ) {
            $this->handler->save( $name );
        }

        // Save the new image and return the response as image
        $this->handler->destroy();

        return $this;
    }

    /**
     * Getter for cropsFolder
     *
     * return string
     */
    public function getCropsFolder( $filename = null )
    {
        if ( $filename ) {
            return sprintf( '%s/%s', $this->cropsFolder, $filename );
        }
        return $this->cropsFolder;
    }

    /**
     * Setter for cropsFolder
     *
     * @param string $cropsFolder
     * @return CropHandler
     */
    public function setCropsFolder($cropsFolder)
    {
        $this->cropsFolder = $cropsFolder;

        return $this;
    }


}
