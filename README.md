**Universal GA Conversion Track**

Summary

**Extension that gives the ability to **

- Track orders in Google Analytics based on status/state change.

- Use the new Universal Google Analytics.

**The problem:**

**Tracking orders in GA from users that close the browser before returning to success page.**

All merchants ask the same question: ¿Why isn´t the number of transactions in Google Analytics the same as the number of transactions in the Order Sales Admin section?

The answer is simple: Magento by default can’t track orders when the user closes the browser before landing on the success page.

In this page is where Magento sends the info to Google Analytics using the GA.js method.

So, if the customer is paying with an online method (eg. PayPal Standard) and closes the window at the PayPal Success Payment page… the order will not be tracked in Google Analytics.

In this case, there are fewer transactions in GA than in the Order Sales Admin Panel.

**Track orders in GA using non online payment methods: Money transfer, cash on delivery**

In addition to the problem mentioned before, there is another case that corrupts the number of real transactions in Google Analytics. 

If you are using a non online payment method (Money transfer, Invoice or Cash on delivery), all orders will finish on success page. 

So, whether the client pays or not, or receives the package and pays or not (COD and invoice), the order will be tracked in Google Analytics anyway.

**Google Changes the way of tracking the activity of the users on webpages.**

GA is using a new method called Universal Analytics. This new method introduces a set of features that changes the way data is collected and organized in the Google Analytics account.

Here is a summary of the new set of features: [https://support.google.com/analytics/answer/2790010?hl=en](https://support.google.com/analytics/answer/2790010?hl=en)

Magento does not support this new method by default

(Currently last CE version is 1.9 while writing this documentation)

**The solution:**

We developed a new extension that can solve all of these problems using:

Universal Analytics new method 

[https://support.google.com/analytics/answer/2790010?hl=en](https://support.google.com/analytics/answer/2790010?hl=en)

Atwix Universal Analytics extension

[http://www.magentocommerce.com/magento-connect/google-universal-analytics-3.html](http://www.magentocommerce.com/magento-connect/google-universal-analytics-3.html)

Interactiv4 GA Conversion track as a base

Measurement Protocol. [https://developers.google.com/analytics/devguides/collection/protocol/v1/](https://developers.google.com/analytics/devguides/collection/protocol/v1/)

And we also added new features:

- Selection of the order status/state to be tracked as a new order.

- Tracking all social media interactions.

**Using this extension you can also keep tracking the orders in order to have a proper Conversion Analytics Funnel with the correct data.**

We are collecting all the order data form the Checkout regarding the user session, in order to be sent to GA when the order status changes.

So your Conversion Funnel will not be broken using this extension.

**Magento Extension:**

**Important: USE THIS EXTENSION AT YOUR OWN RISK.**

Be sure that you make a full backup on your Magento Store before installing this Extension.

**Installation instructions:**

You can install the extension using three methods:

- Download here the Magento Connect Package.

- Use modman to install the extension.

- Just download the zip file from repository and unzip in the correct path in your Magento instance.

**Admin section ScreenShoot**

1. Select the statuses / status of the orders you want to track.

2. You have to define the Title and the URL to be sent to GA as a success page, when an order is tracked correctly

3. If you also want to include Twiter and Facebook interactions on all of your pages, you can activate here.

**Order tracked in Google Analytics history ScreenShoot**

**Contributors**:

Bas Blanken (NL)

Isolde van Oosterhout (NL)

Jernst Tempelaar (NL)

Peter Jaap Blaakmeer (NL)

Tjerk Ameel (NL)

Ignacio Riesco (ES)

Sherrie Rhode (US)

**Thanks**:

Atwix (www.atwix.com)

**License**:

This Magento Package is under [http://opensource.org/licenses/osl-3.0.php](http://opensource.org/licenses/osl-3.0.php)