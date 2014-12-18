EuroTax
=======

Calculates taxes for all european countries, according to whether the seller's country tax rate (most product types) or the customer's country tax rate (downlodable products among others since 1st january 2015) applies.

VAT rates are extracted from this document:
http://ec.europa.eu/taxation_customs/resources/documents/taxation/vat/how_vat_works/rates/vat_rates_en.pdf 

# Installation

With composer :

    composer require biblys/eurotax:dev-master

# Usage

Say you're selling an ebook from a french bookshop to a belgian customer: Belgium ebook tva rate must be used. 

    $tax = new \Biblys\EuroTax();
    $tax->setSellerCountry('FR');
    $tax->setCustomerCountry('BE');
    $tax->setProductType(\Biblys\EuroTax::EBOOK);
    
    echo $tax->getTaxRate(); // Will echo 21

Short version:

    $tax = new \Biblys\EuroTax('BE', 'FR', \Biblys\EuroTax::EBOOK);
    echo $tax->getTaxRate(); // Will echo 21
    
# Available countries

* BE: Belgium
* BU: Bulgaria
* CZ: Czech Republic
* DK: Denmark
* DE: Germany
* EE: Estonia
* EL: Greece
* ES: Spain
* FR: France
* HR: Croatia
* IE: Ireland
* IT: Italy
* CY: Cyprus
* LV: Latvia
* LT: Lithania
* LU: Luxembourg
* HU: Hungary
* MT: Malta
* NL: Netherlands
* AT: Austria
* PL: Poland
* PT: Portugal
* RO: Romania
* SI: Slovenia
* SK: Slovakia
* FI: Finland
* SE: Sweden
* UK: United Kingdom


# Available product types

* BOOK
* EBOOK
* AUDIOEBOOK (selled on physical support, eg. CD)
* EAUDIOBOOK (downloable audio books)
* CD
* DVD

# Contribute

I just this class with just the countries and product types I needed in my app, but feel free to add whatever your need and share it by doing pull requests.

Also the class should take in account the date of sale, as tax rates may vary over time. For instance, the ebook tax rate in France has change three times this last few years.
