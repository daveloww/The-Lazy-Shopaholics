function call_category_apis(keyword, sort_by) {
    var api_key = ""; // enter api key

    var sort_type = sort_by;

    var selected_category = keyword;

    // dictionary to access the selected categories for different api endpoints and parameters
    // category id for amazon, aliexpress and taobao with respective indexes of 0, 1 and 2 (with 2 being taobao) 
    var category = {"Electronics":["electronics","44","504000"],
                    "Computers":["computers","7",""], 
                    "Mobile phones":["mobile","5090301",""], 
                    "Sports":["sporting","301","506000"],
                    "Men's Fashion":["fashion-mens","200000343","496000"],
                    "Women's Fashion":["fashion-womens","200000345","495000"],
                    "Toys":["toys-and-games","26",""], 
                    "Health":["hpc","200001355", ""]
                };

    /* Counters for page number - product_div_(general_page_num)
    Counter is created based on product_div_id to search for a specific page number in the API 
    After calling the api, the counter would be updated later on in the code
    where the general page number would increase by 1 */
    
    var product_div = document.getElementsByName("product_div")[0];

    var div_id_str = product_div.getAttribute("id").split("_");

    var general_page_num = parseInt(div_id_str[2]);


    // Making sure to call the correct Taobao API
    if (category[selected_category][2] == ""){
        // Normal Taobao
        tbao_endpoint = `https://taobao-api.p.rapidapi.com/api?api=item_search&sort=default&page=${general_page_num}&q=${selected_category}`;
        tbao_api_host = `taobao-api.p.rapidapi.com`;
    } else {
        // Tabao advanced
        tbao_endpoint = `https://rapidapi.p.rapidapi.com/api?api=item_search_tejia&page=${general_page_num}&cat=${category[selected_category][2]}&priceScope=1-9999`;
        tbao_api_host = "taobao-advanced.p.rapidapi.com";
    }


    Promise.all([
        //Aliexpress - Retrieve general categories with the most sales, where most sales starts from index 0
        fetch(`https://rapidapi.p.rapidapi.com/api/bestSales/products?page=${general_page_num}&categoryID=${category[selected_category][1]}`, {
            "method": "GET",
            "headers": {
                "x-rapidapi-host": "magic-aliexpress1.p.rapidapi.com",
                "x-rapidapi-key": api_key
            }
        }),

        // Amazon
        fetch(`https://amazon-product-reviews-keywords.p.rapidapi.com/product/search?&page=${general_page_num}category=${category[selected_category][0]}&country=SG&keyword=${selected_category}`, {
            "method": "GET",
            "headers": {
                "x-rapidapi-host": "amazon-product-reviews-keywords.p.rapidapi.com",
                "x-rapidapi-key": api_key
            }
        }),

        // Taobao
        fetch(tbao_endpoint, {
            "method": "GET",
            "headers": {
                "x-rapidapi-host": tbao_api_host,
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


        // Collate all results, standardize format using format function and sort by sort_type 
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


            // After loading hide loading animation and infinite scroll trigger
            var trigger = document.getElementById("infinite_scroll_trigger");
            trigger.style.visibility = "hidden";

            })
            .catch(error => console.log(error))

    }).catch(function (error) {
        // if there's an error, log it
        console.log(error);
    });
}