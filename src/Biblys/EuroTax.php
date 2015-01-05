<?php 

namespace Biblys;

class EuroTax
{
    const STANDARD = 1, // Default tax rate to use with unknown type
        BOOK = 2, 
        EBOOK = 3, 
        AUDIOBOOK = 4, // Physical audio books
        EAUDIOBOOK = 5, // Downloadable audio books
        PERIODICAL = 6, // Magazines
        CD = 7,
        DVD = 8;
    
    private $customerCountry,
        $sellerCountry,
        $productType,
        $dateOfSale,
        $taxRate,
        $downloadable = array(self::EBOOK, self::EAUDIOBOOK),
        $isDownlodable = false,
        $rates = array(
            
            // Belgium
            'BE' => array(
                self::STANDARD => 21,
                self::BOOK => 6
            ),
            
            // Bulgaria
            'BU' => array(
                self::STANDARD => 20
            ),
            
            // Czech Republic
            'CZ' => array(
                self::STANDARD => 21,
                self::BOOK => 10
            ),
            
            // Denmark
            'DK' => array(
                self::STANDARD => 25
            ),
            
            // Germany
            'DE' => array(
                self::STANDARD => 19,
                self::BOOK => 7
            ),
            
            // Estonia
            'EE' => array(
                self::STANDARD => 20,
                self::BOOK => 9
            ),
            
            // Greece
            'EL' => array(
                self::STANDARD => 23,
                self::BOOK => 6.5
            ),
            
            // Spain
            'ES' => array(
                self::STANDARD => 21,
                self::BOOK => 4
            ),
            
            // France
            'FR' => array(
                self::STANDARD => 20,
                self::BOOK => 5.5,
                self::EBOOK => 5.5,
                self::EAUDIOBOOK => 5.5,
                self::PERIODICAL => 2.1
            ),
            
            // Croatia
            'HR' => array(
                self::STANDARD => 25,
                self::BOOK => 5
            ),
            
            // Ireland
            'IE' => array(
                self::STANDARD => 23,
                self::BOOK => 0
            ),
            
            // Italy
            'IT' => array(
                self::STANDARD => 22,
                self::BOOK => 4
            ),
            
            // Cyprus
            'CY' => array(
                self::STANDARD => 19,
                self::BOOK => 5
            ),
            
            // Latvia
            'LV' => array(
                self::STANDARD => 21,
                self::BOOK => 12
            ),
            
            // Lithuania
            'LI' => array(
                self::STANDARD => 21,
                self::BOOK => 9
            ),
            
            // Luxembourg
            'LU' => array(
                self::STANDARD => 15,
                self::BOOK => 3,
                self::EBOOK => 3
            ),
            
            // Hungary
            'HU' => array(
                self::STANDARD => 27,
                self::BOOK => 5
            ),
            
            // Malta
            'MT' => array(
                self::STANDARD => 18,
                self::BOOK => 5
            ),
            
            // Netherlands
            'NL' => array(
                self::STANDARD => 21,
                self::BOOK => 5
            ),
            
            // Austria
            'AT' => array(
                self::STANDARD => 20,
                self::BOOK => 10
            ),
            
            // Poland
            'PL' => array(
                self::STANDARD => 23,
                self::BOOK => 5
            ),
            
            // Portugal
            'PT' => array(
                self::STANDARD => 23,
                self::BOOK => 6
            ),
            
            // Romania
            'RO' => array(
                self::STANDARD => 24,
                self::BOOK => 9
            ),
            
            // Slovenia 
            'SI' => array(
                self::STANDARD => 22,
                self::BOOK => 9.5
            ),
            
            // Slovakia
            'SK' => array(
                self::STANDARD => 20,
                self::BOOK => 10
            ),
            
            // Finland
            'FI' => array(
                self::STANDARD => 24,
                self::BOOK => 10
            ),
            
            // Sweden
            'SE' => array(
                self::STANDARD => 25,
                self::BOOK => 6
            ),
            
            // United Kingdom & Northern Ireland
            'GB' => array(
                self::STANDARD => 20,
                self::BOOK => 0
            )
        );
    
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
     * Set the customer country
     * @param string $country An ISO-3166 country code
     */
    public function setCustomerCountry($country) 
    {
        if (empty($this->sellerCountry))
        {
            throw new \Exception("Seller's country must be set before customer's.");
        }
        
        $country = strtoupper($country);
        
        // If unhandled country, fallback to seller's
        if (!isset($this->rates[$country]))
        {
            $country = $this->getSellerCountry();
        }
        
        $this->customerCountry = $country;
    }
    
    public function getCustomerCountry() 
    {
        return $this->customerCountry;
    }
    
    private function setDownloadable()
    {
        $this->isDownloadable = true;
    }
    
    public function isDownloadable()
    {
        return $this->isDownloadable;
    }
    
    /**
     * Set the product type 
     * @param CONST $type See CONSTs
     */
    public function setProductType($type)
    {
        $country = $this->getCustomerCountry();
        
        if (in_array($type, $this->downloadable))
        {
            $this->setDownloadable();
        }
        
        if (!isset($this->rates[$country][$type]))
        {
            $type = self::STANDARD;
        }
        
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
        
        $rate = $this->rates[$this->getCustomerCountry()][$this->getProductType()];
        
        $this->setTaxRate($rate);
        
        return $rate;
        
    }
    
}
