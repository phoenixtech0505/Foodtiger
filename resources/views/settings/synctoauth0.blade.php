<div class="row">
    <div class="col-xl-12 order-xl-1">
        <div class="card bg-secondary shadow">
            <div class="card-header bg-white border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">{{ __('AuthO is the new Authentication Mechanizm') }}</h3>
                    </div>
                    <div class="col-4 text-right">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php $currentEnvLanguage= isset(config('config.env')[2]['fields'][0]['data'][config('settings.app_locale')]) ? config('config.env')[2]['fields'][0]['data'][config('settings.app_locale')]:"UNKNOWN"; ?>
                @if(strlen(config('settings.auth0_token'))>5&&strlen(config('settings.auth_connection'))>4)
                    <p>In v2 we have migrated to Auth0. So, we need to sync users and their passwords to Auth0 Database.
                        <br />Make sure you have entered correct AUTH0_TOKEN and AUTH_CONNECTION as explained in the changelog docs.
                        <br />We will sync users that used email login/register. This existing users need to do password reset.
                        <br />After executing this action we will show log of the results of the sync.  
                    </p>
                    <div class="text-center">
                        <a  href="{{ route('admin.syncV1UsersToAuth0') }}" class="btn btn-danger mt-4">{{ __("Sync Existing Users to AuthO (So they need to do Password Reset)") }}</a>
                    </div>
                @else
                    <p>In v2 we have migrated to Auth0. So, we need to sync users and their passwords to Auth0 Database.
                        <br />But you are missing  AUTH0_TOKEN and AUTH_CONNECTION as explained in the changelog docs, so we can proceed with the sync process.
                        <br />You should puth this values in the "Plugins" tab.
                    </p>
                @endif 
                <div class="text-center">
                    <a  href="{{ route('admin.dontsyncV1UsersToAuth0') }}" class="btn btn-white mt-4">{{ __("Do not Sync Existing Users to AuthO (So they need to do Sign Up)") }}</a>
                </div>

                
            </div>
        </div>
    </div>
</div>
<br />