# Validate VAT Number

Validate a European VAT number using the EU commission VIES service.

## Installation via Composer

    "require": {
        "drawmyattention/validate-vat-number" : "1.*"
    }
    
## Usage
    
### Check if a VAT number is valid
    
    $validator = new ValidateVatNumber();
    
    // True
    $validator->validate('GB371057172')->isValid();
        
    // False
    $validator->validate('GB123452341678')->isValid();
    
### Get the company name, address and country code
    
    $validator = new ValidateVatNumber();
    
    $validator->validate('GB371057172');
    
    // Mcdonald's Restaurants Limited
    $validator->company();
    
    // Array of the company's address
    $validator->address();
    
    // GB
    $validator->countryCode();
    
### Get the full response from the api

    $validator = new ValidateVatNumber();
    
    $validator->validate('GB371057172');
    
    // Full response object from the api
    $validator->response();