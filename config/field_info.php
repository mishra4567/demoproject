<?php

return [

    'product' => [
        'name'                    => 'Enter the full product name. This will be displayed on the frontend.',
        'slug'                    => 'URL-friendly version of the name. Use lowercase letters and hyphens only. e.g. my-product',
        'category_id'             => 'Select the category this product belongs to.',
        'brand'                   => 'Select the brand of this product.',
        'model'                   => 'Enter the model number or name of the product.',
        'media_id'                => 'Select the main display image for this product from the media library.',
        'coupon_id'               => 'Optionally select a coupon to apply to this product.',
        'gallery'                 => 'Select multiple images for the product gallery.',
        'short_desc'              => 'A brief summary of the product. Shown on listing pages. Keep it under 160 characters.',
        'desc'                    => 'Full detailed description of the product. Supports HTML.',
        'keywords'                => 'SEO keywords for this product. Separate with commas.',
        'technical_specification' => 'Technical details, dimensions, materials, etc.',
        'uses'                    => 'Describe the common uses or applications of this product.',
        'warranty'                => 'Warranty period and terms for this product.',
    ],

    'linkproduct' => [
        'product_id' => 'Select the product this variant belongs to.',
        'sku'        => 'Stock Keeping Unit — a unique identifier for this variant. e.g. PROD-RED-XL',
        'mrp'        => 'Maximum Retail Price — the original price before any discount.',
        'price'      => 'Selling price of this variant. Should be less than or equal to MRP.',
        'size_id'    => 'Select the size for this variant.',
        'color_id'   => 'Select the color for this variant.',
        'qty'        => 'Available stock quantity for this variant.',
        'media_id'   => 'Select the image for this specific variant.',
    ],

    'category' => [
        'category_name' => 'Enter the category name. e.g. Electronics, Clothing.',
        'slug'          => 'URL-friendly version of the category name.',
        'media_id'      => 'Select a display image for this category.',
    ],

    'size' => [
        'size'   => 'Enter the size label. e.g. S, M, L, XL, 42, 10.',
        'status' => 'Set whether this size is active and available for selection.',
    ],

    'color' => [
        'color'  => 'Enter the color name or hex code. e.g. Red or #FF0000.',
        'status' => 'Set whether this color is active and available for selection.',
    ],

    'coupon' => [
        'title'      => 'Enter a descriptive title for this coupon. e.g. "20% Off Summer Sale".',
        'code'       => 'Unique coupon code customers will enter at checkout. e.g. SAVE20',
        'discount'   => 'Discount value — either a percentage or flat amount.',
        'type'       => 'Select whether the discount is a percentage (%) or fixed amount.',
        'min_order'  => 'Minimum order value required to use this coupon.',
        'expires_at' => 'Expiry date of this coupon. Leave empty for no expiry.',
        'status'     => 'Set whether this coupon is currently active.',
        'one_time'  => 'If checked, this coupon can only be used once per customer.',
    ],

    'media' => [
        'file'   => 'Upload an image file. Supported formats: JPG, PNG, WEBP. Max size: 2MB.',
        'tags'   => 'Add tags to help search and filter media. Separate with commas.',
        'status' => 'Set whether this media file is active and usable.',
    ],

    'technical_spec' => [
        'title'          => 'Title or heading for this specification entry.',
        'lead_time_from' => 'Earliest expected delivery or lead time start date.',
        'lead_time_to'   => 'Latest expected delivery or lead time end date.',
        'tax'            => 'Tax percentage applicable to this product. e.g. 18 for 18% GST.',
        'tax_type'       => 'Type of tax — None, Inclusive (tax included in price), or Exclusive (tax added on top).',
        'is_promo'       => 'Mark this product as promotional to feature it in promo sections.',
        'is_featured'    => 'Mark this product as featured to show it on the homepage or featured section.',
        'is_discounted'  => 'Mark this product as discounted to show a discount badge.',
        'is_trending'    => 'Mark this product as trending to show it in trending sections.',
    ],

];
