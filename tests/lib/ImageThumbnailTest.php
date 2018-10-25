<?php
use PHPUnit\Framework\TestCase;
use Mre\Unicorn\lib\Image;

/**
 * Image Thumbnail test class
 */

class ImageThumbnailTest extends TestCase
{
  public function testImageMustBeOfValidExtension() {
    $imageFile = Image::createThumbnail('//img.url/myAvatar.pngh');
    $extension = strtolower(strrchr($imageFile, '.'));
    switch ($extension) {
      case '.jpg':
        $this->assertStringEqualsFile('.jpg',$extension);
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
        $this->assertTrue(false, 'Invalid/Unsupported Image extension');
        break;
    }
  }
}
