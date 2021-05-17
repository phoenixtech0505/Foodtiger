<footer class="footer">
    <div class="container">
      <div class="row align-items-center justify-content-md-between">
        <div class="col-md-6">
          <div class="copyright">
            &copy; 2020 <a href="" target="_blank">{{ config('global.site_name', 'mResto') }}</a>.
          </div>
        </div>
        <div class="col-md-6">
          <ul id="footer-pages" class="nav nav-footer justify-content-end">
            <li v-for="page in pages" class="nav-item" v-cloak>
                <a :href="'/pages/' + page.id" class="nav-link">@{{ page.title }}</a>
            </li>

            @if (!config('settings.single_mode')&&config('settings.restaurant_link_register_position')=="footer")
            <li class="nav-item">
              <a  target="_blank" class="button nav-link nav-link-icon" href="{{ route('newrestaurant.register') }}">{{ __(config('settings.restaurant_link_register_title')) }}</a>
            </li>
          @endif
          @if (config('app.isft')&&config('settings.driver_link_register_position')=="footer")
          <li class="nav-item">
              <a target="_blank" class="button nav-link nav-link-icon" href="{{ route('driver.register') }}">{{ __(config('settings.driver_link_register_title')) }}</a>
            </li>
            @endif

          </ul>
        </div>
      </div>
    </div>
  </footer>
