<div class="card card-profile shadow">
    <div class="card-header">
        <h5 class="h3 mb-0">{{ __("Order Review")}}</h5>
    </div>
<div class="card-body">
    <form role="form" method="post" action="{{ route('rate.order', isset($order)?$order:"") }}">
        @csrf
        <input type="hidden" id="order_id" value="{{ $order->id }}"/>
        <input type="hidden" id="rating_value" name="ratingValue">
        <section class='rating-widget'>
            <!-- Rating Stars Box -->
            <div class='rating-stars text-center'>
                <ul id='stars'>
                    <li class='star' title={{ __("Poor") }} data-value='1'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                    <li class='star' title={{ __("Fair") }} data-value='2'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                    <li class='star' title={{ __("Good") }} data-value='3'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                    <li class='star' title={{ __("Excellent") }} data-value='4'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                    <li class='star' title={{ __("WOW") }} data-value='5'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                </ul>
            </div>
            <div class='success-box' id="success-box-ratings">
                <div class='clearfix'></div>
                    <img alt='tick image' width='32' src='data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0MjYuNjY3IDQyNi42NjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQyNi42NjcgNDI2LjY2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxwYXRoIHN0eWxlPSJmaWxsOiM2QUMyNTk7IiBkPSJNMjEzLjMzMywwQzk1LjUxOCwwLDAsOTUuNTE0LDAsMjEzLjMzM3M5NS41MTgsMjEzLjMzMywyMTMuMzMzLDIxMy4zMzMgIGMxMTcuODI4LDAsMjEzLjMzMy05NS41MTQsMjEzLjMzMy0yMTMuMzMzUzMzMS4xNTcsMCwyMTMuMzMzLDB6IE0xNzQuMTk5LDMyMi45MThsLTkzLjkzNS05My45MzFsMzEuMzA5LTMxLjMwOWw2Mi42MjYsNjIuNjIyICBsMTQwLjg5NC0xNDAuODk4bDMxLjMwOSwzMS4zMDlMMTc0LjE5OSwzMjIuOTE4eiIvPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K'/>
                    <div class='text-message'></div>
                <div class='clearfix'></div>
            </div>
            <div id="order_comment" class="form-group{{ $errors->has('comment') ? ' has-danger' : '' }}">
                <label class="form-control-label" for="name">{{ __('Order comment') }}</label>
                <textarea name="comment" id="comment" class="form-control{{ $errors->has('comment') ? ' is-invalid' : '' }}" placeholder="{{ __( 'Order comment here' ) }} ..." required></textarea>
                @if ($errors->has('comment'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('comment') }}</strong>
                    </span>
                @endif
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary my-4" id="save-ratings">{{ __('Save') }}</button>
            </div>
        </section>
    </form>
    </div>
</div>
