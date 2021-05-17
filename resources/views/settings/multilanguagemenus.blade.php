<div class="row">
    <div class="col-xl-12 order-xl-1">
        <div class="card bg-secondary shadow">
            <div class="card-header bg-white border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">{{ __('Migrate to multilanguge menu') }}</h3>
                    </div>
                    <div class="col-4 text-right">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php 
                    $currentEnvLanguage= isset(config('config.env')[2]['fields'][0]['data'][config('settings.app_locale')]) ? config('config.env')[2]['fields'][0]['data'][config('settings.app_locale')]:"UNKNOWN"; ?>
                    <p>You have enabled Multilanguage menus, but we haven't yet done a migaration to add the default language menu in <strong>{{ $currentEnvLanguage  }}</strong>. 
                    <br />At the moment, your default locale / language is <strong>{{ $currentEnvLanguage  }}</strong>!
                    <br />If <strong>{{ $currentEnvLanguage  }}</strong> is not your desired language, you can change it in the "Localization" tab. And then run this action
                    <br />This action is unreversable. 
                </p>
                <div class="text-center">
                    <a  href="{{ route('translatemenu') }}" class="btn btn-danger mt-4">{{ __("Make all restaurant's menus in ".$currentEnvLanguage) }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
<br />