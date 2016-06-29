<?php

namespace LasseHaslev\Image\Handlers;

/**
 * Class CropHandler
 * @author Lasse S. Haslev
 */
class CropHandler
{

    protected $baseFolder;
    protected $cropsFolder;

    /**
     * Staticly create instance
     *
     * @return Crophandler
     */
    public static function create($baseFolder = null, $cropsFolder = null)
    {
        return new static( $baseFolder, $cropsFolder );
    }

    /**
     * @param mixed $baseFolder = null
     * @param mixed $cropsFolder = null
     */
    public function __construct($baseFolder = null, $cropsFolder = null)
    {
        $this->baseFolder = $baseFolder;
        $this->cropsFolder = $cropsFolder;
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
    public function getCropsFolder()
    {
        if ( ! $this->cropsFolder ) {
            return $this->getBaseFolder();
        }
        return $this->cropsFolder;
    }

}
