# M2Commerce Commerce: Magento 2 Book An Appointment

## Description
This extension allows customers to place/book an appointment at your store for your selected products.
The comment field is displayed on product detail page when you enable Book Appointment attribute.
A button will be available on click of which are popup foem will show up and customer can fill in information and submit it.
This form will be emailed to the configured receiver in admin configuration.

## Configuration

There are several configuration options for this extension, which can be found at **STORES > Configuration > Commerce Enterprise > Book an Appointment**.

## Installation
### Magento® Marketplace

This extension will also be available on the Magento® Marketplace when approved.

1. Go to Magento® 2 root folder
2. Require/Download this extension:

   Enter following commands to install extension.

   ```
   composer require m2commerce/book-an-appointment"
   ```

   Wait while composer is updated.

   #### OR

   You can also download code from this repo under Magento® 2 following directory:

    ```
    app/code/M2Commerce/BookAnAppointment
    ```    

3. Enter following commands to enable the module:

   ```
   php bin/magento module:enable M2Commerce_BookAnAppointment
   php bin/magento setup:upgrade
   php bin/magento setup:di:compile
   php bin/magento cache:clean
   php bin/magento cache:flush
   ```

4. If Magento® is running in production mode, deploy static content:

   ```
   php bin/magento setup:static-content:deploy
   ```
