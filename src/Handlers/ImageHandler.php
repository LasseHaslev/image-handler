<?php

namespace LasseHaslev\Image\Handlers;

use LasseHaslev\Image\Modifiers\ImageModifier;

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
    public function __construct( $originalImagePath, $cropsFolder )
    {

        // Set cropsFolder or get the directory of $originalImagePath
        $this->cropsFolder = $cropsFolder ?: dirname( $originalImagePath );

        parent::__construct( $originalImagePath );

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
    public static function deleteCrops( $originalFilePath )
    {
        $self = static::create( $originalFilePath );
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

        // Get the directory of where all the files are
        $dir = dirname( $this->originalImagePath );

        // Get all the files to remove
        $filesToRemove = glob( sprintf( '%s/%s-*', $dir, $justTheName ) );

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
    public function save($path = null)
    {
        // var_dump( [ $this->getWidth(), $this->getHeight() ] );
        $path = $path ?: sprintf( '%s/%s', $this->cropsFolder, $this->buildImageName() );

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
     * Create filename based on crop and resize information
     * Build the image name ( {name}-{width}x{height}-resize
     *
     * @return String
     */
    protected function buildImageName()
    {

        // Modify values
        $width = $this->setWidth ?: '_';
        $height = $this->setHeight ?: '_';
        $resize = $this->isResized && !( $width == '_' || $height == '_' ) ? '-resize' : '';

        $replaceString = sprintf( '$1-%sx%s%s$2', $width, $height, $resize ); // $1 = filename and $2 = extension
        // var_dump( $replaceString );
        return preg_replace( '/^([A-z0-9\-\_]+)(\.[A-z]+)$/', $replaceString, basename( $this->originalImagePath ) );
        // return $this->cropsFolder
    }

    /**
     * Set $setWidth and $setHeight before resizing the GD
     *
     * @return static
     */
    public function resize($width = null, $height = null)
    {

        $this->setWidth = $width;
        $this->setHeight = $height;
        $this->isResized = true;

        return parent::resize( $width, $height );
    }


}
