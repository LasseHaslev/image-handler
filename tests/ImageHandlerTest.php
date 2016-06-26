<?php

use LasseHaslev\Image\Handlers\ImageHandler;

/**
 * Class DontKnowTest
 * @author Lasse S. Haslev
 */
class ImageHandlerTest extends PHPUnit_Framework_TestCase
{


    protected $modifier;
    protected $imagePath;

    /*
     * Setup image path and modifier
     */
    public function setup()
    {
        $this->imagePath = __DIR__ . '/../images/test-image.jpg';
        $this->modifier = ImageHandler::create( $this->imagePath );
    }

    /**
     * Call destroy on the modifier to remove the GD object
     * This prevent error
     */
    public function tearDown()
    {
        $this->modifier->destroy();
    }

    /**
     * Check if cropps folder gets set
     * automaticly
     */
    function test_if_crops_folder_automaticly_get_set()
    {
        $this->assertEquals( dirname( $this->imagePath ), $this->modifier->getCropsFolder() );
    }

    /**
     * Can get and set crops folder
     */
    function test_get_set_crops_folder()
    {
        $folderName = __DIR__ . '/new-folder';
        $this->modifier->setCropsFolder( $folderName );
        $folder = $this->modifier->getCropsFolder();
        $this->assertEquals( $folderName, $folder );
    }

    /**
     * Prevent overwrite of original
     */
    function test_cannot_overwrite_original_image()
    {
        $this->setExpectedException('Exception');
        $this->modifier->cropToFit( 100, 100 )
            ->save( $this->imagePath );
    }

    /*
     * Add custom filename on save without parameter
     */
    public function test_custom_filename_on_save_without_parameter()
    {
        $this->modifier->resize( 100, 100 )
            ->save();
        $this->assertFileExists( $this->modifier->getCropsFolder() . '/test-image-100x100-resize.jpg' );

        $this->modifier->resize( null, 100 )
            ->save();
        $this->assertFileExists( $this->modifier->getCropsFolder() . '/test-image-_x100.jpg' );

        $this->modifier->resize( 100, null )
            ->save();
        $this->assertFileExists( $this->modifier->getCropsFolder() . '/test-image-100x_.jpg' );

        $this->modifier->cropToFit( 100, 100 )
            ->save();
        $this->assertFileExists( $this->modifier->getCropsFolder() . '/test-image-100x100.jpg' );
    }

    /*
     * Can save images in same folder
     */
    public function test_save_images_in_same_folder()
    {
        $this->modifier->resize( 150, 150 )
            ->save();
        $this->assertFileExists( dirname( $this->imagePath ) . '/test-image-150x150-resize.jpg' );
    }

    /*
     * Can save images in different folder
     */
    public function test_save_images_in_different_folder()
    {
        $cropsFolder = dirname( $this->imagePath ) . '/tests';
        $this->modifier->setCropsFolder( $cropsFolder );
        $this->modifier->resize( 160, 160 )
            ->save();
        $this->assertFileExists( $cropsFolder . '/test-image-160x160-resize.jpg' );
    }

    /*
     * Can remove crops from same folder
     */
    public function test_remove_crops_in_same_folder()
    {
    }

    /*
     * Can remove crops from different folder
     */
    public function test_remove_crops_in_different_folder()
    {
    }

}
