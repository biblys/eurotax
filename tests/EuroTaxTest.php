<?php 

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Biblys\EuroTax as Tax;

class testEuroTax extends PHPUnit_Framework_TestCase
{
    
    public function testBasic()
    {
        $tax = new Tax('FR', 'BE', Tax::EBOOK);
        
        $this->assertEquals('FR', $tax->getSellerCountry());
        $this->assertEquals('BE', $tax->getCustomerCountry());
        $this->assertTrue($tax->isNewLawApplicable());
        $this->assertEquals(21, $tax->getTaxRate());
        
    }
    
    public function testSameCountry()
    {
        $tax = new Tax('FR', 'FR', Tax::EBOOK);
        
        $this->assertEquals('FR', $tax->getSellerCountry());
        $this->assertEquals('FR', $tax->getCustomerCountry());
        $this->assertEquals(5.5, $tax->getTaxRate());
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

        $this->assertFalse($tax->isNewLawApplicable());
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
    
    public function testPhysicalProduct()
    {
        $tax = new Tax('FR', 'BE', Tax::BOOK);
        
        $this->assertFalse($tax->isNewLawApplicable());
        $this->assertEquals(5.5, $tax->getTaxRate());
    }
    
}
