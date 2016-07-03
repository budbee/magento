## Get Started

[Budbee Api documentation](http://developer.budbee.com)

[Budbee php adapter](https://github.com/budbee/budbee-php)

## Requirements

1. Ask for credentials from Budbee

## How does it works
1. All products are shippable with budbee
3. Product attribute created and assigned to default attribute set (ship with budbee)
 - if you want to exclude a product add the attribut to your attribute set then update the product (ship with budbee > no)
4. if you want to show additional information about budbee in transactional Emails add the following code :

```<p><span>Selected time:</span> {{var order.getBudbeeDesiredDeliveryFormatedDate()}}</p>
{{if order.budbee_door_code}}<p><span>Code Door: </span>{{var order.budbee_door_code}}</p>{{/if}}
{{if order.budbee_outside_door}}<p><span>Outside Door: </span>{{var order.getBudbeeOutsideDoorformated()}}</p>{{/if}}```

## Instalation
``` modman clone https://github.com/LybeAB/Lybe_Budbee```



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
12. add discountRate , Tax Rate in Budbee order
13. handleResponse in magento's way


