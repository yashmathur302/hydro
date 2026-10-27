@extends('frontend.layouts.app')

@section('content')
    @if($page->sections)
        @foreach($page->sections as $section)
            
            @if($section->section_type == 'banner')
                @include('frontend.sections.hero', ['section' => $section])
            @endif

            @if($section->section_type == 'about')
                @include('frontend.sections.about', ['section' => $section])
            @endif

            @if($section->section_type == 'industries')
                @include('frontend.sections.industries', ['section' => $section])
            @endif

            @if($section->section_type == 'products')
                @include('frontend.sections.products', ['section' => $section])
            @endif

            @if($section->section_type == 'certificates')
                @include('frontend.sections.certificates', ['section' => $section])
            @endif

            @if($section->section_type == 'blogs')
                @include('frontend.sections.blogs', ['section' => $section])
            @endif

            @if($section->section_type == 'cta')
                @include('frontend.sections.cta', ['section' => $section])
            @endif

        @endforeach
    @endif
@endsection