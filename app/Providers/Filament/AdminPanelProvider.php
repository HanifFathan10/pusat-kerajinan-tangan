<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationGroup;
use Shanerbaner82\PanelRoles\PanelRoles;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()

            ->brandName('Pusat Kerajinan Tangan')
            ->brandLogoHeight('3rem')
            ->brandLogo(asset('image/logo-pkt.png'))
            ->favicon(asset('favicon.ico'))

            ->colors([
                'primary' => Color::Emerald,
                'gray'    => Color::Slate,
                'info'    => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Orange,
                'danger'  => Color::Rose,
            ])
            ->font('Plus Jakarta Sans')
            ->darkMode(true)

            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('full')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])

            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Penjualan dan Kasir')
                    ->icon('heroicon-o-shopping-bag'),
                NavigationGroup::make()
                    ->label('Manajemen Produksi')
                    ->icon('heroicon-o-rectangle-stack'),
                NavigationGroup::make()
                    ->label('Inventaris Gudang')
                    ->icon('heroicon-o-home-modern'),
                NavigationGroup::make()
                    ->label('Evaluasi & Keuangan')
                    ->icon('heroicon-o-chart-bar'),
                NavigationGroup::make()
                    ->label('Master Data')
                    ->icon('heroicon-o-database'),
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])

            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])

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
            ])
            ->plugin(
                PanelRoles::make()
                    ->roleToAssign('Pekerja')
                    ->restrictedRoles([
                        'Administrator',
                        'Pusat Pengelola',
                        'Tim Keuangan',
                        'Pekerja'
                    ]),
            )
            ->databaseNotifications();
    }
}
