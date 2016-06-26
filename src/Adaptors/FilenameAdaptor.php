<?php

namespace LasseHaslev\Image\Adaptors;

/**
 * Class UrlAdaptor
 * @author Lasse S. Haslev
 */
class FilenameAdaptor implements CropAdaptorInterface
{

    /**
     * Prepare what we want to do with the image
     *
     * @return void
     */
    public function transform()
    {

    }


    /**
     * Prepare what we want to do with the image
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
