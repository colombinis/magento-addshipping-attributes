# Sacsi_CustomAttribute for Magento 2

This module adds attributes (Billing/Shipping Address)

## Install
Manually
```
1- clone repo
2- Remame git cloned folder to -> Sacsi_CustomAttribute
3- copy manually in <magento_root>/app/code folder

```

TODO check install/unistall with composer:
```
[warning - it is not working yet]
composer require sacsi/magento2-add-piso-depto-attributes
```

Enable the module and execute dinamic code generation

```
bin/magento module:enable Sacsi_CustomAttribute
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
bin/magento cache:clean
bin/magento cache:flush
```

## Uninstall

```
[warning - it is not working yet]
bin/magento module:uninstall Sacsi_CustomAttribute
```