<?php

namespace LasseHaslev\Image\Handlers;

use LasseHaslev\Image\Handlers\ImageHandler;

/**
 * Class ImageFilenameHandler
 * @author Lasse S. Haslev
 */
class ImageUrlHandler
{

    protected $folderToBeRelativeTo;
    protected $cropsFolder;
    protected $domain;

    protected $imageHandler;

    /**
     * @param mixed $folderToBeRelativeTo
     * @param mixed $cropsFolder = null
     * @param mixed $domain = null
     */
    public function __construct($folderToBeRelativeTo, $cropsFolder = null, $domain = null)
    {
        $this->folderToBeRelativeTo = $folderToBeRelativeTo;
        $this->cropsFolder = $cropsFolder = null;
        $this->domain = $domain = null;

        $this->imageHandler = new ImageFilenameHandler;
    }


    /**
     * Handle the full url
     *
     * @return void
     */
    public function handle( $url )
    {

        $this->imageHandler->handle( $this->folderToBeRelativeTo . $url );

    }

}
