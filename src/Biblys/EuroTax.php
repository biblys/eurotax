<?php 

namespace Biblys;

class EuroTax
{
    const STANDARD = 0, // Default tax rate to use with unknown type
        BOOK = 1, 
        EBOOK = 2, 
        AUDIOBOOK = 3, // Physical audio books
        EAUDIOBOOK = 4, // Downloadable audio books
        PERIODICAL = 5, // Magazines
        CD = 6,
        DVD = 7;
    
    private $customerCountry,
        $sellerCountry,
        $productType,
        $dateOfSale,
        $taxRate;
    
    public function __construct($sellerCountry = null, $customerCountry = null, $productType = null, $dateOfSale = null)
    {
        if (isset($sellerCountry)) $this->setSellerCountry($sellerCountry);
        if (isset($customerCountry)) $this->setCustomerCountry($customerCountry);
        if (isset($productType)) $this->setProductType($productType);
        
        $this->setDateOfSale(new \DateTime());
        if (isset($dateOfSale)) 
        {
            $this->setDateOfSale($dateOfSale);
        }
        
        $this->calculateTaxRate();
    }
    
    /**
     * Set the customer country
     * @param string $country An ISO-3166 country code
     */
    public function setCustomerCountry($country) 
    {
        $this->customerCountry = strtoupper($country);
    }
    
    public function getCustomerCountry() 
    {
        return $this->customerCountry;
    }
    
    /**
    * Set the seller country
    * @param string $country An ISO-3166 country code
    */
    public function setSellerCountry($country) 
    {
        $this->sellerCountry = strtoupper($country);
    }
    
    public function getSellerCountry() 
    {
        return $this->sellerCountry;
    }
    
    /**
     * Set the product type 
     * @param CONST $type See CONSTs
     */
    public function setProductType($type)
    {
        $this->productType = $type;
    }
    
    public function getProductType()
    {
        return $this->productType;
    }
    
    /**
     * Set the date of sale
     * @param Date $date
     */
    public function setDateOfSale(\DateTime $date)
    {
        $this->dateOfSale = $date;
    }
    
    public function getDateOfSale()
    {
        return $this->dateOfSale;
    }
    
    /**
     * Set the tax rate
     * @param float $rate
     */
    
    private function setTaxRate($rate)
    {
        $this->taxRate = $rate;
    }
    
    public function getTaxRate()
    {
        return $this->calculateTaxRate();
    }
    
    /**
     * Calculate the tax rate
     */
    
    private function calculateTaxRate()
    {
        
        if (!$this->getSellerCountry() || !$this->getCustomerCountry() || !$this->getProductType())
        {
            return false;
        }
        
        // If date of sale < January 1st 2015, don't use customer country
        if ($this->getDateOfSale() < new \DateTime("2015-01-01"))
        {
            $this->setCustomerCountry($this->getSellerCountry());
        }
        
        $rates = array(
            
            // Belgium
            'BE' => array(
                self::STANDARD => 21,
                self::BOOK => 6,
                self::EBOOK => 21
            ),
            
            // Bulgaria
            'BU' => array(
                self::STANDARD => 20
            ),
            
            // Czech Republic
            'CZ' => array(
                self::STANDARD => 21
            ),
            
            // Denmark
            'DK' => array(
                self::STANDARD => 25
            ),
            
            // Germany
            'DE' => array(
                self::STANDARD => 19
            ),
            
            // Estonia
            'EE' => array(
                self::STANDARD => 20
            ),
            
            // Greece
            'EL' => array(
                self::STANDARD => 23
            ),
            
            // Spain
            'ES' => array(
                self::STANDARD => 21
            ),
            
            // France
            'FR' => array(
                self::STANDARD => 20,
                self::BOOK => 5.5,
                self::EBOOK => 5.5,
                self::AUDIOBOOK => 5.5,
                self::AUDIOBOOK => 20,
                self::PERIODICAL => 2.1
            ),
            
            // Croatia
            'HR' => array(
                self::STANDARD => 25
            ),
            
            // Ireland
            'IE' => array(
                self::STANDARD => 23
            ),
            
            // Italy
            'IT' => array(
                self::STANDARD => 22
            ),
            
            // Cyprus
            'CY' => array(
                self::STANDARD => 19
            ),
            
            // Latvia
            'LV' => array(
                self::STANDARD => 21
            ),
            
            // Lithuania
            'LI' => array(
                self::STANDARD => 21
            ),
            
            // Luxembourg
            'LU' => array(
                self::STANDARD => 15
            ),
            
            // Hungary
            'HU' => array(
                self::STANDARD => 27
            ),
            
            // Malta
            'MT' => array(
                self::STANDARD => 18
            ),
            
            // Netherlands
            'NL' => array(
                self::STANDARD => 21
            ),
            
            // Austria
            'AT' => array(
                self::STANDARD => 20
            ),
            
            // Poland
            'PL' => array(
                self::STANDARD => 23
            ),
            
            // Portugal
            'PT' => array(
                self::STANDARD => 23
            ),
            
            // Romania
            'RO' => array(
                self::STANDARD => 24
            ),
            
            // Slovenia 
            'SI' => array(
                self::STANDARD => 22
            ),
            
            // Slovakia
            'SK' => array(
                self::STANDARD => 20
            ),
            
            // Finland
            'FI' => array(
                self::STANDARD => 24
            ),
            
            // Sweden
            'SE' => array(
                self::STANDARD => 25
            ),
            
            // United Kingdom
            'UK' => array(
                self::STANDARD => 20
            )
        );
        
        $country = $rates[$this->getCustomerCountry()];
        
        if (!$country)
        {
            throw new Exception('Unknown country '.$this->getCustomerCountry());
        }
        
        $rate = $country[$this->getProductType()];
        
        if (!$rate)
        {
            $rate = $country[self::STANDARD];
        }
        
        $this->setTaxRate($rate);
        
        return $rate;
        
    }
    
}
