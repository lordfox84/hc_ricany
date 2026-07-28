@extends('layouts.hc')

@section('title', $article->trans('title') . ' – HC COM-SYS Říčany')

@section('content')

@include('partials._navbar')

<div class="article-page">

  {{-- Back link --}}
  <div class="container" style="padding-top:2rem;">
    <a href="{{ route('articles.index') }}" class="article-back">{!! __('site.articles.all_articles') !!}</a>
  </div>

  <article class="container article-detail">

    {{-- Category + date --}}
    <div class="article-meta">
      <span class="tag article-tag">{{ $article->trans('category') }}</span>
      @if($article->published_at)
        <span class="article-date">{{ $article->published_at->locale(app()->getLocale())->isoFormat('LL') }}</span>
      @endif
    </div>

    {{-- Title --}}
    <h1 class="article-title">{{ $article->trans('title') }}</h1>

    {{-- Excerpt / perex --}}
    @if($article->excerpt_cs)
      <p class="article-excerpt">{{ $article->trans('excerpt') }}</p>
    @endif

    {{-- Featured image --}}
    @if($article->image)
      <div class="article-image-wrap">
        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->trans('title') }}" class="article-image">
      </div>
    @endif

    {{-- Body --}}
    <div class="article-body">{!! nl2br(e($article->trans('body'))) !!}</div>

  </article>

  {{-- Navigation to other articles --}}
  <div class="container" style="padding-bottom:4rem;text-align:center;">
    <a href="{{ route('articles.index') }}" class="btn btn-outline">{{ __('site.articles.back_to_all') }}</a>
  </div>

</div>

@endsection
