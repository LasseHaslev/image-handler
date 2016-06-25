<?php

namespace LasseHaslev\Image\Handlers;

use LasseHaslev\Image\Modifiers\ImageModifier;

/**
 * Class ImageHandler
 * @author yourname
 */
class ImageHandler extends ImageModifier
{

    protected $cropsFolder;

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
     * @param string $originalImagePath
     */
    public function __construct( $originalImagePath, $cropsFolder )
    {

        $this->cropsFolder = $cropsFolder;

        parent::__construct( $originalImagePath );

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

}
