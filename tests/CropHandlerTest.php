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
    protected $baseFolder;

    /*
     * Setup image path and handler
     */
    public function setup()
    {
        $this->baseFolder = __DIR__ . '/../images';
        $this->imagePath = __DIR__ . '/../images/test-image.jpg';
        $this->handler = CropHandler::create();
    }

    /**
     * Call destroy on the handler to remove the GD object
     * This prevent error
     */
    public function tearDown()
    {
        // $this->handler->destroy();
    }

    // Get baseFolder
    /**
     * Get set and get baseFolder
     *
     * @return void
     */
    public function test_set_and_get_base_folder()
    {
        $this->handler
            ->setBaseFolder( $this->baseFolder );
        $this->assertEquals( $this->baseFolder, $this->handler->getBaseFolder() );
    }

    // Get cropsFolder
    /**
     * Get set and get cropsFolder
     *
     * @return void
     */
    public function test_set_and_get_crops_folder()
    {
        $this->handler
            ->setCropsFolder( $this->baseFolder );
        $this->assertEquals( $this->baseFolder, $this->handler->getCropsFolder() );
    }


    // Get base path if cropsfolder does not exists
    /**
     * Get baseFolder if crops folder does not exists
     */
    public function test_get_base_path_if_crops_folder_does_not_exists()
    {
        $this->handler
            ->setBaseFolder( $this->baseFolder );

        $this->assertEquals( $this->baseFolder, $this->handler->getCropsFolder() );
    }

    /**
     * Get relative path of base folder
     */
    public function test_get_relative_path_of_base_folder_with_attribute()
    {
        $relativePath = 'images/my-image.jpg';
        $this->handler->setBaseFolder( $this->baseFolder );

        $this->assertEquals( sprintf( '%s/%s', $this->baseFolder, $relativePath ), $this->handler->getBaseFolder( $relativePath ) );
    } // Do this but remove "/" if set in attrubute eks: "/myPath" > "myPath"


    /**
     * Test get aboslute path to crops folder with attribute
     *
     * @return void
     */
    public function test_get_aboslute_path_to_crops_folder_with_attribute()
    {
        $relativePath = 'images/my-image.jpg';
        $this->handler->setCropsFolder( $this->baseFolder );

        $this->assertEquals( sprintf( '%s/%s', $this->baseFolder, basename( $relativePath ) ), $this->handler->getCropsFolder( $relativePath ) );
    }

    /**
     * Test get relative path to base folder when getting crops folder when no crops folder is set
     */
    public function test_get_relative_path_to_base_folder_when_getting_crops_folder_when_no_crops_folder_is_set()
    {
        $relativePath = 'images/my-image.jpg';
        $this->handler->setBaseFolder( $this->baseFolder );

        $this->assertEquals( sprintf( '%s/%s', $this->baseFolder, $relativePath ), $this->handler->getCropsFolder( $relativePath ) );
    }

    // Can set Adaptor
    /**
     * Can set Adaptor
     */
    public function test_can_get_and_set_adaptor()
    {
        $adaptor = new FilenameAdaptor;
        $this->handler->setAdaptor( $adaptor );

        $this->assertEquals( $adaptor, $this->handler->getAdaptor() );
    }

    // Throw error if Adaptor dont inherit CropAdaptorInterface
    public function test_trow_error_if_adaptor_not_implementing_adaptor_interface()
    {
        $this->setExpectedException( 'Exception' );

        $adaptor = new CropHandler;
        $this->handler->setAdaptor( $adaptor );
    }

    // When trying to get crop from relative path when cropsfolder retrieve {baseFolder}/{relativePath}

    // public function test_set_relative_path_from_base_folder() {
        // $filename = 'images/test-image-89x89-resize.jpg';
        // $path = $this->handler

            // ->setBaseFolder( __DIR__ . '/..' )
            // ->setCropsFolder( __DIR__ . '/../images/tests' )

            // ->setAdaptor( new FilenameAdaptor )

            // ->handle( $filename )

            // ->save( $filename )
            // ->getCropsFolder( $filename );

        // $this->assertFileExists( __DIR__ . '/../images/tests/test-image-89x89-resize.jpg' );
    // }

}
