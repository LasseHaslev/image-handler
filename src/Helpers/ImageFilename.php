<?php

namespace LasseHaslev\Image\Helpers;

/**
 * Class ImageFilename
 * @author Lasse S. Haslev
 */
class ImageFilename
{
    // Help set width, height, focus point to path

    /**
     * undocumented function
     *
     * @return void
     */
    public static function filename( $filename, array $options = [])
    {
        $pathInfo = pathinfo($filename);

        $basename = $pathInfo[ 'basename' ];
        $dirname = $pathInfo[ 'dirname' ];
        $extension = $pathInfo[ 'extension' ];
        $filename = $pathInfo[ 'filename' ];

        $options = array_merge( [
            'width'=>null,
            'height'=>null,

            'focusX'=>null,
            'focusY'=>null,

            'resize'=>null,
        ], $options );
        $options['width'] = $options['width'] ?: '_';
        $options['height'] = $options['height'] ?: '_';
        $options['focusX'] = $options['focusX'] ?: '_';
        $options['focusY'] = $options['focusY'] ?: '_';

        return sprintf( '%s/%s-%sx%s.%s', $dirname, $filename, $options['width'], $options['height'], $extension );;
    }

}
