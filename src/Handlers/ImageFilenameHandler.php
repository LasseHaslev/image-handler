<?php

namespace LasseHaslev\Image\Handlers;

use LasseHaslev\Image\Handlers\ImageHandler;

/**
 * Class ImageFilenameHandler
 * @author Lasse S. Haslev
 */
class ImageFilenameHandler
{

    protected $handler;
    protected $cropsFolder;

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
     * @return ImageFilenameHandler
     */
    public function setCropsFolder($cropsFolder)
    {
        $this->cropsFolder = $cropsFolder;

        return $this;
    }

    /**
     * Handle the full url
     *
     * @return void
     */
    public function handle( $newFile, $folderToFindOriginalImage = null )
    {

        // If the file already exists we just return
        if ( file_exists( $newFile ) ) {
            return $this;
        }

        // Get data
        $data = $this->getData( $newFile );
        $name = $data['name'];
        $width = $data['width'];
        $height = $data['height'];
        $resize = $data['resize'];
        $extension = $data['extension'];
        $fileName = $data['fileName'];
        $fullFileName = $data['fullFileName'];

        // Build path to original object
        $folderToFindOriginalImage = $folderToFindOriginalImage ?: dirname( $newFile );
        $originalImage = sprintf( '%s/%s', $folderToFindOriginalImage, $fileName );

        // Instansiate image handler
        $this->handler = ImageHandler::create( $originalImage );

        // Check if we should resize or crop
        // If one of the width or height is _ This is still a resize
        if ( $resize || ( !$width || !$height ) ) {
            $this->handler
                ->resize( $width, $height );
        }
        else {
            $this->handler
                ->cropToFit( $width, $height );
        }

        // Save the new image and return the response as image
        $this->handler
            ->save( $newFile )
            ->destroy();

        return $newFile;

    }

    /**
     * Get the information out of image url
     *
     * @return Array
     */
    protected function getData( $url )
    {

        $matches = [];

        preg_match( '/([A-z0-9\-]+)-([0-9_]+)x([0-9_]+)(-[0-9a-zA-Z(),\-._]+)*(\..+)$/', basename( $url ), $matches );

        return [
            'name'=>$matches[ 1 ], // Name

            // If the value is _ we returns null
            'width'=>$matches[ 2 ] != '_' ? $matches[ 2 ] : null, // Width
            'height'=>$matches[ 3 ] != '_' ? $matches[ 3 ] : null, // Height

            'resize'=>$matches[ 4 ] == '-resize', // Resize
            'extension'=>$matches[ 5 ], // Extension
            'fileName'=>$matches[ 1 ] . $matches[ 5 ], // Extension
            'fullFileName'=>dirname($url) . '/' . $matches[ 1 ] . $matches[ 5 ], // Extension
        ];

    }


}
