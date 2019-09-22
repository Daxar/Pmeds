# Pmeds
Tingle P-Meds

To install extension run following commands at project root folder:  
composer config repositories.tingle-pmeds git https://github.com/Daxar/Pmeds.git  
composer require tingle/module-pmeds  

And then, as always:  
php bin/magento setup:upgrade  
php bin/magento setup:di:compile  
php bin/magento setup:static-content:deploy  

Module functionality path at admin panel:  
Adminhtml config path : Stores->Configurations->Tingle->Pmeds  
Add new question path : Products->Pmeds->Questions  
