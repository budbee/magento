## Get Started

[Budbee Api documentation](http://developer.budbee.com)

[Budbee php adapter](https://github.com/budbee/budbee-php)

## Requirements

1. Ask for credentials from Budbee
2. Product attribute must be set to all shippable products (ship with budbee > yes)
3. add to transactional Emails

```<p><span>Selected time:</span> {{var order.getBudbeeDesiredDeliveryFormatedDate()}}</p>
{{if order.budbee_door_code}}<p><span>Code Door: </span>{{var order.budbee_door_code}}</p>{{/if}}
{{if order.budbee_outside_door}}<p><span>Outside Door: </span>{{var order.getBudbeeOutsideDoorformated()}}</p>{{/if}}```


## ToDo

1. Take in account Grouped, bundles products
2. add adminhtml class for price ( accept only float)  --optional
3. add JS toggle budbee method
4. add budbee extra form data in progress ( rewrite block progress)template for d
5. add and Editing delivery date in adminhtml
6. locale
7. sales/order/print/order_id/660/  add extra data in print order
8. check if delivery date is shown in invoice
9. Unit tests
10. log api , debug , exception
11. connect lib from external fork


## Budbee API questions 


### PostalCode validation
1. lybe gets 2 items with postal code call 
2. how to set interval - magento and budbee interface , get all intervals
3. if postal code not covered by budbee will we show the shipping method anyway ?

### get intervals  
1. DO I need to show up collection time with Order i backend ?
2. additional info add to sales process add DoorCode , OutsideDoor (animail's need ?)

## Tell budbee php adapter
1. collectionPointId(s) instead of collectionPointId
2. edi ??
3.  Can't connect to the api: http://sandbox.api.budbee.com/multiple/orders response code: 400
   {"message":"Can not deserialize instance of long out of START_ARRAY token"}  check the object in border.log




