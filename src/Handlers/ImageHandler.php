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
        var_dump( 'test' );
        return null;
    }

    /**
     * Create filename based on crop and resize information
     *
     * @return String
     */
    protected function createFileName()
    {
        return null;
    }



}
