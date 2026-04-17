@extends('layouts.public')

@section('title', ($about->title ?? 'Giới thiệu về Dự án') . ' – ' . config('app.name'))

@push('styles')
<style>
.about-hero {
    text-align: center;
    padding: 80px 20px 40px;
    max-width: 800px;
    margin: 0 auto;
}
.about-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 48px;
    color: var(--gold);
    margin-bottom: 32px;
}
.about-content {
    font-size: 16px;
    line-height: 1.8;
    color: var(--text);
    margin-bottom: 60px;
    text-align: left;
}
.about-content img {
    max-width: 100%;
    border-radius: 8px;
    margin: 20px 0;
}
.about-content p {
    margin-bottom: 16px;
}

.team-section {
    padding: 60px 0 100px;
    border-top: 1px solid rgba(255,255,255,0.05);
}
.team-heading {
    text-align: center;
    font-family: 'Cormorant Garamond', serif;
    font-size: 36px;
    margin-bottom: 60px;
}
.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 40px;
}
.team-card {
    text-align: center;
}
.team-card-link {
    display: inline-block;
}
.team-avatar {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 20px;
    border: 3px solid rgba(201,168,76,0.3);
    transition: all 0.3s;
}
.team-card:hover .team-avatar {
    border-color: var(--gold);
    transform: scale(1.03);
}
.team-name {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 6px;
}
.team-name a {
    color: var(--text);
    text-decoration: none;
    transition: color 0.2s;
}
.team-name a:hover {
    color: var(--gold);
}
.team-role {
    font-size: 13px;
    color: var(--gold);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 16px;
}
.team-bio {
    font-size: 14px;
    color: var(--muted);
    line-height: 1.6;
    max-width: 90%;
    margin: 0 auto;
}
.no-avatar {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    margin: 0 auto 20px;
    background: #1a1d27;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    color: var(--muted);
    border: 3px solid rgba(255,255,255,0.05);
    transition: all 0.3s;
}
.team-card:hover .no-avatar {
    border-color: var(--gold);
}

@media (max-width: 768px) {
    .about-hero {
        padding: 40px 16px 20px;
    }
    .about-title {
        font-size: 36px;
    }
    .team-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
    .team-avatar, .no-avatar {
        width: 140px;
        height: 140px;
    }
    .team-name {
        font-size: 18px;
    }
    .team-role {
        font-size: 11px;
    }
}
@media (max-width: 480px) {
    .team-grid {
        grid-template-columns: 1fr;
    }
    .team-avatar, .no-avatar {
        width: 180px;
        height: 180px;
    }
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="about-hero">
        <h1 class="about-title">{{ $about->title ?? 'Về Chúng Tôi' }}</h1>
        <div class="about-content">
            {!! $about->content ?? '<p style="color:var(--muted); text-align:center;">Chưa có thông tin giới thiệu.</p>' !!}
        </div>
    </div>

    @if($members->count() > 0)
    <div class="team-section">
        <h2 class="team-heading">Đội Ngũ Dự Án</h2>
        
        <div class="team-grid">
            @foreach($members as $m)
            <div class="team-card">
                @if($m->custom_link)
                    <a href="{{ $m->custom_link }}" target="_blank" class="team-card-link">
                @endif
                
                @if($m->avatar_path)
                    <img src="{{ Storage::disk('public')->url($m->avatar_path) }}" class="team-avatar" alt="{{ $m->name }}">
                @else
                    <div class="no-avatar">{{ mb_substr($m->name, 0, 1) }}</div>
                @endif
                
                @if($m->custom_link)
                    </a>
                @endif

                <div class="team-name">
                    @if($m->custom_link)
                        <a href="{{ $m->custom_link }}" target="_blank">{{ $m->name }}</a>
                    @else
                        {{ $m->name }}
                    @endif
                </div>
                
                @if($m->role)
                    <div class="team-role">{{ $m->role }}</div>
                @endif
                
                @if($m->bio)
                    <div class="team-bio">{{ $m->bio }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
