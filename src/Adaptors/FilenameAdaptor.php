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

            'focus_point_x'=>$data[ 'focusX' ],
            'focus_point_y'=>$data[ 'focusY' ],
        ];
    }


    /**
     * Test function for regex
     *
     * @return void
     */
    protected function regexTest()
    {
        $stringExamples = [
            'images/kitten1-10x20.jpg',
            'images/kitten1-_x20.jpg',
            'images/kitten1-20x_.jpg',
            'images/kitten1-10x20-resize.jpg',
            'images/kitten1-10x20-[-0.55678x0.12345].jpg',
        ];

        foreach ($stringExamples as $value) {

            var_dump( $value );
            $this->getData( $value );
        }
        exit;
    }


    /**
     * Prepare what we want to do with the image
     *
     * @return Array
     */
    protected function getData( $filename )
    {

        $matches = [];

        $regex = '/(.+)-([0-9_]+)x([0-9_]+)(-resize)*(\-\[([\-\d\.]+)x([\-\d\.]+)\])*\.([A-z]+)$/';

        preg_match( $regex, $filename, $matches );


        $path = $matches[1];
        $extension = $matches[8];
        $imagePath = sprintf( '%s/%s.%s', dirname( $filename ), basename( $path ), $extension );

        $width = $matches[2];
        $height = $matches[3];

        $focusX = $matches[6];
        $focusY = $matches[7];

        return [
            'filepath'=> $imagePath,
            'resize'=> $matches[4] != '',

            'width'=> $width != '_' ? $width : null,
            'height'=> $height != '_' ? $height : null,

            'focusX' => $focusX != '' ? $focusX : null,
            'focusY' => $focusY != '' ? $focusY : null,
        ];

    }

}
