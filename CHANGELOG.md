# v5.0.0

- Requires `filament/filament` ^5.0 (Livewire 4)
- Supports Laravel 12 and 13, PHP 8.2+ (#4, thanks to the community PR #6)
- Activity resource ported to the Filament v5 schema / actions APIs
- Eager load the activity causer on the table (#1)
- "Clear Activities" deletes rows instead of `TRUNCATE`, which failed inside a MySQL transaction (#3)
- The request listener is subscribed directly; `TomatoPHP\FilamentLogger\EventServiceProvider` is removed because extending the framework provider registered the email verification listener twice (#5). Remove any workaround you added for it.
- Livewire 4 update requests are recognised again, so `request.livewire => false` keeps them out of the log
- `FilamentLogger::log()` works on servers without `REDIRECT_STATUS` (nginx, `artisan serve`)
- `filament-logger:install` runs the migrations in-process
- Translations publish to `lang_path('vendor/filament-logger')`
- Added a Pest test suite and CI for PHP 8.3 / 8.4 on Laravel 12 / 13
- The Filament v3 line continues on the `v3` branch
