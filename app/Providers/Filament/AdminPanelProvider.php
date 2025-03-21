<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationBuilder;
use function app_path;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\TourLeaderResource;
use App\Filament\Resources\PilgrimResource;
use App\Filament\Resources\GroupResource;
use App\Filament\Resources\LuggageResource;
use App\Filament\Resources\NotificationResource;
use App\Filament\Resources\QuestionnaireResource;
use App\Filament\Resources\ContentResource;
use App\Filament\Resources\CarouselResource;
use App\Filament\Pages\LiveTracking;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
        ->font('Poppins')

        ->brandLogo(asset('images/LOGO2.png'))
        ->darkModeBrandLogo(asset('images/LOGO1.png'))
        ->brandLogoHeight('2rem')
        ->default()
        ->id('admin')
        ->path('admin')
        ->login()
        ->brandLogo(fn () => view('filament.admin.logo'))
        ->darkModeBrandLogo(fn () => view('filament.admin.logo-dark'))

        ->colors([
            'primary' => Color::Rose,
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,

            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make('')
                        ->items([
                            NavigationItem::make('Dashboard')
                                ->icon('heroicon-o-home')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                                ->url(fn (): string => Dashboard::getUrl()),
                        ]),
                    NavigationGroup::make('Tour Management')
                        ->items([
                            ...TourLeaderResource::getNavigationItems(),
                            ...PilgrimResource::getNavigationItems(),
                            ...GroupResource::getNavigationItems(),
                            ...LuggageResource::getNavigationItems(),
                            NavigationItem::make('Live Tracking')

                                ->url(LiveTracking::getUrl())
                        ])
                        ->icon('heroicon-o-users'),
                    NavigationGroup::make('Communication')
                        ->items([
                            ...NotificationResource::getNavigationItems(),
                            ...QuestionnaireResource::getNavigationItems(),
                        ])
                        ->icon('heroicon-o-chat-bubble-left-right'),
                    NavigationGroup::make('Content Management')
                        ->items([
                            ...ContentResource::getNavigationItems(),
                            ...CarouselResource::getNavigationItems(),
                        ])
                        ->icon('heroicon-o-photo'),
                ]);
            })
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);


    }
}
