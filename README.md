# GeneratePress AT-NRW

## General

This theme is a child theme of the [WordPress](https://wordpress.org/) theme 
[GeneratePress](https://generatepress.com/). It was designed for the web portal
[AT-NRW](https://at-nrw.dobus.tu-dortmund.de/) of the 
[DoBuS](https://dobus.zhb.tu-dortmund.de/) of [TU Dortmund University](https://www.tu-dortmund.de/). 
The theme offers various shortcodes to dynamically generate those elements of the subpages that 
interact with the application data in the database. It also adapts certain processes 
on the website and adds additional CSS and JavaScript. 

## Offered shortcodes

### Shortcodes for the public display of data

* `disabilities`: lists all disability categories and forms of impairment and displays detailed information
* `limitations`: lists all functional limitations and suitable aids
* `aids`: lists all assistive categories and assistive products and displays detailed information
* `universities`: lists all universities, displays detailed information and lists all assistive technologies offered
* `comprehensive_links`: display all comprehensive links

## Shortcodes for listing all database entries for database maintenance

* `list_editable_disability_categories`: lists all disability categories
* `list_editable_disabilities`: lists all disabilities
* `list_editable_limitations`: lists all functional limitations
* `list_editable_product_categories`: lists all product categories
* `list_editable_products`: lists all products
* `list_editable_universities`: lists all universities
* `list_editable_consultants`: lists all consultants at universities
* `list_editable_links`: lists all links for further information

## Shortcodes for the forms for database maintenance

* `disability_category_form`: form for creation and of editing disability categories
* `disability_form`: form for creation and editing of disabilities
* `limitation_form`: form for creation and editing of functional limitations
* `product_category_form`: form for creation and editing of product categories
* `product_form`: form for creation and editing of products
* `university_form`: form for creation and editing of universities
* `consultant_form`: form for creation and editing of consultants at universities
* `additional_link_form`: form for creation and editing of additional links

## Other features of the theme

In addition to the shortcodes, the theme also offers other functions. For example, 
it provides CSS and JavaScript to improve the appearance and functionality of the 
website. Certain processes and the footer are improved by additional PHP actions. 

## Usage

To use the theme, the [GeneratePress](https://generatepress.com/) theme must first 
be downloaded and installed in the WordPress backend. This child theme must then be 
copied to the `wp-content/themes` folder. The “GeneratePress AT-NRW” child theme must 
then be activated. For the shortcodes for database maintenance (listings and forms), 
it must be ensured that the corresponding pages are only accessible to authorized users. 