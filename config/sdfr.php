<?php

/**
 * تنظیمات اختصاصی SDFR — برای اتصال صفحهٔ خرید دوره به محصول مرکزی و سایر
 * مقادیر ثابت پروژه.
 */
return [

    /**
     * شناسهٔ محصولی که در سبد سفارش «دورهٔ مشاورهٔ SDFR» استفاده می‌شود.
     * این محصول باید در `products` ذخیره شده باشد (در ProductsTableSeeder).
     */
    'course_product_id' => (int) env('SDFR_COURSE_PRODUCT_ID', 1),

];
