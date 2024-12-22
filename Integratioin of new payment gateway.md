# Integratioin of new payment gateway

To integrate a payment gateway in Pixer-Laravel, you will need to follow these general steps:

Source link：https://pixer-doc.vercel.app/integratioin-new-payment-gateway#getting-started-with-shop-front

## [Getting Started with API](https://pixer-doc.vercel.app/integratioin-new-payment-gateway#getting-started-with-api)

### [Step 1: Install and configure the payment gateway package](https://pixer-doc.vercel.app/integratioin-new-payment-gateway#step-1-install-and-configure-the-payment-gateway-package)

First, you will need to install the payment gateway package for Laravel (if there are any available package). There are several packages available that provide integration with various payment gateways. Example is given in the screenshot.

Once you have chosen and installed a package, you will need to configure it by adding your payment gateway credentials and any other required settings.

To check the installed dependencies of payment gateway in Pixer-Laravel, you can open the composer.json file in a text editor and find that.

![payment-composer.png](https://pixer-doc.vercel.app/static/payment-composer-34d5e02c9d7012f466d25a7ef76622ad.png)

### [Step 2: Add payment gateway name in the Enum.](https://pixer-doc.vercel.app/integratioin-new-payment-gateway#step-2-add-payment-gateway-name-in-the-enum)

Add PaymentGateway Enum `API -> package -> marvel -> src -> Enums -> PaymentGatewayType.php`

![payment-gateway-type.png](https://pixer-doc.vercel.app/static/payment-gateway-type-b1f126c67f24601f9b03a5d7e8a30934.png)

### [Step 3: Configure the Payment Facade for the new payment gateway.](https://pixer-doc.vercel.app/integratioin-new-payment-gateway#step-3-configure-the-payment-facade-for-the-new-payment-gateway)

Now go to `API -> package -> marvel -> src -> Payment` then create new payment Class (e.g. Stripe, PayPal, Razorpay, Mollie) for implements the PaymentInterface.The Class must implements all of the methods defined in the PaymentInterface.

![gateway-class.png](https://pixer-doc.vercel.app/static/gateway-class-9c016906379216cf1f044ae542068365.png)

Methods defined in the PaymentInterface

![payment-interface.png](https://pixer-doc.vercel.app/static/payment-interface-64a5357a185ed469a0e6fc507f1ba1bd.png)

Here's an example of a class that implements that PaymentInterface:

![interface-implements-example.png](https://pixer-doc.vercel.app/static/interface-implements-example-c61d4ca1845691b32262237254a88bfc.png)

***Note :\*** It is important to note that each payment gateway has its own set of requirements and may have different methods for processing payments. You will need to follow the official documentation of that specific payment gateway.

### [Step 4: Using that payment gateway for submitting the order.](https://pixer-doc.vercel.app/integratioin-new-payment-gateway#step-4-using-that-payment-gateway-for-submitting-the-order)

Go to `API -> package -> marvel -> src -> Http -> Controllers -> OrderController.php -> submitPayment` then you've to add your PaymentGatewayType and function name in switch case.

![supmit-payment.png](https://pixer-doc.vercel.app/static/supmit-payment-b495596be55ce94b1299d9419cc1c924.png)

After That go to `API -> package -> marvel -> src -> Traits -> PaymentStatusManagerWithOrderTrait.php` for verify your payment status update, you've to add function like Stripe, PayPal, Razorpay or Mollie.

![trait_function.png](https://pixer-doc.vercel.app/static/trait_function-6f28e1f63d1bd922a2dd1dcebfd076a3.png)

### [Step 5: How to Setup webhook in Pixer follow the steps](https://pixer-doc.vercel.app/integratioin-new-payment-gateway#step-5-how-to-setup-webhook-in-pixer-follow-the-steps)

To use a payment gateway webhook, you would first configure the webhook URL in your payment gateway account. Then, whenever the specified events occur, the payment gateway will send an HTTP POST request to the webhook URL with a payload of data about the event.

First go to `API -> package -> marvel -> src -> Routes` then add a post route.

![webhook-route.png](https://pixer-doc.vercel.app/static/webhook-route-e873b9e0b8de1439f2d6e22cf175e6cb.png)

Add your function in WebHookController

![webhooks-controller.png](https://pixer-doc.vercel.app/static/webhooks-controller-2a9c5baae4a9770540b5ac9481b5f1bd.png)

To handle ***webhook events\*** follow your payment gateway offical webhook documentation

![handle-webhook.png](https://pixer-doc.vercel.app/static/handle-webhook-860211b636fe29ced33f30da83a830fb.png)

***Note :\*** For locally webhook testing you can use ngrok tools for that. Please follow their official documentation.

## [Getting Started with admin dashboard.](https://pixer-doc.vercel.app/integratioin-new-payment-gateway#getting-started-with-admin-dashboard)

First go to `marvel-admin -> rest -> types -> index.ts -> export enum PaymentGateway` then add PaymentGateway Name.

![admin-payment-enum.png](https://pixer-doc.vercel.app/static/admin-payment-enum-ccb06a0430656d1e8981bf4270731be0.png)

after that go to `marvel-admin -> rest -> src ->components -> setings -> payment.ts` add name & Title.

![setings-payments.png](https://pixer-doc.vercel.app/static/setings-payments-981121a6f60760d74b3ec75ec01654cb.png)

Then it will automatically add that payment gateway in the marvel-admin settings.

## [Getting Started with shop front.](https://pixer-doc.vercel.app/integratioin-new-payment-gateway#getting-started-with-shop-front)

There are two types of payment gateway system can be integrated here.

- Redirect based payment gateway (e.g PayPal). Where the customer will redirect to that payment gateway site during order checkout. Complete the payment there. And then comeback to the application.
- Non redirect based payment gateway. Where the customer will stay on the application and complete the whole payment process here. Here we consider Stripe as a non-redirect based payment gateway. Though Stripe has features too similar to redirect based payment gateway.

### [Redirect-base Payment Gateway](https://pixer-doc.vercel.app/integratioin-new-payment-gateway#redirect-base-payment-gateway)

if you want to integrate redirect based payment gateway (e.g. PayPal, Mollie). follow the steps

First go to `shop -> src -> types -> index.ts -> export enum PaymentGateway` then add PaymentGateway Name.

![admin-payment-enum.png](https://pixer-doc.vercel.app/static/admin-payment-enum-ccb06a0430656d1e8981bf4270731be0.png)

after that go to `shop -> src -> components -> checkout -> payment -> Payment-grid.tsx` then add your PaymentGateway object.

![checkout-payment.png](https://pixer-doc.vercel.app/static/checkout-payment-e1b0910654644bdf4140f3f84bea7c1a.png)

### [Non-redirect based payment gateway](https://pixer-doc.vercel.app/integratioin-new-payment-gateway#non-redirect-based-payment-gateway)

First, complete the redirect-based payment gateway steps mentioned above. Because that two steps is universal for all payment gateway to apply.

After That go to `shop -> src -> components -> payment` then add a folder like Stripe, Razorpay. Inside your payment folder you can create your required typescript files with related functionalities for your Payment Gateway. For example, you can checkout the Stripe folder. You can find all the necessary indication, components guide, payment-method (card) saving options etc in the Stripe folder.

![custom-typescript-file.png](https://pixer-doc.vercel.app/static/custom-typescript-file-298766e1df878bc0f28fb1c6a9697145.png)

That's all for today. If you need any more help you can always contact with our support agents in our support portal.