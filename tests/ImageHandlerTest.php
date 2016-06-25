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

    /*
     * Can save images in same folder
     */
    public function test_save_images_in_same_folder()
    {
        // $this->modifier->cropToFit( 50,50 )
            // ->save();
    }

    /*
     * Can save images in different folder
     */
    public function test_save_images_in_different_folder()
    {
        $this->modifier->setCropsFolder( __DIR__ . '/../images/tests' );
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
