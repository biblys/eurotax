<?php 

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Biblys\EuroTax as Tax;

class testEuroTax extends PHPUnit_Framework_TestCase
{
    
    public function test()
    {
    
        $tax = new Tax();
        $tax->setCustomerCountry('BE');
        $tax->setSellerCountry('FR');
        $tax->setProductType(Tax::EBOOK);

        $this->assertEquals(21, $tax->getTaxRate());
        
    }
    
}
