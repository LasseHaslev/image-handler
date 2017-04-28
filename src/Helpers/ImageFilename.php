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
        // Size flag
        $sizeFlag = sprintf( '-%sx%s', $options['width'], $options['height'] );

        // Resize flag
        $resizeFlag = $options[ 'resize' ] ? '-resize' : '';

        // focus point flag
        $focusPointFlag = '';
        if (!$options['resize'] && ( $options[ 'focusX' ] || $options['focusY'] )) {
            $focusPointFlag = sprintf( '-[%sx%s]', $options['focusX'], $options['focusY'] );
        }

        return sprintf( '%s%s%s', $sizeFlag, $focusPointFlag, $resizeFlag );
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

            'focusX'=>0,
            'focusY'=>0,

            'resize'=>false,
        ], $options );

        // Format to strings
        $options['width'] = $options['width'] ?: '_';
        $options['height'] = $options['height'] ?: '_';

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
