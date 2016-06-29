<?php

namespace LasseHaslev\Image\Handlers;

use LasseHaslev\Image\Adaptors\CropAdaptorInterface;


/**
 * Class CropHandler
 * @author Lasse S. Haslev
 */
class CropHandler
{

    protected $adaptor;
    protected $baseFolder;
    protected $cropsFolder;

    /**
     * Staticly create instance
     *
     * @return Crophandler
     */
    public static function create($baseFolder = null, $cropsFolder = null, $adaptor = null)
    {
        return new static( $baseFolder, $cropsFolder, $adaptor );
    }

    /**
     * @param mixed $baseFolder = null
     * @param mixed $cropsFolder = null
     */
    public function __construct($baseFolder = null, $cropsFolder = null, $adaptor = null)
    {
        $this->setBaseFolder( $baseFolder );
        $this->setCropsFolder( $cropsFolder );
        $this->setAdaptor( $adaptor );
    }

    /**
     * Handle image based on array
     *
     * @return void
     */
    public function handle($data = null)
    {

        // Handle data with adaptor
        $adaptor = $this->getAdaptor();
        if ( $adaptor ) {
            $data = $adaptor->transform( $data, $this );
        }

        // Overwrite default data with data
        $data = array_merge( [
            'name'=>null,
            'width'=>null,
            'height'=>null,
            'focus_point_x'=>0,
            'focus_point_y'=>0,
            'resize'=>false,
        ], $data );

        // Create variables from array keys
        extract( $data );

        // Throw error if filename is not set
        if ( ! $name ) throw new \Exception( 'You need to set a name in adaptor' );

        // Throw error if both width and height is null
        if ( ! $width && ! $height ) throw new \Exception( 'The width or the height need to be set' );

        // Create handler
        $this->handler = ImageHandler::create( $name, $this->getBaseFolder(), $this->getCropsFolder() );

        // Check if we should resize or crop
        // If one of the width or height is _ This is still a resize
        if ( $resize || ( !$width || !$height ) ) {
            $this->handler->resize( $width, $height );
        }
        else {
            $this->handler->cropToFit( $width, $height, $focus_point_x, $focus_point_y );
        }

        // Return this for binding
        return $this;
    }

    /**
     * Save the file
     *
     * @return void
     */
    public function save($path)
    {
        if ( ! file_exists( $this->getCropsFolder( $path ) ) ) {
            $this->handler->save( $this->getCropsFolder( $path ), true );
        }

        return $this;
    }


    /**
     * Setter for baseFolder
     *
     * @param string $baseFolder
     * @return CropHandler
     */
    public function setBaseFolder($baseFolder)
    {
        $this->baseFolder = $baseFolder;

        return $this;
    }


    /**
     * Getter for baseFolder
     *
     * return string
     */
    public function getBaseFolder( $path = null )
    {

        if ( $path ) {
            return sprintf( '%s/%s', $this->baseFolder, $path );
        }

        return $this->baseFolder;
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


    /**
     * Getter for cropsFolder
     *
     * return string
     */
    public function getCropsFolder( $path = null )
    {
        if ( ! $this->cropsFolder ) {
            return $this->getBaseFolder($path);
        }

        if ( $path ) {
            return sprintf( '%s/%s', $this->cropsFolder, basename( $path ) );
        }

        return $this->cropsFolder;
    }

    /**
     * Setter for adaptor
     *
     * @param CropAdaptorInterface $adaptor
     * @return CropHandler
     */
    public function setAdaptor($adaptor = null)
    {

        // Set the adaptor
        $this->adaptor = $adaptor;

        // If we just want to reset the adaptor
        if ( ! $adaptor ) {
            return $this;
        }

        // Validate the adaptor
        if ( ! ( $adaptor instanceof CropAdaptorInterface ) ) {
            throw new \Exception( 'The adaptor need to implement LasseHaslev\Image\Adaptors\CropAdaptorInterface' );
        }

        // Return this for binding
        return $this;
    }

    /**
     * Getter for adaptor
     *
     * return CropHandler
     */
    public function getAdaptor()
    {
        return $this->adaptor;
    }

}
