<?php

use LasseHaslev\Image\Handlers\CropHandler;
use LasseHaslev\Image\Adaptors\FilenameAdaptor;

/**
 * Class DontKnowTest
 * @author Lasse S. Haslev
 */
class CropHandlerTest extends PHPUnit_Framework_TestCase
{

    protected $handler;
    protected $imagePath;

    /*
     * Setup image path and handler
     */
    public function setup()
    {
        $this->imagePath = __DIR__ . '/../images/test-image.jpg';
        $this->handler = CropHandler::create( dirname( $this->imagePath ) );
    }

    /**
     * Call destroy on the handler to remove the GD object
     * This prevent error
     */
    public function tearDown()
    {
        // $this->handler->destroy();
    }

    /**
     * Check if we can use an adaptor to handle resizing of image
     *
     * @return void
     */
    public function test_can_handle_image_data()
    {
        $path = $this->handler->handle( [
            'name'=>'test-image.jpg',
            'width'=>15,
            'height'=>15,
            'resize'=>true,
        ] )->save( 'test-image-43x43-resize.jpg' );

        $this->assertFileExists( $this->handler->getCropsFolder('test-image-43x43-resize.jpg') );
    }

    /**
     * Check if we can use an adaptor to handle resizing of image
     *
     * @return void
     */
    public function test_can_handle_image_data_with_adaptor()
    {
        $filename = 'test-image-44x44-resize.jpg';
        $path = $this->handler
            ->useAdaptor( new FilenameAdaptor( $filename ) )
            ->save( $filename )
            ->getCropsFolder( $filename );

        $this->assertFileExists( $path );
    }

    public function test_set_relative_path_from_original_folder() {
        $filename = 'images/test-image-89x89-resize.jpg';
        $path = $this->handler

            ->setOriginalsFolder( __DIR__ . '/..' )
            ->setCropsFolder( __DIR__ . '/../images/tests' )

            ->useAdaptor( new FilenameAdaptor( $filename ) )
            ->save( $filename )
            ->getCropsFolder( $filename );

        $this->assertFileExists( __DIR__ . '/../images/tests/test-image-89x89-resize.jpg' );
    }

}
