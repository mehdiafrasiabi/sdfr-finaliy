<div class="an-page" dir="rtl" wire:poll.10s>
    @push('link')
        <style>
            .an-page{
                --an-surface:var(--bs-body-bg);
                --an-soft:var(--bs-tertiary-bg);
                --an-border:var(--bs-border-color);
                --an-text:var(--bs-body-color);
                --an-muted:var(--bs-secondary-color);
                --an-shadow:0 14px 38px rgba(15,23,42,.08);
                color:var(--an-text);direction:rtl;text-align:right;overflow-x:hidden;
            }
            [data-bs-theme="dark"] .an-page,html.dark .an-page{
                --an-surface:#171d2d;
                --an-soft:rgba(255,255,255,.055);
                --an-border:rgba(255,255,255,.14);
                --an-text:#f8fafc;
                --an-muted:#e2e8f0;
                --an-shadow:0 18px 48px rgba(0,0,0,.28);
            }
            .an-page .breadcrumb{margin-bottom:0;color:var(--an-muted)}
            .an-page .breadcrumb a{color:var(--bs-primary);text-decoration:none}
            .an-hero,.an-shell{background:var(--an-surface);border:1px solid var(--an-border);border-radius:10px;box-shadow:var(--an-shadow)}
            .an-hero{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:20px;margin:14px 0}
            .an-hero-main{display:flex;align-items:center;gap:13px;min-width:0;text-align:right}
            .an-hero-icon{display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;flex:0 0 48px;color:#fff;background:linear-gradient(135deg,#0d6efd,#6f42c1);border-radius:10px;font-size:1.2rem}
            .an-hero h3{margin:0 0 4px;color:var(--an-text);font-size:1.22rem}
            .an-hero p{margin:0;color:var(--an-muted);font-size:.77rem}
            .an-summary{display:flex;align-items:center;gap:8px;flex:0 0 auto}
            .an-summary-item{min-width:90px;padding:9px 11px;text-align:center;background:var(--an-soft);border:1px solid var(--an-border);border-radius:8px}
            .an-summary-item strong{display:block;color:var(--an-text);font-size:1.05rem}
            .an-summary-item span{color:var(--an-muted);font-size:.68rem}
            .an-shell{overflow:hidden}
            .an-toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 16px;background:var(--an-soft);border-bottom:1px solid var(--an-border)}
            .an-filters{display:flex;flex-wrap:wrap;gap:7px}
            .an-filter{display:inline-flex;align-items:center;gap:7px;padding:7px 11px;color:var(--an-muted);background:var(--an-surface);border:1px solid var(--an-border);border-radius:8px;font-size:.73rem;font-weight:700}
            .an-filter.active{color:#fff;background:var(--bs-primary);border-color:var(--bs-primary)}
            .an-filter .badge{color:inherit!important;background:rgba(255,255,255,.16)!important}
            .an-list{display:grid;gap:0}
            .an-item{position:relative;display:grid;grid-template-columns:auto minmax(0,1fr) auto;align-items:start;gap:12px;padding:15px 16px;background:var(--an-surface);border-bottom:1px solid var(--an-border);transition:background .18s ease}
            .an-item:last-child{border-bottom:0}
            .an-item:hover{background:var(--an-soft)}
            .an-item.is-unread{background:rgba(var(--bs-primary-rgb),.075)}
            .an-item.is-unread:before{content:"";position:absolute;inset-block:0;inset-inline-start:0;width:4px;background:var(--bs-primary)}
            .an-item-icon{display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:10px;color:#fff;background:var(--bs-secondary);flex:0 0 40px}
            .an-item.is-unread .an-item-icon{background:var(--bs-primary)}
            .an-item-content{min-width:0;text-align:right}
            .an-item-title-row{display:flex;align-items:center;flex-wrap:wrap;gap:7px;margin-bottom:4px}
            .an-item-title{color:var(--an-text);font-size:.86rem;font-weight:800}
            .an-unread-dot{width:7px;height:7px;border-radius:50%;background:var(--bs-primary)}
            .an-item-body{margin:0;color:var(--an-muted);font-size:.76rem;line-height:1.85;overflow-wrap:anywhere}
            .an-item-meta{display:flex;align-items:center;flex-wrap:wrap;gap:7px;margin-top:8px;color:var(--an-muted);font-size:.68rem}
            .an-status{display:inline-flex;align-items:center;gap:4px;padding:3px 7px;border-radius:999px;font-weight:700}
            .an-status.is-read{color:var(--bs-success);background:rgba(var(--bs-success-rgb),.12)}
            .an-status.is-unread{color:var(--bs-danger);background:rgba(var(--bs-danger-rgb),.12)}
            .an-item-actions{display:flex;align-items:center;flex-wrap:wrap;justify-content:flex-end;gap:7px;direction:rtl}
            .an-page .btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;white-space:normal;text-align:center}
            .an-empty{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;min-height:260px;padding:30px;color:var(--an-muted);text-align:center}
            .an-empty i{display:inline-flex;align-items:center;justify-content:center;width:58px;height:58px;color:var(--bs-primary);background:rgba(var(--bs-primary-rgb),.1);border-radius:50%;font-size:1.4rem}
            .an-footer{padding:12px 16px;background:var(--an-soft);border-top:1px solid var(--an-border)}
            [data-bs-theme="dark"] .an-page .btn-outline-primary,html.dark .an-page .btn-outline-primary{color:#93c5fd;border-color:#60a5fa}
            [data-bs-theme="dark"] .an-page .text-muted,html.dark .an-page .text-muted{color:var(--an-muted)!important}
            @media(max-width:767.98px){
                .an-page{margin-inline:-5px}
                .an-page .app-page-head{padding-inline:5px}
                .an-hero{align-items:stretch;flex-direction:column;padding:15px}
                .an-summary{width:100%}
                .an-summary-item{flex:1 1 0}
                .an-toolbar{align-items:stretch;flex-direction:column}
                .an-filters{display:grid;grid-template-columns:1fr 1fr}
                .an-filter{justify-content:center;min-height:40px}
                .an-toolbar>.btn{width:100%;min-height:40px}
                .an-item{grid-template-columns:40px minmax(0,1fr);gap:10px;padding:14px 12px;text-align:right}
                .an-item-actions{grid-column:1/-1;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));width:100%}
                .an-item-actions .btn{min-height:38px}
                .an-item-actions .btn:only-child{grid-column:1/-1}
            }
            @media(max-width:480px){
                .an-hero-main{align-items:flex-start}.an-hero-icon{width:42px;height:42px;flex-basis:42px}.an-item{grid-template-columns:36px minmax(0,1fr)}.an-item-icon{width:36px;height:36px;flex-basis:36px}
                .an-summary{display:grid;grid-template-columns:1fr 1fr}.an-summary-item{min-width:0}
                .an-item-actions{grid-template-columns:1fr}.an-item-actions .btn,.an-item-actions .btn:only-child{grid-column:1}
                .an-item-meta{align-items:flex-start;flex-direction:column}
            }
        </style>
    @endpush

    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">اعلانات من</li>
            </ol>
        </nav>
    </div>

    <section class="an-hero">
        <div class="an-hero-main">
            <span class="an-hero-icon"><i class="fi fi-rr-bell"></i></span>
            <div>
                <h3>مرکز اعلانات</h3>
                <p>پیام‌های مربوط به فعالیت دانش‌آموزان و مخاطبان اختصاص‌داده‌شده به شما</p>
            </div>
        </div>
        <div class="an-summary">
            <div class="an-summary-item"><strong>{{ number_format($unreadCount) }}</strong><span>خوانده‌نشده</span></div>
            <div class="an-summary-item"><strong>{{ number_format($totalCount) }}</strong><span>کل اعلانات</span></div>
        </div>
    </section>

    <section class="an-shell">
        <div class="an-toolbar">
            <div class="an-filters">
                <button type="button" wire:click="setFilter('unread')" class="an-filter {{ $filter === 'unread' ? 'active' : '' }}">
                    <i class="fi fi-rr-envelope-dot"></i> خوانده‌نشده
                    <span class="badge">{{ number_format($unreadCount) }}</span>
                </button>
                <button type="button" wire:click="setFilter('all')" class="an-filter {{ $filter === 'all' ? 'active' : '' }}">
                    <i class="fi fi-rr-list"></i> همه اعلانات
                    <span class="badge">{{ number_format($totalCount) }}</span>
                </button>
            </div>
            @if($unreadCount > 0)
                <button type="button" wire:click="markAllAsRead" wire:loading.attr="disabled" class="btn btn-sm btn-success">
                    <i class="fi fi-rr-check-double"></i> علامت‌گذاری همه به‌عنوان خوانده‌شده
                </button>
            @endif
        </div>

        <div class="an-list">
            @forelse($notifications as $notification)
                <article class="an-item {{ $notification->is_read ? '' : 'is-unread' }}" wire:key="admin-notification-{{ $notification->id }}">
                    <span class="an-item-icon"><i class="fi fi-rr-bell"></i></span>
                    <div class="an-item-content">
                        <div class="an-item-title-row">
                            <span class="an-item-title">{{ $notification->title }}</span>
                            @unless($notification->is_read)<span class="an-unread-dot" title="خوانده‌نشده"></span>@endunless
                        </div>
                        @if($notification->body)<p class="an-item-body">{{ $notification->body }}</p>@endif
                        <div class="an-item-meta">
                            <span dir="ltr"><i class="fi fi-rr-clock-three"></i> {{ jdate($notification->created_at)->format('Y/m/d H:i') }}</span>
                            @if($notification->read_at)
                                <span class="an-status is-read"><i class="fi fi-rr-check"></i> خوانده‌شده</span>
                            @else
                                <span class="an-status is-unread"><i class="fi fi-rr-envelope"></i> خوانده‌نشده</span>
                            @endif
                        </div>
                    </div>
                    <div class="an-item-actions">
                        @if($notification->url)
                            <a href="{{ $notification->url }}" class="btn btn-sm btn-outline-primary"><i class="fi fi-rr-eye"></i> مشاهده</a>
                        @endif
                        @unless($notification->is_read)
                            <button type="button" wire:click="markAsRead({{ $notification->id }})" wire:loading.attr="disabled" class="btn btn-sm btn-success">
                                <i class="fi fi-rr-check"></i> خوانده شد
                            </button>
                        @endunless
                    </div>
                </article>
            @empty
                <div class="an-empty"><i class="fi fi-rr-bell-ring"></i><strong>اعلانی برای نمایش وجود ندارد</strong><span>اعلان‌های جدید در این بخش نمایش داده می‌شوند.</span></div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="an-footer">{{ $notifications->links('layouts.admin.pagination') }}</div>
        @endif
    </section>
</div>
