## 主题自定义的Meta Keys列表

### User Meta

* tt_user_country
* tt_user_province
* tt_user_city
* tt_user_sex
* tt_weixin_avatar
* tt_weixin_unionid
* tt_avatar_type
* tt_user_description
* tt_user_location
* tt_weibo_avatar
* tt_weibo_profile_img
* tt_weibo_id
* tt_user_cover
* tt_latest_login
* tt_latest_login_ip
* tt_latest_login_before
* tt_latest_ip_before
* tt_banned
* tt_banned_reason
* tt_banned_time
* tt_default_address_id

* tt_credits
* tt_consumed_credits
* tt_daily_sign

* tt_view_product_history (array<product id>) //cookie use same key

* tt_bought_posts

### Post Meta
* views (postViews插件)
* tt_post_stars (废弃)
* tt_post_star_users (不唯一)
* tt_sidebar
* tt_latest_reviewed
* tt_free_dl
* tt_sale_dl
* tt_embed_product


### Post Meta(Product)
* tt_product_price
* tt_product_quantity
* tt_pay_currency (0->credit 1->cash)
* tt_product_sales
* tt_product_discount (array, 折扣使用百分数的数值部分, serialize后保存)
* tt_buy_channel (instation/taobao)
* tt_taobao_link
* tt_latest_rated
* tt_product_download_links
* tt_product_pay_content

### Post Meta(Page)
* tt_keywords
* tt_description


### Comment Meta
* tt_comment_likes

### Comment Meta(Product)
* tt_rating_product (购买的用户可通过评论对product rating, 记录在commentMeta)