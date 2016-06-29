<?php

namespace LasseHaslev\Image\Adaptors;

/**
 * Interface CropAdaptorInterface
 * @author Lasse S. Haslev
 */
interface CropAdaptorInterface
{
    public function transform( $input, $handler = null );
}
