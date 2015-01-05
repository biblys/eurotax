<?php 

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Biblys\EuroTax as Tax;

class testEuroTax extends PHPUnit_Framework_TestCase
{
    
    public function testSetDownloadable()
    {
        $tax = new Tax('FR', 'BE', Tax::EBOOK);
        $this->assertTrue($tax->isDownloadable());
    }
    
    public function testBasic()
    {
    
        $tax = new Tax();
        $tax->setSellerCountry('FR');
        $tax->setCustomerCountry('BE');
        $tax->setProductType(Tax::EBOOK);
        
        $this->assertEquals('FR', $tax->getSellerCountry());
        $this->assertEquals('BE', $tax->getCustomerCountry());
        $this->assertTrue($tax->isDownloadable());
        $this->assertEquals(21, $tax->getTaxRate());
        
    }
        
    public function testBefore2015()
    {
    
        $tax = new Tax('FR', 'BE', Tax::EBOOK, new \DateTime('2014-12-31'));
        
        $this->assertEquals(Tax::EBOOK, $tax->getProductType());
        $this->assertEquals('FR', $tax->getCustomerCountry()); // Must fallback to seller country
        $this->assertEquals(5.5, $tax->getTaxRate());
        
    }
        
    public function testUnknownCustomerCountry()
    {
    
        $tax = new Tax('FR', 'US', Tax::EBOOK);

        $this->assertEquals($tax->getSellerCountry(), $tax->getCustomerCountry());
        
    }
        
    /**
     * @expectedException Exception
     * @expectedExceptionMessage Seller's country must be set before customer's.
     */
    public function testSettingCustomerBeforeSeller()
    {
    
        $tax = new Tax();
    
        $tax->setCustomerCountry('FR');
        
    }
    
    public function testUnsetProductType()
    {
    
        $tax = new Tax('FR', 'FI', Tax::AUDIOBOOK);

        $this->assertEquals(Tax::STANDARD, $tax->getProductType());
        
    }
    
}
