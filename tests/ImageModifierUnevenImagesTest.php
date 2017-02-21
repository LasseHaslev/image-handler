<?php

use LasseHaslev\Image\Modifiers\ImageModifier;

/**
 * Class DontKnowTest
 * @author Lasse S. Haslev
 */
class ImageModifierUnevenImagesTest extends PHPUnit_Framework_TestCase
{


    protected $modifier;
    protected $imagePath;

    /*
     * Setup image path and modifier
     */
    public function setup()
    {
        // Throw error if originalFilePath does not exist
        $this->imagePath = __DIR__ . '/../images/placehold.jpg';
    }

    /** @test */
    public function create_versions_for_checking_if_we_dont_create_black_stripe_in_width() {
        for ($i = 0; $i < 100; $i++) {

        $modifier = ImageModifier::create( $this->imagePath );
        $modifier->cropToFit( 100 + $i,100 )
            ->save( sprintf( '%s/../images/tests/placeholder-%dx100.jpg', __DIR__,  100 + $i ) );
        $modifier->destroy();

        }
    }
    /** @test */
    public function create_versions_for_checking_if_we_dont_create_black_stripe_in_height() {
        for ($i = 0; $i < 100; $i++) {

        $modifier = ImageModifier::create( $this->imagePath );
        $modifier->cropToFit( 100,100 + $i )
            ->save( sprintf( '%s/../images/tests/placeholder-100x%d.jpg', __DIR__,  100 + $i ) );
        $modifier->destroy();

        }
    }

}
