$(window).load(function() {
    $(window).resize(function(){
        $( ".box_product" ).each(function() {
          // if ($('.box_product').siblings().not('.box_product_carousel')) {
               var box_item_w = $(this).width();
                if(box_item_w > 0){
                    $(this).find('> a').css({'height': box_item_w});
                    $(this).find('.box_product_img').css({'width': box_item_w,'height': box_item_w});
                }
            // }
        });
    }).resize(); 

    $(window).resize(function(){
       $( ".media_news .media-left" ).each(function() {
            var box_item_w = $(this).width();
            if(box_item_w > 0){
                var box_item_h = (box_item_w * 70.3297)/100; 
                $(this).find('> a').css({'height': box_item_h});
                $(this).find('.box_media_img').css({'height': box_item_h});
            }
        });
    }).resize(); 

    $(window).resize(function(){
        $( ".product_photo_img_link" ).each(function() {
            var box_item_w = $(this).width();
            if(box_item_w > 0){
                $(this).css({'height': box_item_w});
                $(this).find('.product_photo_img').css({'width': box_item_w,'height': box_item_w});
            }
        });
    }).resize();

    $(window).resize(function(){
        $( ".news_grid_photo" ).each(function() {
            var box_item_w = $(this).width();
            if(box_item_w > 0){
                var box_item_h = (box_item_w * 66.66)/100;
                $(this).css({'height': box_item_h});
                $(this).find('.box_img_news').css({'width': box_item_w,'height': box_item_h});
            }
        });
    }).resize();

    $(window).resize(function(){
        $( ".news_introduct_item_image" ).each(function() {
            var box_item_w = $(this).width();
            if(box_item_w > 0){
                var box_item_h = (box_item_w * 66.66)/100;
                $(this).find('> a').css({'height': box_item_h});
                $(this).find('.box_introduct_item_image').css({'width': box_item_w,'height': box_item_h});
            }
        });
    }).resize();
});