function priceAndPromoPrice(price, promo, showon = "productPrice"){
    if (price == 0 || price == ""){
        return false;
    }

    if ((promo > 0) && (promo != "") && (promo <= price)){
        $("#"+showon).text("₦"+numberWithCommas(promo))
    }else{
        $("#"+showon).text("₦"+numberWithCommas(price))
    }
}


// GET Short Data
function viewShortProductData(prod_id){

    $.ajax({
        url: Sh_Ajax_Requests_File() + '?f=userproductpage&s=showshortdata&prod_id='+prod_id,
        beforeSend: function() {
            run_waitMe($('#loader'), 1, 'win8');
        },
        success: function(data) {
            $('#loader').waitMe('hide');
            $("#productTitle").text(data[0].product_name);
            $("#product_id_data").val(data[0].product_id)
            $("#category").text(data[0].category_section.category);
            $("#category").attr('href', data[0].category_section.cat_slug);


            priceAndPromoPrice(data[0].pricing_and_promotion.price,data[0].pricing_and_promotion.promo_price)


            $("#ratingsview").html(data[0].ratings);
            $("#reviewcount").text(data[0].ratingscount);

            $("#imgFeat").attr('src', data[0].product_images.images.featured);

            let pontSpecs = '';
            if (data[0].point_specs.point_specs.length > 0){
                for (let u = 0; u < data[0].point_specs.point_specs.length; u++){
                    pontSpecs +=  `<li> ${data[0].point_specs.point_specs[u]['name']} </li>`;
                }
            }

            $("#pDescription").html(pontSpecs);
            $("#view-product").attr('href', data[0].product_slug);

            $("#product-quickview").modal();
        }

    });
}


// Add to cart
function addProductToCart(e){

    let productId = $("#product_id_data").val();

    $.ajax({
        url: Sh_Ajax_Requests_File() + '?f=userproductpage&s=addproducttocart&prod_id='+productId,
        beforeSend: function() {
            run_waitMe($('#loader'), 1, 'win8');
        },
        success: function(data) {

            $("#product-quickview").modal('hide');

            if (data.type == 1){
                sureNotify(1,data.message);

                // desktop add to cart
                $("#cart-product").append(data.cartHtml);
                $("#cart-count").text(data.cart_no);
                $("#cart-total-price").text('₦'+numberWithCommas(data.total_price));
                $("#show-hide-cart").removeClass('hide-cart').addClass('show-cart');

                // mobile add to cart
                $("#mobile-cart-product").append(data.cartHtml);
                $("#mobile-cart-count").text(data.cart_no);
                $("#mobile-cart-total-price").text('₦'+numberWithCommas(data.total_price));
                $("#mobile-show-hide-cart").removeClass('hide-cart').addClass('show-cart');

                // Update cart inp
                readyOrderData();

            }else{
                sureNotify(0,data.message);
            }

            $('#loader').waitMe('hide');

        }

    });
}


// Remove from cart
function removeProductFromCart(prod_id){

    let productId = prod_id

    $.ajax({
        url: Sh_Ajax_Requests_File() + '?f=userproductpage&s=removeproductfromcart&prod_id='+productId,
        beforeSend: function() {
            run_waitMe($('#loader'), 1, 'win8');
        },
        success: function(data) {

            // desktop remove from cart data
            $("#cart-count").text(data.cart_no);
            $("#cart-total-price").text('₦'+numberWithCommas(data.total_price));

            // mobile remove from cart data
            $("#mobile-cart-count").text(data.cart_no);
            $("#mobile-cart-total-price").text('₦'+numberWithCommas(data.total_price));

            if (data.cart_no > 0){
                $("#show-hide-cart").removeClass('hide-cart').addClass('show-cart');
                $("#mobile-show-hide-cart").removeClass('hide-cart').addClass('show-cart');
            }else{
                $("#show-hide-cart").removeClass('show-cart').addClass('hide-cart');
                $("#mobile-show-hide-cart").removeClass('show-cart').addClass('hide-cart');
            }

            $("#cart_product_"+prod_id).remove();
            $("#mobile-cart_product_"+prod_id).remove();

            // Update cart inp
            readyOrderData();

            $('#loader').waitMe('hide');
            sureNotify(1,data.message);
        }

    });
}


$(document).ready(function() {
    /**
     *
     * CART QUANTITY INCREMENT AND DECREMENT
     *
     */
    $('.action').on('click', '#remove-cart', function (e) {
        let pid = $(this).data('cartid');

        $.ajax({
            url: Sh_Ajax_Requests_File() + '?f=userproductpage&s=removeproductfromcart&prod_id='+pid,
            beforeSend: function() {
                run_waitMe($('#loader'), 1, 'win8');
            },
            success: function(data) {

                // remove cart row
                $("#show_cart_"+pid).remove();

                // update price data
                PriceChanger(0, pid);

                // desktop
                $("#cart-count").text(data.cart_no);
                $("#cart-total-price").text('₦'+numberWithCommas(data.total_price));

                // mobile
                $("#mobile-cart-count").text(data.cart_no);
                $("#mobile-cart-total-price").text('₦'+numberWithCommas(data.total_price));

                if (data.cart_no > 0){
                    $("#show-hide-cart").removeClass('hide-cart').addClass('show-cart');
                    $("#mobile-show-hide-cart").removeClass('hide-cart').addClass('show-cart');
                }else{
                    $("#show-hide-cart").removeClass('show-cart').addClass('hide-cart');
                    $("#mobile-show-hide-cart").removeClass('show-cart').addClass('hide-cart');
                }

                $("#cart_product_"+pid).remove();
                $("#mobile-cart_product_"+pid).remove();

                $('#loader').waitMe('hide');
                sureNotify(1,data.message);
            }

        });

    });

    $('.qty').on('click', '#add-btn', function (e) {

        let inc = 0;
        let prodId = $(this).data('prodid');
        let QtyTag = $(this).next().next();
        let initQty = parseInt(QtyTag.val());
        inc = initQty + 1;
        QtyTag.val(inc);

        IncreDecreCart(prodId, inc, '+');

    });

    $('.qty').on('click', '#remove-btn', function (e) {

        let inc = 0;
        let prodId = $(this).data('prodid');
        let QtyTag = $(this).next();
        let initQty = parseInt(QtyTag.val());

        if (initQty <= 1) {
            sureNotify(0, "Cant go beyond 1");
            return false
        }

        inc = initQty - 1;
        QtyTag.val(inc);

        IncreDecreCart(prodId, inc, '-');

    });


    let IncreDecreCart = (prod_id, qty, type) => {

        if (!typeof (prod_id) == 'number') {
            sureNotify(0, 'Product Not Found');
            return false;
        }

        $.ajax({
            type: "POST",
            url: Sh_Ajax_Requests_File() + '?f=userproductpage&s=quantyincredecre',
            data: {
                prod_id: prod_id,
                qty: qty,
                type: type
            },
            beforeSend: function () {
                run_waitMe($('#loader'), 1, 'win8');
            },
            success: function (dt) {
                $('#loader').waitMe('hide');
                if (dt.status == 200) {
                    PriceChanger(qty, prod_id);
                } else {
                    sureNotify(0, dt.message);
                }
            }
        });

    }

    let PriceChanger = (qty, prod_id) => {
        let Sprice, Nprice, Tprice, STprice;

        Sprice = $("#hidden_single_price_" + prod_id).data('price');
        Nprice = Sprice * qty

        $("#single-total_" + prod_id).text('₦' + numberWithCommas(Nprice));
        $("#hidden_single_price_" + prod_id).attr('data-qty', qty);
        $("#hidden_single_price_" + prod_id).attr('data-stprice', Nprice);

        // sum total init price
        Tprice = 0;

        // Sub total price without tax (sum of all product prices * their quantity)
        $(".single_price").each((i,e) => {
            Tprice += parseInt($(this).find(".cart_"+i).attr('data-stprice'));
            // console.log($(this).find(".cart_"+i).attr('data-stprice'));
        })

        // release Sub total price without tax (sum of all product prices * their quantity)
        $("#subTotal").text('₦'+numberWithCommas(Tprice));

        // Grand Total price of all product
        grandTotalCal();
    }


    function grandTotalCal(){

        //sub total price
        let o_p = $("#subTotal").text();
        o_p = o_p.replace(/\D/g,'');

        // logistic price
        let log_fee = $("#shipping-fee").text();
        log_fee = log_fee.replace(/\D/g,'');

        // sum of logistic price and sub total
        let mewprice = parseInt(o_p) + parseInt(log_fee);

        $("#all-total").text("₦"+numberWithCommas(mewprice));

        // price to send to server
        $("#totalPrice").val(mewprice);

    }


    // Make review on a product
    $('#review_form').parsley();
    $("#review_form").ajaxForm({
        url: Sh_Ajax_Requests_File() + '?f=rating&s=make_rating',
        beforeSend: function() {
            $('#review_form').parsley().validate();
            $("#review_form").find('button').attr("disabled", true);
            run_waitMe($('#loader'), 1, 'win8');
        },
        success: function(data) {

            $('#loader').waitMe('hide');

            if (data.status == 200) {

                // setTimeout(function () {
                //     window.location.href = d;
                // }, 1000);

                sureNotify(1, data.message);

            } else {

                sureNotify(0, data.message);
                $("#review_form").find('button').attr("disabled", false);

            }
        }
    });


    // Navigation search
    $(".searchProductNavOne").keyup(function() {
        // e.preventDefault();
        let searchVal = $.trim(this.value);

        $.ajax({
            type: 'POST',
            url: Sh_Ajax_Requests_File() + '?f=products&s=navigation_search',
            data: {keywords: searchVal},
            cache: false,
            success: function(html) {
                $("#nav_search_products").html(html)
            }

        });

    })


    $(".searchProductNavTwo").keyup(function() {
        $(".ps-panel--search-result-mobile").addClass('active');
        let searchVal = $.trim(this.value);

        $.ajax({
            type: 'POST',
            url: Sh_Ajax_Requests_File() + '?f=products&s=navigation_search',
            data: {keywords: searchVal},
            cache: false,
            success: function(html) {
                $("#nav_search_productsTwo").html(html)
            }

        });

    })


    $(".searchProductNavThree").keyup(function() {
        $(".ps-panel--search-result-mobile").addClass('active');
        let searchVal = $.trim(this.value);

        $.ajax({
            type: 'POST',
            url: Sh_Ajax_Requests_File() + '?f=products&s=navigation_search',
            data: {keywords: searchVal},
            cache: false,
            success: function(html) {
                $("#nav_search_productsThree").html(html)
            }

        });

    })



});

$(document).mouseup(function(e) {
    let searchCont = $(".ps-panel--search-result-mobile");

    // if the target of the click isn't the container nor a descendant of the container
    if (!searchCont.is(e.target) && searchCont.has(e.target).length === 0) {
        searchCont.removeClass('active');
    }

});

let numberWithCommas = (x) => {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Get orderCart data
let readyOrderData = () => {
    $.ajax({
        type: 'POST',
        url: Sh_Ajax_Requests_File() + '?f=userproductpage&s=getallcartdata',
        dataType: 'json',
        cache: false,
        success: function(data) {
            $("#order_data").val(JSON.stringify(data))
        }

    });

}








