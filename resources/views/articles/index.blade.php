@extends('layouts.hc')

@section('title', __('site.articles.index_meta_title'))

@section('content')

@include('partials._navbar')

<div class="article-page">

  <div class="container" style="padding-top:3rem;padding-bottom:4rem;">

    <div class="section-header" style="margin-bottom:2.5rem;">
      <div>
        <div class="tag">{{ __('site.articles.index_tag') }}</div>
        <h2>{{ __('site.articles.index_title') }}</h2>
      </div>
      <a href="{{ route('home') }}#news" class="btn btn-outline">{!! __('site.articles.back') !!}</a>
    </div>

    <div class="news-grid">
      @forelse($articles as $article)
        <a href="{{ route('articles.show', $article) }}" class="news-card {{ $article->is_featured ? 'featured' : '' }}" style="text-decoration:none;color:inherit;" data-id="{{ $article->id }}">
          @if($article->image)
            <div class="news-card-img" style="background-image:url('{{ asset('storage/' . $article->image) }}');"></div>
          @endif
          <div class="news-date">
            {{ $article->published_at ? $article->published_at->locale(app()->getLocale())->isoFormat('LL') : '' }} &middot; {{ $article->trans('category') }}
          </div>
          <h3>{{ $article->trans('title') }}</h3>
          <p>{{ $article->trans('excerpt') }}</p>
          <div class="news-card-arrow"><span>{{ __('site.articles.read_more') }}</span> &rarr;</div>
        </a>
      @empty
        <p style="color:var(--gray-light);grid-column:1/-1;">{{ __('site.articles.empty') }}</p>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($articles->hasPages())
      <div style="margin-top:3rem;display:flex;justify-content:center;gap:0.5rem;flex-wrap:wrap;">
        {{$articles->links()}}
      </div>
    @endif

  </div>

</div>

@endsection
