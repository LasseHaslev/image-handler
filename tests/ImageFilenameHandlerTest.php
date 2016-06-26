<?php

use LasseHaslev\Image\Handlers\ImageFilenameHandler;

/**
 * Class ImageFilenameHandlerTest
 * @author Lasse S. Haslev
 */
class ImageFilenameHandlerTest extends PHPUnit_Framework_TestCase
{

    protected $fileHandler;
    protected $imagePath;

    public function setup()
    {
        $this->imagePath = __DIR__ . '/../images/test-image.jpg';
        $this->fileHandler = new ImageFilenameHandler;
    }


    /**
     * Crop image by filename
     */
    function test_crop_by_filename()
    {
        $filename = dirname( $this->imagePath ) . '/test-image-500x500.jpg';
        $this->fileHandler->handle( $filename );
        $this->assertFileExists( $filename );
    }

    /**
     * Resize image by filename
     */
    function test_resize_by_filename()
    {
        $filename = dirname( $this->imagePath ) . '/test-image-500x500-resize.jpg';
        $this->fileHandler->handle( $filename );
        $this->assertFileExists( $filename );
    }

    /**
     * Resize image by filename with width unknown
     */
    function test_resize_by_filename_with_width_unknown()
    {
        $filename = dirname( $this->imagePath ) . '/test-image-_x500.jpg';
        $this->fileHandler->handle( $filename );
        $this->assertFileExists( $filename );
    }

    /**
     * Resize image by filename with height unknown
     */
    function test_resize_by_filename_with_height_unknown()
    {
        $filename = dirname( $this->imagePath ) . '/test-image-500x_.jpg';
        $this->fileHandler->handle( $filename );
        $this->assertFileExists( $filename );
    }

    // Check if we can save based on just filename
    // Check if we can save based on full path
    // Check if we can save crop based on original in other folder
    // Check if we can resize
    // Check if we can resize with unknown
    // Check if we can crop

    /**
     * Handle file based on filename
     */
    // function test()
    // {
    // }


}
