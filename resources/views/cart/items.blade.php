<div class="card card-profile shadow mt--300">
    <div class="px-4">
      <div class="mt-5">
        <h3>{{ __('Items') }}<span class="font-weight-light"></span></h3>
      </div>
        <!-- List of items -->
        <div  id="cartList" class="border-top">
            <br />
            <div  v-for="item in items" class="items col-xs-12 col-sm-12 col-md-12 col-lg-12 clearfix">
                <div class="info-block block-info clearfix" v-cloak>
                    <div class="square-box pull-left">
                    <figure>
                        <img :src="item.attributes.image" :data-src="item.attributes.image"  class="productImage" width="100" height="105" alt="">
                    </figure>
                    </div>
                    <h6 class="product-item_title">@{{ item.name }}</h6>
                    <p class="product-item_quantity">@{{ item.quantity }} x @{{ item.attributes.friendly_price }}</p>
                    <ul class="pagination">
                        <li class="page-item">
                            <button v-on:click="decQuantity(item.id)" :value="item.id" class="page-link" tabindex="-1">
                                <i class="fa fa-minus"></i>
                            </button>
                        </li>
                        <li class="page-item">
                            <button v-on:click="incQuantity(item.id)" :value="item.id" class="page-link" >
                                <i class="fa fa-plus"></i>
                            </button>
                        </li>
                        <li class="page-item">
                            <button v-on:click="remove(item.id)"  :value="item.id" class="page-link" >
                                <i class="fa fa-trash"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End List of items -->
    </div>
</div>
<br />
