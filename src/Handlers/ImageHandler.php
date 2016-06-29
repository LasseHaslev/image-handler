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
    protected $filename;
    protected $originalFolder;
    protected $cropsFolder;

    /**
     * Quickly create a new instance
     *
     * @return ImageModifier instance
     */
    public static function create( $filename, $originalFolder = null, $cropsFolder = null )
    {
        return new static( $filename, $originalFolder, $cropsFolder );
    }

    /**
     * @param string $originalImagePath
     */
    public function __construct( $filename, $originalFolder = null, $cropsFolder = null )
    {

        $this->originalFolder = $originalFolder ?: dirname( $filename );

        // Set cropsFolder or get the directory of $originalImagePath
        $this->cropsFolder = $cropsFolder ?: $this->originalFolder;

        $this->filename = $originalFolder ? $filename : basename( $filename );

        return parent::__construct( sprintf( '%s/%s', $this->originalFolder, $this->filename ) );;

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
    public function save($path, $fullPath = false)
    {
        if ( !$fullPath ) {

            // var_dump( [ $this->getWidth(), $this->getHeight() ] );
            $path = sprintf( '%s/%s', $this->cropsFolder, basename( $path ) );

        }

        if ($path == $this->originalImagePath) {
            throw new \Exception( 'You are not allowed to overwrite original image. Select another save path.' );
        }

        // var_dump( $path );
        $returnValue = parent::save( $path );

        // Reset
        $this->reset();

        // Return $this-> from parent
        return $returnValue;
    }


}
