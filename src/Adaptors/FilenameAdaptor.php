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

        $regex = '/(.+)-([0-9_]+)x([0-9_]+)([\-resize]*)([\-\d\.\[\]x]*)\.([A-z]+)$/';

        $stringExamples = [
            'images/kitten1-10x20-[-0.55678x0.12345].jpg',
            'images/kitten1-10x20-resize.jpg',
            'images/kitten1-10x20.jpg',
            'images/kitten1-_x20.jpg',
            'images/kitten1-20x_.jpg',
        ];
        preg_match( $regex, $stringExamples[3], $matches );


        // preg_match( $regex, $filename, $matches );

        // Debuging
        // if (!count($matches)) {
            // var_dump($filename);
            // var_dump($matches);
            // exit;
        // }

        $filepath = $matches[1];
        $width = $matches[2];
        $height = $matches[3];
        $resize = $matches[4];
        $focusPoint = $matches[5];
        $extension = $matches[6];

        $originalFileName = sprintf( '%s/%s.%s', dirname( $filepath ), basename( $filepath ), $extension );
        $dirname = dirname( $matches[0] );

        $returnArray = [
            'filepath'=>basename( $originalFileName ), // Name

            'width'=>$width != '_' ? $width : null, // Width
            'height'=>$height != '_' ? $height : null, // Height

            'resize'=>$resize != '', // Resize
        ];

        // var_dump($returnArray);
        // var_dump($matches);
            // exit;

        return $returnArray;



    }

}
