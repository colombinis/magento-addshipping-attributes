#### Magento 2 module

Description: add 2 custom fields to Customer Shipping and Billing

to install:

* copy to  app/code
* usual steps
```bash 
rm -rf var/di
rm -rf var/generation
rm -rf var/cache
rm -rf var/page_cache
rm -rf var/log
rm -rf var/view_preprocessed

bin/magento setup:upgrade

bin/magento setup:di:compile
# bin/magento setup:static-content:deploy
bin/magento cache:flush
bin/magento cache:clean
```
