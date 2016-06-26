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

}
