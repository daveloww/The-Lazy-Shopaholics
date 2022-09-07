# The Lazy Shopaholics

## Table of contents
1. [Introduction](#Introduction)
2. [Technical Overview](#Technical-Overview)
    1. [Technologies, Frameworks, Libraries Used](#tech)
3. [Prerequisites](#Prerequisites)
4. [Getting Started](#Getting-Started)
    1. [Clone Repository](#Clone-Repository)
    2. [Database Setup](#Database-Setup)
    3. [API Key Setup](#API-Key-Setup)
5. [Running the Application](#Running-the-Application)
6. [Navigating the Application](#Navigating-the-Application)
    1. [Search By Product Name](#Search-By-Product-Name)
    2. [Search By Category](#Search-By-Category)
    3. [Sort By Price or Popularity](#Sort-By-Price-or-Popularity)
    4. [Recommended Popular Items](#Recommended-Popular-Items)
    5. [Product Description and Reviews](#Product-Description-and-Reviews)
    6. [Log In](#Log-In)
    7. [Register](#Register)
    8. [Change Password](#Change-Password)
    9. [Log Out](#Log-Out)
    10. [Cart](#Cart)
        1. [Add Product to Cart](#Add-Product-to-Cart)
        2. [View Cart](#View-Cart)

<br>

## Introduction
We all know that the same product could be sold on MILLION e-commerce websites and listed at different prices! We all know the pain of going through MILLION websites.. just to compare prices to get the best deal. But calm down, The Lazy Shopholics app is here to save you! 

The Lazy Shopaholics is created to save your precious time and effort as it can aggregate product search results from multiple e-commerece websites and render them nicely on a single page! NO NEED TO BROWSE MULTIPLE WEBSITES. It can also recommend popular items across e-commerce sites. Most importantly as our target audience are Singaporeans, prices that are denominated in foreign currency are all converted to SGD for you. 

This repository contains instructions of setting up and running the application on localhost, meant for **Windows** or **Mac** OS. Ensure that you also have all the [prerequisites](#Prerequisites) covered before you [get started](#Getting-Started).

[Back To The Top](#The-Lazy-Shopaholics)

<br>

## Technical Overview
The main focus of this app is to experiment on front-end coding, which includes building responsive webpages. However, back-end coding is still involved, as a database was used to store user-related data. For all other data (i.e., products from multiple sites and exchange rate), the app retrieves the data by communicating (asynchronously) with external APIs via the [JavaScript Fetch API](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch). The [JavaScript Promise API's static method - Promise.all()](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise/all) is used to wrap these asynchronous calls, as it will wait for all calls (promises) to be fulfilled, before aggregating and returning all the results. The aggregation part is crucial as the app needs all the product data from multiple e-commerce sites (external APIs) before it can start sorting by price. At the same time, it needs the exchange rate - retrieved from one of the external APIs - to be able to perform currency conversion.

### Technologies, Frameworks, Libraries Used <a name="tech"></a>
- HTML
- CSS
- JavaScript
- [Bootstrap](https://getbootstrap.com/)
- [Font Awesome](https://fontawesome.com/)
- [Animate.css](https://animate.style/)
- PHP
- MySQL

[Back To The Top](#The-Lazy-Shopaholics)

<br>

## Prerequisites
- [Git](https://git-scm.com/downloads): >= 2.21
- [WAMP](https://www.wampserver.com/en/download-wampserver-64bits/) (Windows)/ [MAMP](https://www.mamp.info/en/downloads/) (Mac)
  - PHP >= 7.4
  - MySQL >= 8.0
- [RapidAPI](https://rapidapi.com/) Account + API Key
  - [Currency Exchange API](https://rapidapi.com/fyhao/api/currency-exchange) (Free)
  - [Amazon Product/Reviews/Keywords API](https://rapidapi.com/logicbuilder/api/amazon-product-reviews-keywords) (Paid)
  - [Magic AliExpress API](https://rapidapi.com/b2g.corporation/api/magic-aliexpress1) (Paid)
  - [Taobao Advanced API](https://rapidapi.com/gabrielius.u/api/taobao-advanced) (Paid)
  - [Taobao API](https://rapidapi.com/gabrielius.u/api/taobao-api) (Paid)

[Back To The Top](#The-Lazy-Shopaholics)

<br>

## Getting Started
To get things started, you need to:
1. [Clone this repository](#Clone-Repository) into your local machine
2. [Set up database](#Database-Setup)
3. [Set up API key](#API-Key-Setup)

<br>

### Clone Repository
The app will be hosted on WAMP/MAMP. Therefore, please clone the repository into the WAMP/MAMP root directory.
- Windows: **www** directory
- Mac: **htdocs** directory

<br>

Using your command-line interface/terminal window, navigate to the WAMP/MAMP root directory and run the following command:
```
git clone https://github.com/daveloww/The-Lazy-Shopaholics.git
```
You will be prompted to log in to a Github account. Please log in to your Github account and you will be able to access this public repository.

<br>

### Database Setup
1. Start WAMP/MAMP
2. Launch your web browser and log in to [phpMyAdmin](http://localhost/phpmyadmin/)
3. At the homepage:
   - Click the "Import" tab
   - Click the "Choose File" button
   - Navigate to WAMP/MAMP root directory > "The-Lazy-Shopaholics" directory ([previously cloned](#Clone-Repository)) > "database" directory > "load.sql" file
   - Click the "Go" button at the bottom
   - Upon completion, you should see a green prompt stating that "*Import has been successfully finished..*" and the newly created database schema "lazy_shopaholics" should appear at the left
4. Launch an IDE of your choice and navigate to the "The-Lazy-Shopaholics" directory > "include" directory > "ConnectionManager.php" file
5. In line 7 and 8, please enter the username and password that you used in step 2
6. In line 9, please enter the port number used by phpMyAdmin. If unsure, you can check by logging into phpMyAdmin - the port number is shown at the top level corner, beside the logo, in the form of "Server: MySQL: XXXX". XXXX is the port number

<br>

### API Key Setup
1. Log in to your account at RapidAPI and copy your API key
2. Launch an IDE of your choice
3. Navigate to the "The-Lazy-Shopaholics" directory > "script" directory > "populate_**category**.js" file
3. In line 2, paste the API key
4. Navigate to the "The-Lazy-Shopaholics" directory > "script" directory > "populate_**index**.js" file
5. In lines 24, 364, and 407, paste the API key
6. Navigate to the "The-Lazy-Shopaholics" directory > "script" directory > "populate_**search**.js" file
7. In lines 32, 375, and 418, paste the API key

[Back To The Top](#The-Lazy-Shopaholics)

<br>

## Running the Application
Please ensure that you have fulfilled the [prerequisites](#Prerequisites) and accomplished the steps in [getting started](#Getting-Started) above.

If have already done so, all you have to do is to start WAMP/MAMP, launch your web browser, and type in [http://localhost/The-Lazy-Shopaholics](http://localhost/The-Lazy-Shopaholics)

[Back To The Top](#The-Lazy-Shopaholics)

<br>

## Navigating the Application

### Search By Product Name
On the homepage, enter the product's name in the search bar.

<br>

### Search By Category
On the homepage, select a category from the dropdown.

<br>

### Sort By Price or Popularity
After attempting to search by product name or category, you will be directed to another page. On this new page, products will appear in the form of cards. By default, products are sorted by price (increasing order). If you wish to sort by popularity instead, please click on the "Popularity" button.

<br>

### Recommended Popular Items
On the homepage, under the section "Popular items", you will be able to see the recommended popular items in the form of cards.

<br>

### Product Description and Reviews
If you are interested to know more about a product, please click on the card and a modal will appear. The modal will display the detailed description and reviews, if they exist.

<br>

### Log In
At the navigation bar, click "Account" > "Log in".

<br>

### Register
At the navigation bar, click "Account" > "Register".

<br>

### Change Password
At the navigation bar, click "Account" > "Log out".

<br>

### Log Out
At the navigation bar, click "Account" > "Change password".

<br>

### Cart
#### Add Product to Cart
There is a 'shopping cart' icon on every product card. Click on the icon to add the product to the cart. Alternatively, click on the product card > click on pink "Favourite" button.

#### View Cart
At the navigation bar, click "Cart".

<br>

[Back To The Top](#The-Lazy-Shopaholics)
