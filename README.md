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
4. add budbee extra form data in progress ( rewrite block progress)
5. add and Editing delivery date in adminhtml
6. locale
7. sales/order/print/order_id/660/  add extra data in print order
8. check if delivery date is shown in invoice
9. Unit tests




