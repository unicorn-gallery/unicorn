<?php
use PHPUnit\Framework\TestCase;
use Mre\Unicorn\lib\Image;

/**
 * Image Thumbnail test class
 */

class ImageThumbnailTest extends TestCase
{
    public function testImageMustBeOfValidExtension() {
        $imageFile = Image::createThumbnail('/assets/img_avatar.png');
        $extension = strtolower(strrchr($imageFile, '.'));
        echo $extension;
        switch ($extension) {
            case '.jpg':
                $this->assertEquals('.jpg',$extension);
                break;
            case '.jpeg':
                $this->assertStringEqualsFile('.jpeg',$extension);
                break;
            case '.gif':
                $this->assertStringEqualsFile('.gif',$extension);
                break;
            case '.png':
                $this->assertStringEqualsFile('.png',$extension);
                break;
            default:
                $this->assertTrue(false, $extension);
                break;
        }
  }
}
