<?php

namespace Liip\ImagineBundle\Tests\Imagine\Cache\Resolver;

use Liip\ImagineBundle\Imagine\Cache\Resolver\WebPathResolver;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class WebPathResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolvedResponsePathDoesNotIncludeDuplicatedRequestPath()
    {
        $request = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->setMethods(array('getBaseUrl', 'getScriptName', 'getBasePath'))
            ->getMock()
        ;
        
        $request
            ->expects($this->any())
            ->method('getBaseUrl')
            ->will($this->returnValue('/path'))
        ;
        
        $request
            ->expects($this->any())
            ->method('getScriptName')
            ->will($this->returnValue('/path/app_dev.php'))
        ;
        
        $request
            ->expects($this->any())
            ->method('getBasePath')
            ->will($this->returnValue('/path'))
        ;
        
        $resolver = $this
            ->getMockBuilder('Liip\ImagineBundle\Imagine\Cache\Resolver\WebPathResolver')
            ->disableOriginalConstructor()
            ->setMethods(array('decodeBrowserPath', 'getBrowserPath', 'getFilePath'))
            ->getMock()
        ;
        
        $resolver
            ->expects($this->any())
            ->method('decodeBrowserPath')
            ->will($this->returnValue('/path/cache/thumbnails/image.jpg'))
        ;
        
        // Use this file to pass the file_exists() check
        $resolver
            ->expects($this->any())
            ->method('getFilePath')
            ->will($this->returnValue(__FILE__))
        ;
        
        $redirect = $resolver->resolve($request, 'image.jpg', 'thumbnails');
        
        $this->assertEquals('/path/cache/thumbnails/image.jpg', $redirect->getTargetUrl());
    }
}
