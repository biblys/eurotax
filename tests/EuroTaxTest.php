<?php 

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Biblys\EuroTax as Tax;

class testEuroTax extends PHPUnit_Framework_TestCase
{
    
    public function test()
    {
    
        $tax = new Tax();
        $tax->setSellerCountry('FR');
        $tax->setCustomerCountry('BE');
        $tax->setProductType(Tax::EBOOK);

        $this->assertEquals(21, $tax->getTaxRate());
        
    }
        
    public function testBefore2015()
    {
    
        $tax = new Tax();
        $tax->setSellerCountry('FR');
        $tax->setCustomerCountry('BE');
        $tax->setProductType(Tax::EBOOK);
        $tax->setDateOfSale(new \DateTime('2014-12-31'));

        $this->assertEquals(5.5, $tax->getTaxRate());
        
    }
        
    public function testShortVersion()
    {
    
        $tax = new Tax('FR', 'BE', Tax::EBOOK);

        $this->assertEquals(21, $tax->getTaxRate());
        
    }
        
    public function testUnknownCustomerCountry()
    {
    
        $tax = new Tax('FR', 'US', Tax::EBOOK);

        $this->assertEquals($tax->getSellerCountry(), $tax->getCustomerCountry());
        
    }
    
}
