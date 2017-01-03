## Post Meta Key
tin_dload -> tt_free_dl
UPDATE wp_postmeta SET meta_key='tt_free_dl' WHERE meta_key='tin_dload'

tin_saledl -> tt_sale_dl
UPDATE wp_postmeta SET meta_key='tt_sale_dl' WHERE meta_key='tin_saledl'

keywords -> tt_keywords
UPDATE wp_postmeta SET meta_key='tt_keywords' WHERE meta_key='keywords'

description -> tt_description
UPDATE wp_postmeta SET meta_key='tt_description' WHERE meta_key='description'

pay_currency -> tt_pay_currency
UPDATE wp_postmeta SET meta_key='tt_pay_currency' WHERE meta_key='pay_currency'

product_amount -> tt_product_quantity
UPDATE wp_postmeta SET meta_key='tt_product_quantity' WHERE meta_key='product_amount'

product_price -> tt_product_price
UPDATE wp_postmeta SET meta_key='tt_product_price' WHERE meta_key='product_price'

tin_views -> views
UPDATE wp_postmeta SET meta_key='views' WHERE meta_key='tin_views'

## User Meta Key
tin_credit -> tt_credits
UPDATE wp_usermeta SET meta_key='tt_credits' WHERE meta_key='tin_credit'

tin_credit_void -> tt_consumed_credits
UPDATE wp_usermeta SET meta_key='tt_consumed_credits' WHERE meta_key='tin_credit_void'

tin_twitter -> tt_twitter
UPDATE wp_usermeta SET meta_key='tt_twitter' WHERE meta_key='tin_twitter'

## Taxonomy
products_tag -> product_tag
UPDATE wp_term_taxonomy SET taxonomy='product_tag' WHERE taxonomy='products_tag'

products_category -> product_category
UPDATE wp_term_taxonomy SET taxonomy='product_category' WHERE taxonomy='products_category'

## Post
post_type: store -> product
UPDATE wp_posts SET post_type='product' WHERE post_type='store'
