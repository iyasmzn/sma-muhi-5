<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigationGroups([
                'Konten',
                'PPDB / SPMB',
                'Alumni',
                'Master Data',
                'Pengaturan',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString(<<<'HTML'
                    <style>
                        .fi-ta-content-grid .fi-ta-record{overflow:hidden}

                        /* Roomier writing surface for the content rich editors. */
                        .fi-fo-rich-editor .fi-fo-rich-editor-content{min-height:24rem}

                        /* Fullscreen mode toggled by the editor's fullscreen tool. */
                        .fi-fo-rich-editor.fi-fo-rich-editor-fullscreen{position:fixed;inset:0;z-index:50;margin:0;border-radius:0;padding:1rem;background-color:#f9fafb}
                        .dark .fi-fo-rich-editor.fi-fo-rich-editor-fullscreen{background-color:#09090b}
                        .fi-fo-rich-editor.fi-fo-rich-editor-fullscreen>div{display:flex;flex-direction:column;height:100%}
                        .fi-fo-rich-editor.fi-fo-rich-editor-fullscreen .fi-fo-rich-editor-main{flex:1 1 auto;overflow-y:auto}
                        .fi-fo-rich-editor.fi-fo-rich-editor-fullscreen .fi-fo-rich-editor-content{min-height:100%}
                        body.fi-rich-editor-fullscreen-active{overflow:hidden}

                        /* ── Riwayat / preview import alumni ───────────────────────── */
                        .aih-callout{display:flex;gap:.75rem;border-radius:.75rem;padding:1rem;font-size:.875rem;line-height:1.4;background:#fffbeb;color:#92400e;border:1px solid rgba(217,119,6,.2)}
                        .aih-callout svg{flex:none;width:1.25rem;height:1.25rem;margin-top:.125rem;color:#f59e0b}
                        .aih-callout strong{font-weight:600}
                        .aih-callout p+p{margin-top:.25rem}
                        .dark .aih-callout{background:rgba(251,191,36,.1);color:#fde68a;border-color:rgba(251,191,36,.25)}

                        .aih-badges{display:flex;flex-wrap:wrap;align-items:center;gap:.5rem;margin-bottom:1.25rem}
                        .aih-section{margin-top:1.5rem}
                        .aih-subhead{display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem}
                        .aih-subhead svg{width:1.25rem;height:1.25rem}
                        .aih-subhead h3{font-size:.875rem;font-weight:600;color:#030712}
                        .dark .aih-subhead h3{color:#fff}
                        .aih-ico-ok{display:inline-flex;color:#22c55e}
                        .aih-ico-bad{display:inline-flex;color:#ef4444}
                        .aih-count{border-radius:.375rem;background:#f3f4f6;padding:.1rem .4rem;font-size:.75rem;font-weight:500;color:#4b5563}
                        .dark .aih-count{background:rgba(255,255,255,.1);color:#d1d5db}
                        .aih-count--danger{background:#fee2e2;color:#dc2626}
                        .dark .aih-count--danger{background:rgba(248,113,113,.12);color:#f87171}

                        .aih-table-wrap{overflow:hidden;border-radius:.75rem;border:1px solid rgba(3,7,18,.07)}
                        .dark .aih-table-wrap{border-color:rgba(255,255,255,.1)}
                        .aih-table-wrap--danger{border-color:rgba(220,38,38,.18)}
                        .dark .aih-table-wrap--danger{border-color:rgba(248,113,113,.22)}
                        .aih-table-scroll{overflow-x:auto}
                        .aih-table{width:100%;border-collapse:collapse;font-size:.875rem;text-align:left}
                        .aih-table thead{background:#f9fafb}
                        .dark .aih-table thead{background:rgba(255,255,255,.05)}
                        .aih-table th{padding:.625rem 1rem;font-size:.6875rem;font-weight:500;text-transform:uppercase;letter-spacing:.05em;color:#6b7280;white-space:nowrap}
                        .dark .aih-table th{color:#9ca3af}
                        .aih-table td{padding:.625rem 1rem;border-top:1px solid #f3f4f6;color:#374151;vertical-align:middle}
                        .dark .aih-table td{border-top-color:rgba(255,255,255,.06);color:#d4d4d8}
                        .aih-table tbody tr:hover{background:#f9fafb}
                        .dark .aih-table tbody tr:hover{background:rgba(255,255,255,.04)}
                        .aih-name{font-weight:500;color:#030712}
                        .dark .aih-name{color:#fff}
                        .aih-num{text-align:right;font-variant-numeric:tabular-nums;font-size:.75rem;color:#9ca3af;width:3rem}
                        .aih-center{text-align:center;font-variant-numeric:tabular-nums}
                        .aih-right{text-align:right}
                        .aih-mono{font-family:ui-monospace,SFMono-Regular,Menlo,monospace;font-size:.75rem;color:#4b5563}
                        .dark .aih-mono{color:#9ca3af}
                        .aih-muted{color:#9ca3af}

                        .aih-table--danger thead{background:#fef2f2}
                        .dark .aih-table--danger thead{background:rgba(248,113,113,.1)}
                        .aih-table--danger th{color:#dc2626}
                        .dark .aih-table--danger th{color:#fca5a5}
                        .aih-table--danger td{border-top-color:#fee2e2}
                        .dark .aih-table--danger td{border-top-color:rgba(248,113,113,.12)}
                        .aih-table--danger tbody tr:hover{background:rgba(254,242,242,.6)}
                        .dark .aih-table--danger tbody tr:hover{background:rgba(248,113,113,.06)}
                        .aih-reason{color:#b91c1c}
                        .dark .aih-reason{color:#fca5a5}

                        .aih-empty{border-radius:.75rem;padding:1.5rem 1rem;text-align:center;font-size:.875rem;color:#6b7280;background:#f9fafb;border:1px solid rgba(3,7,18,.07)}
                        .dark .aih-empty{background:rgba(255,255,255,.05);color:#9ca3af;border-color:rgba(255,255,255,.1)}
                        .aih-empty--ok{background:#f0fdf4;color:#15803d;border-color:rgba(22,163,74,.18)}
                        .dark .aih-empty--ok{background:rgba(74,222,128,.1);color:#4ade80;border-color:rgba(74,222,128,.22)}
                    </style>
                    <script>
                        document.addEventListener('keydown', (event) => {
                            if (event.key !== 'Escape') {
                                return
                            }

                            const fullscreenEditor = document.querySelector('.fi-fo-rich-editor-fullscreen')

                            if (! fullscreenEditor) {
                                return
                            }

                            fullscreenEditor.classList.remove('fi-fo-rich-editor-fullscreen')
                            document.body.classList.remove('fi-rich-editor-fullscreen-active')
                        })
                    </script>
                    HTML),
            )
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3,
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
            ]);
    }
}
