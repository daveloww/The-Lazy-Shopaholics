//Infinite Scrolling Object
document.addEventListener("DOMContentLoaded", () => {
    var trigger = document.getElementById("infinite_scroll_trigger");
    //Add intersection observer to product div, to observe the trigger
    let options = {
    root: null,
    rootMargins: "0px",
    threshold: 0.2
    };
    const observer = new IntersectionObserver((entries)=>{

        if (entries[0].isIntersecting){
            trigger.style.visibility = "visible";

            var category = document.getElementById("category").innerText;
            var keyword = document.getElementById("keyword").innerText;
            var sort_by = document.getElementById("sort_by").innerText;

            if (category == "true") {
                call_category_apis(keyword, sort_by);
            } else {
                call_search_apis(keyword, sort_by);
            }
        }
    }, options);
    observer.observe(trigger);
});


function call_search_apis(keyword, sort_by) {

    var api_key = ""; // enter api key

    var sort_type = sort_by;

    /* Counters for page number - product_div_(general_page_num)
    Counter is created based on product_div_id to search for a specific page number in the API 
    After calling the api, the counter would be updated later on in the code
    where the general page number would increase by 1 */

    var product_div = document.getElementsByName("product_div")[0];

    var div_id_str = product_div.getAttribute("id").split("_");

    var general_page_num = parseInt(div_id_str[2]); 


    Promise.all([
        // Aliexpress
        fetch(`https://magic-aliexpress1.p.rapidapi.com/api/products/search?name=${keyword}&sort=SALE_PRICE_ASC&lg=EN&page=${general_page_num}&targetCurrency=USD`, {
            "method": "GET",
            "headers": {
                "x-rapidapi-host": "magic-aliexpress1.p.rapidapi.com",
                "x-rapidapi-key": api_key
            }
        }),

        // Amazon
        fetch(`https://amazon-product-reviews-keywords.p.rapidapi.com/product/search?keyword=${keyword}&page=${general_page_num}&country=SG`, {
            "method": "GET",
            "headers": {
                "x-rapidapi-host": "amazon-product-reviews-keywords.p.rapidapi.com",
                "x-rapidapi-key": api_key
            }
        }),

        // Taobao
        fetch(`https://taobao-api.p.rapidapi.com/api?api=item_search&sort=priceasc&page=${general_page_num}&q=${keyword}`, {
            "method": "GET",
            "headers": {
                "x-rapidapi-host": "taobao-api.p.rapidapi.com",
                "x-rapidapi-key": api_key
            }
        }),

        fetch("https://rapidapi.p.rapidapi.com/exchange?from=CNY&to=SGD", {
            "method": "GET",
            "headers": {
                "x-rapidapi-host": "currency-exchange.p.rapidapi.com",
                "x-rapidapi-key": api_key
            }
        }),

        fetch("https://rapidapi.p.rapidapi.com/exchange?from=USD&to=SGD", {
            "method": "GET",
            "headers": {
                "x-rapidapi-host": "currency-exchange.p.rapidapi.com",
                "x-rapidapi-key": api_key
            }
        })
    ]).then(function (responses) {
        
        // Get a JSON object from each of the responses
        
        return Promise.all(responses.map(function (response) {
            return response.json();
        }));
    }).then(function (data) {
        // Log the data to the console
        console.log(data);
        
        // Retrieve 3 sets of products
        var ali_json = data[0].docs;
        var amazon_json = data[1].products;
        var tbao_json = data[2].result.item;

        // Conversion rates
        var CNY_to_SGD_rate = parseFloat(data[3]);
        var USD_to_SGD_rate = parseFloat(data[4]);

        // Use if currency api endpoint is wonky
        // var CNY_to_SGD_rate = 0.2;
        // var USD_to_SGD_rate = 1.34;


        // checks if user is logged in
        var loggedIn = document.getElementById("login").innerText  == "true" ? "true" : "";


        /* Collate all results, standardize format using format function and sort by sort_type */
        var collated_list = [];
        
        Promise.all([format_amazon(amazon_json), format_ali(ali_json, USD_to_SGD_rate), format_tbao(tbao_json, CNY_to_SGD_rate)])
        .then(response =>{
            collated_list = collated_list.concat(response[0], response[1], response[2])

            collated_list = sort_list_by(collated_list, sort_type);
            

            for (each of collated_list){          
                let p_id = each.product_id;
                let p_name = each.product_name;
                let photo = each.thumbnail;
                let url = each.url;
                let price = each.current_price;
                let ecommerce = each.product_origin;

                let href = `cart_process.php?pid=${p_id}&photo=${photo}&url=${url}&price=${price}&ecom=${ecommerce}&p_name=${p_name}`;

                let cardhtml = "";
                
                cardhtml += 
                `<div class="col mb-4">
                    <div class="card h-100" style="padding-top: 20px;">
                        <img src="${photo}" class="card-img-top" style="height: 223px; cursor: pointer;" alt="${p_name}" data-toggle="modal" data-target="#product_${p_id}" onclick="display_description('${p_id}', '${ecommerce}', '${url}'); setTimeout(() => { display_reviews('${p_id}', '${ecommerce}', '${url}'); }, 2000);">
                            
                        <div class="card-body">
                            <h6 class="card-title" style="cursor: pointer;" data-toggle="modal" data-target="#product_${p_id}" onclick="display_description('${p_id}', '${ecommerce}', '${url}'); setTimeout(() => { display_reviews('${p_id}', '${ecommerce}', '${url}'); }, 2000);">${p_name}</h6>
                            
                            <p class="card-text text-primary font-weight-bold float-left m-0" style="cursor: pointer;" data-toggle="modal" data-target="#product_${p_id}" onclick="display_description('${p_id}', '${ecommerce}', '${url}'); setTimeout(() => { display_reviews('${p_id}', '${ecommerce}', '${url}'); }, 2000);">S$${price}</p>
                            <p class="card-text text-secondary font-weight-bold float-right">`;
                
                            if (loggedIn) {
                                var fav_href = `favourites_process.php?type=add&pid=${p_id}&photo=${photo}&url=${url}&price=${price}&ecom=${ecommerce}&p_name=${p_name}`;
                                cardhtml += `<a href='${fav_href}'><i class='far fa-heart text-secondary iconHover'></i></a>`;
                            }

                            cardhtml +=
                                ` <a href='${href}'><i class='fas fa-shopping-cart text-secondary iconHover'></i></a>
                            </p>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="product_${p_id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">${p_name} <span class='badge badge-pill badge-warning'>${ecommerce}</span></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="container-fluid">

                                            <div class="row justify-content-center">
                                                <div class="col-auto">
                                                <img src="${photo}" style="height: 223px;" alt="${p_name}">
                                                </div>
                                            </div>

                                            <div class="row-cols-1">
                                                <div id="desc_${p_id}" class="col mb-2 mt-2 text-secondary">
                                                    Click <a href='${url}' target='_blank'>here</a> for product description
                                                </div>

                                                <div class="col">
                                                    <p class="card-text text-primary font-weight-bold float-left m-0">S$${price}</p>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="modal-footer">`;

                                    if (loggedIn) {
                                        cardhtml += `<a href='${fav_href}' class='btn text-white' style='background: lightpink'>Favourite <i class='far fa-heart'></i></a>`;
                                    }
                                    
                                    cardhtml += `
                                        <a href='${href}' class='btn btn-primary'>Add to cart <i class='fas fa-shopping-cart'></i></a>
                                        
                                    </div>

                                    <div class="modal-footer" style="padding: 0px;">
                                    </div>

                                    <div class="modal-header">
                                        <h5 class="modal-title"><i>Reviews</i></h5>
                                    </div>

                                    <div class="modal-body">

                                        <div id="review_${p_id}" class="container-fluid">
                                            
                                            <div class="row-cols-1 text-center">
                                                <p class="text-info"><b>Finding (<i>hopefully</i>) constructive reviews..</b></p>
                                                <div class="spinner-border" style="width: 4rem; height: 4rem; margin-top: 10px;" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-primary text-center">
                            <img src="images/${ecommerce.toLowerCase()}.png" width="40%">
                        </div>
                    </div>
                </div>`;
                    
                product_div.innerHTML += cardhtml;
            }
    

            // Update counter
            general_page_num = parseInt(div_id_str[2]) + 1;
            product_div.setAttribute("id",`${div_id_str[0]}_${div_id_str[1]}_${general_page_num.toString()}`);


            //After loading hide loading animation and infinite scroll trigger
            var trigger = document.getElementById("infinite_scroll_trigger");
            trigger.style.visibility = "hidden";

        })
        .catch(error => console.log(error))

    }).catch(function (error) {
        // if there's an error, log it
        console.log(error);

        document.getElementById("scroller").innerHTML = `<h4 style='margin-top: 1em;' class="text-info">Oh no! No products found <i class="far fa-sad-tear"></i></h4>`;
    });
}


async function format_amazon(product_json){

    var product_arr = [];

    if (product_json != undefined) {
        if (product_json.length != 0) {
            for (product of product_json) {

                //to filter the products from the amazon api that have a current price of 0
                if (product.price.current_price != "0" ){

                    let standard_product_format = {
                        "product_id":product.asin,
                        "product_name":product.title,
                        "before_price":parseFloat(product.price.before_price), //Original price
                        "current_price":parseFloat(product.price.current_price).toFixed(2), //Sale price displayed
                        "savings":parseFloat(product.price.before_price-product.price.current_price),
                        "score":parseFloat(product.score), //Score refers to (total_reviews*each_review_score)
                        "thumbnail":product.thumbnail,
                        "url":product.url,
                        "product_origin":"Amazon",
                
                    };
            
                    product_arr.push(standard_product_format);

                }

            }
            //sort before scoring the product's popularity
            product_arr.sort((a, b) => (b.score - a.score));
            product_arr = give_relative_popularity(product_arr);
        }
    }

    return product_arr; 
}


async function format_ali(product_json, conversion_rate){

    var product_arr = [];

    if (product_json != undefined) {
        if (product_json.length != 0) {
            for (product of product_json) {

                if (product.sale_price*conversion_rate > 0){

                    let standard_product_format = {
                        "product_id":product.product_id,
                        "product_name":product.product_title,
                        "before_price":(parseFloat(product.original_price)*conversion_rate).toFixed(2),
                        "current_price":(parseFloat(product.sale_price)*conversion_rate).toFixed(2), //Original price
                        "savings": (parseFloat(product.original_price)-parseFloat(product.sales_price)).toFixed(2),
                        "score":parseFloat(product.lastest_volume), //use most recent sales volume information from api
                        "thumbnail":product.product_main_image_url,
                        "url":product.product_detail_url,
                        "product_origin":"Aliexpress",
                    };

                    product_arr.push(standard_product_format);
                }

            }
            
            // don't need to sort before scoring, products are already sorted by api. Highest Sales = Index 0, Lowest Sales = Last Index
            product_arr = give_relative_popularity(product_arr); 
        }
    }

    return product_arr;
}



async function format_tbao(product_json, conversion_rate){
    
    var product_arr = [];
    
    if (product_json != undefined) {
        if (product_json.length != 0) {
            for (product of product_json) {

                let standard_product_format = {
                    "product_id":product.num_iid,
                    "product_name":product.title,
                    "before_price":(parseFloat(product.price)*conversion_rate).toFixed(2), //SGD conversion rate
                    "current_price":(parseFloat(product.promotion_price)*conversion_rate).toFixed(2), 
                    "savings":(parseFloat(product.price-product.promotion_price)*conversion_rate).toFixed(2),
                    "score":parseFloat(product.sales),
                    "thumbnail":product.pic,
                    "url":product.detail_url,
                    "product_origin":"Taobao",
                };

                product_arr.push(standard_product_format);    

            }

            product_arr.sort((a, b) => (b.score - a.score));
            product_arr = give_relative_popularity(product_arr);
        }
    }

    return product_arr;
}



function display_description(product_id, product_origin, url){
    var api_key = ""; // enter api key

    if (product_origin == "Aliexpress") {
        return
    }

    var product_desc = document.getElementById("desc_"+product_id);

    var link = {"Amazon":[`https://rapidapi.p.rapidapi.com/product/details?asin=${product_id}&country=SG`,"amazon-product-reviews-keywords.p.rapidapi.com"],
                "Taobao":[`https://rapidapi.p.rapidapi.com/api?api=item_detail&num_iid=${product_id}`,"taobao-advanced.p.rapidapi.com"]
            };//using taobao_advanced

    //Use api to retrieve the respective product description
    fetch(`${link[product_origin][0]}`, {
        "method": "GET",
        "headers": {
            "x-rapidapi-host": `${link[product_origin][1]}`,
            "x-rapidapi-key": api_key
        }
    }).then(response => {

        var product_json = response.json();
        return product_json
    })
    .then(product_json => {
        
        if (product_origin == "Amazon" && product_json.product.description){
            product_desc.innerHTML = `${product_json.product.description}<br> <a href='${url}' target='_blank'>Find out more ></a>`;
        }

        else if (product_origin == "Taobao" && product_json.result.item.properties_cut){
            product_desc.innerHTML = `${product_json.result.item.properties_cut}<br> <a href='${url}' target='_blank'>Find out more ></a>`;
        }
    })
    .catch(err => {
        console.error(err);
    });

}


function display_reviews(product_id, product_origin, url){

    var api_key = ""; // enter api key
    var product_review = document.getElementById("review_"+product_id);

    if (product_origin == "Aliexpress") {
        product_review.innerHTML = `<p class="text-secondary">Click <a href='${url}' target='_blank'>here</a> to check out reviews</p>`;
        return;
    }

    var link = {"Amazon":[`https://rapidapi.p.rapidapi.com/product/reviews?asin=${product_id}&variants=1`,"amazon-product-reviews-keywords.p.rapidapi.com"],
                "Taobao":[`https://rapidapi.p.rapidapi.com/api?num_iid=${product_id}&api=item_review&page=1`,"taobao-advanced.p.rapidapi.com"]
            };//using taobao_advanced

        
    //Use api to retrieve the respective product description
    fetch(`${link[product_origin][0]}`, {
        "method": "GET",
        "headers": {
            "x-rapidapi-host": `${link[product_origin][1]}`,
            "x-rapidapi-key": api_key
        }
    }).then(response => {
        return response.json();

    }) .then(review_json => {
        if (product_origin == "Amazon"){
            var reviews = review_json.reviews;

            if (!reviews) {
                product_review.innerHTML = `<p class="text-secondary"><b>No reviews for this product yet! <i class="far fa-frown-open"></i></b></p>`;
            
            } else {
                let count = 1;

                for (review of reviews){

                    var review_data = review.review_data;

                    var temp_arr = review_data.split(" ");
                    var front_part = temp_arr.slice(0, temp_arr.length-3).join(" ");
                    var date_part = temp_arr.slice(temp_arr.length-3, temp_arr.length).join(" ");

                    var star_count = 5;
                    var review_rating = review.rating;
                    var full_stars = Math.floor(review_rating);
                    var half_star = review_rating % 1;
                    var half_star_count = 0;

                    if (half_star != 0) {
                        half_star_count = 1;
                    }

                    var blank_stars = star_count - full_stars - half_star_count;

                    var stars_str = "<i class='fas fa-star text-warning'></i>".repeat(full_stars) + "<i class='fas fa-star-half-alt text-warning'></i>".repeat(half_star_count) + "<i class='far fa-star text-warning'></i>".repeat(blank_stars);

                    var review_html = 
                        `<div class="row-cols-1 border rounded mb-4 py-2" style="background: lightblue">
                            <div class="col">
                                <b>${review.name}</b> <br>
                                ${stars_str}
                            </div>

                            <div class="col">
                                <i>${front_part}</i> <span class="bg-secondary text-white border rounded pl-1 pr-1"><b>${date_part}</b></span> <br>
                                <hr>
                            </div>

                            <div class="col mb-1">
                                <b>${review.title}</b> <br>
                                ${review.review}
                            </div>
                        </div>`;

                    if (count == 1) {
                        product_review.innerHTML = review_html;
                    } else {
                        product_review.innerHTML += review_html;
                    }

                    count++;
                }
                    
            }
        }

        else if (product_origin == "Taobao"){
            var reviews = review_json.result.item
            
            if (reviews == undefined) {
                product_review.innerHTML = `<p class="text-secondary"><b>No reviews for this product yet! <i class="far fa-frown-open"></i></b></p>`;
            
            } else {
                let count = 1;

                for (review of reviews){

                    var review_html = 
                        `<div class="row-cols-1 border rounded mb-4 py-2" style="background: lightblue">
                            <div class="col">
                                Reviewer: <b>${review.user_nick}</b>
                            </div>

                            <div class="col">
                                <span class="bg-secondary text-white border rounded pl-1 pr-1">Date of review: <b>${review.feedback_date}</b></span> <br>
                                <hr>
                            </div>

                            <div class="col mb-1">
                                ${review.feedback}
                            </div>
                        </div>`;
                    
                    if (count == 1) {
                        product_review.innerHTML = review_html;
                    } else {
                        product_review.innerHTML += review_html;
                    }

                    count++;
                }
            }
        }
    })
    .catch(err => {
        console.error(err);
    });

}


function give_relative_popularity(product_arr){
    /*Relative popularity would be used for displaying the most popular products later on.
    Relative popularity is based on the "local" product score (e.g. Amazon Product A vs Amazon Product B)
    as different sites have different levels of volume and information available

    Score products by their reviews or sales numbers in the relevant websites
    Median as a reference for scoring as some of the best sellers have huge sales numbers / are outliers */

    var median = Math.round(product_arr.length/2);
    var median_score = parseFloat(product_arr[median].score);

    for (var i=0; i < product_arr.length; i++){
        product_arr[i].relative_popularity = parseFloat(product_arr[i].score/median_score).toFixed(2);

        }

    return product_arr;
        
    
}

function sort_list_by(collated_list,sort_type){

    /*After the product arrays have been formatted, sorted and then scored,
    do a final sort that ranks products from all 3 e-commerce sites*/

    if (sort_type != "relative_popularity"){
        collated_list.sort((a, b) => parseFloat(a[sort_type]) - parseFloat(b[sort_type]));
    }
    else{
        collated_list.sort((a, b) => parseFloat(b[sort_type]) - parseFloat(a[sort_type]));
    }
    return collated_list
    
}