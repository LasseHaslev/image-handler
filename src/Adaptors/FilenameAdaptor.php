<?php

namespace LasseHaslev\Image\Adaptors;

/**
 * Class UrlAdaptor
 * @author Lasse S. Haslev
 */
class FilenameAdaptor implements CropAdaptorInterface
{

    protected $filename;


    /**
     * Prepare what we want to do with the image
     *
     * @return void
     */
    public function transform( $input, $handler = null )
    {
        $data = $this->getData( $input );
        return [
            'name'=>$data[ 'filepath' ],
            'width'=>$data[ 'width' ],
            'height'=>$data[ 'height' ],
            'resize'=>$data[ 'resize' ],
        ];
    }


    /**
     * Prepare what we want to do with the image
     *
     * @return Array
     */
    protected function getData( $filename )
    {

        $matches = [];

        preg_match( '/([A-z0-9\-]+)-([0-9_]+)x([0-9_]+)(-[0-9a-zA-Z(),\-._]+)*(\..+)$/', basename( $filename ), $matches );

        return [
            'name'=>$matches[ 1 ], // Name

            'filepath'=>dirname( $filename ) == '.' ? $matches[ 1 ] . $matches[ 5 ] : dirname( $filename ) . '/' . $matches[ 1 ] . $matches[ 5 ], // Name

            // If the value is _ we returns null
            'width'=>$matches[ 2 ] != '_' ? $matches[ 2 ] : null, // Width
            'height'=>$matches[ 3 ] != '_' ? $matches[ 3 ] : null, // Height

            'resize'=>$matches[ 4 ] == '-resize', // Resize
            'extension'=>$matches[ 5 ], // Extension
            'filename'=>$matches[ 1 ] . $matches[ 5 ], // Extension
            'fullFileName'=>dirname($filename) . '/' . $matches[ 1 ] . $matches[ 5 ], // Extension
        ];

    }

}
