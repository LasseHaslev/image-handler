<?php

use LasseHaslev\Image\Handlers\ImageHandler;
use LasseHaslev\Image\Adaptors\FilenameAdaptor;

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
     * Can save images in same folder
     */
    public function test_save_images_in_same_folder()
    {
        $this->modifier->resize( 150, 150 )
            ->save( 'test-image-150x150-resize.jpg' );
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
            ->save( 'test-image-160x160-resize.jpg' );
        $this->assertFileExists( $cropsFolder . '/test-image-160x160-resize.jpg' );

        return $cropsFolder;
    }

    /*
     * Can remove crops from same folder
     */
    public function test_remove_crops_in_same_folder()
    {
        // Make sure we have crops in folder
        $this->test_save_images_in_same_folder();

        $this->modifier->removeCrops();
    }

    /*
     * Can remove crops from different folder
     */
    public function test_remove_crops_in_different_crops_folder()
    {
        // Make sure we have crops in folder
        $this->test_save_images_in_different_folder();
        $this->modifier->removeCrops();
    }

    /**
     * Check if we can use an adaptor to handle resizing of image
     *
     * @return void
     */
    public function test_can_handle_image_with_adaptor()
    {
        $adaptor = new FilenameAdaptor( 'test-image-15x15-resize.jpg' );
        $this->modifier->useAdaptor( $adaptor );
        $this->assertFileExists( $this->modifier->getCropsFolder() . '/test-image-15x15-resize.jpg' );
    }

    public test_work_as_i_would_expect_it_to() {

            $this->modifier->setOriginalFolder( __DIR__ . '/../images' );
            $this->modifier->setCropsFolder( __DIR__ . '/../images/crops' );

            $adaptor = new FilenameAdaptor( 'test-image-160x160-resize.jpg' );

            $path = $this->modifier->useAdaptor( $adaptor )
                ->getCropPath();

            // $file = File::get( $path );
            // return response( $file, 200 )
                // ->header( 'Content-Type', $this->modifier->mimeType() );
    }

    /*
     * Can remove crops from static function
     */
    // public function test_remove_crops_from_static_function()
    // {
        // // Make sure we have crops in folder
        // // $this->test_custom_filename_on_save_without_parameter();
        // // ImageHandler::deleteCrops( $this->imagePath );
    // }

    /*
     * Can remove crops from static function in different folder
     */
    // public function test_remove_crops_in_different_folder_from_static_function()
    // {
        // // Make sure we have crops in folder
        // // $cropsFolder = $this->test_save_images_in_different_folder();
        // // ImageHandler::deleteCrops( $this->imagePath, $cropsFolder );
    // }

}
