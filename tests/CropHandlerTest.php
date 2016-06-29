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
     * undocumented function
     *
     * @return void
     */
    public function test_get_aboslute_path_to_crops_folder_with_attribute()
    {
    }

    // Get file in crop folder when adding relative path
    // When trying to get crop from relative path get {cropsFolder}/{cropName}
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
