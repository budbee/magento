## Get Started

[API Documentation](http://developer.budbee.com)

[PHP Wrapper](https://github.com/budbee/budbee-php) (The base for this project)

## Requirements

1. API credentials (support@budbee.com)

## General Information
1. All products are shippable with Budbee
3. Product attribute created and assigned to default attribute set (ship with budbee)
 - if you want to exclude a product add the attribute to your attribute set then update the product (Ship with Budbee: no)
4. If you want to show additional information about Budbee in transactional Emails add the following code :

```html
<p><span>Selected time:</span>{{var order.getBudbeeDesiredDeliveryFormatedDate()}}</p>
{{if order.budbee_door_code}}<p><span>Code Door: </span>{{var order.budbee_door_code}}</p>{{/if}}
{{if order.budbee_outside_door}}<p><span>Outside Door: </span>{{var order.getBudbeeOutsideDoorformated()}}</p>{{/if}}
```

## Installation
```
modman clone https://github.com/budbee/magento
```



## ToDo

 - [ ] Take in account Grouped, bundled products
 - [ ] Add adminhtml class for price (accept only float)  --optional
 - [ ] aAdd JS toggle Budbee method
 - [ ] aAd Budbee extra form data in progress (rewrite block progress) template for d
 - [ ] aAdd and Editing delivery date in adminhtml
 - [ ] Locale
 - [ ] sales/order/print/order_id/660/  add extra data in print order
 - [ ] Check if delivery date is shown in invoice
 - [ ] Unit tests
 - [ ] Log api, debug, exception
 - [ ] Connect lib from external fork
 - [ ] Add discountRate, Tax Rate in Budbee order
