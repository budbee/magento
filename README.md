## Get Started

Budbee Documentation

## Requirements

1. Ask for credentials from Budbee
2. Product attribute must be set to all shippable products (ship with budbee > yes)
3. add to transactional Emails

<p style="font-size:12px; margin:0 0 10px 0"><span>Selected time:</span> {{var order.budbee_desired_delivery_date}}</p>
{{if order.budbee_door_code}}<p style="font-size:12px; margin:0 0 10px 0"><span>Code Door: </span>{{var order.budbee_door_code}}</p>
{{if order.budbee_outside_door}}<p style="font-size:12px; margin:0 0 10px 0"><span>Outside Door: </span>{{var order.budbee_outside_door}}</p>{{/if}}


## ToDo

1. Take in account Grouped, bundles products
2. add adminhtml class for price ( accept only float)  --optional
3. add JS toggle budbee method
4. add budbee extra form data in progress ( rewrite block progress)
5. Editing and displaying delivery date in adminhtml
6. locale
7. getDisplayFormattedDeliveryDate - format Date
8. sales/order/print/order_id/660/  add extra data in print order

Keep going with step http://adeptasoftware.co.uk/blog/magento-delivery-date-extension-part-4/


