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
     * Add string options to filename based on options
     *
     * @return string
     */
    public static function filename( $filePath, array $options = [])
    {
        extract( static::FilePathProperties($filePath) );

        $options = static::ExtendOptions($options);

        $stringOptions = static::BuildPathOptions($options);

        return sprintf( '%s/%s%s.%s', $dirname, $filename, $stringOptions, $extension );;
    }

    /**
     * Build path options based on options
     * Example: -10x10-resize
     *
     * @return string
     */
    public static function BuildPathOptions(array $options = [])
    {
        $stringOptions = sprintf( '-%sx%s', $options['width'], $options['height'] );
        return $stringOptions;
    }

    /**
     * Extend and prepare options
     *
     * @return string
     */
    public static function ExtendOptions(array $options = [])
    {
        $options = array_merge( [
            'width'=>null,
            'height'=>null,

            'focusX'=>null,
            'focusY'=>null,

            'resize'=>false,
        ], $options );

        // Format to strings
        $options['width'] = $options['width'] ?: '_';
        $options['height'] = $options['height'] ?: '_';
        $options['focusX'] = $options['focusX'] ?: '_';
        $options['focusY'] = $options['focusY'] ?: '_';

        return $options;
    }

    /*
     * Extract all file path properties from file path
     *
     * @return array
     */
    public static function FilePathProperties($filePath)
    {
        $pathInfo = pathinfo($filePath);

        return [
            'basename'  => $pathInfo[ 'basename' ],
            'dirname'   => $pathInfo[ 'dirname' ],
            'extension' => $pathInfo[ 'extension' ],
            'filename'  => $pathInfo[ 'filename' ],
        ];
    }

}
