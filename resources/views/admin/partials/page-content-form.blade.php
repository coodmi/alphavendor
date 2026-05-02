{{--
    Reusable page content form partial.
    Variables:
      $page        - PageContent model instance (nullable)
      $pageTitle   - Admin page heading
      $pageIcon    - FontAwesome icon class
      $updateRoute - Named route for form action
      $previewRoute- Named route for preview link (nullable)
      $defaultTitle- Default value for title field
      $editorId    - Unique ID for CKEditor textarea
--}}
@extends('layouts.dashboard')

@section('title', $pageTitle)
@section('page-title', $pageTitle)

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="{{ $pageIcon }} text-teal-600"></i> {{ $pageTitle }}
            </h1>
            <p class="text-gray-500 text-sm mt-1">Manage the content and SEO settings for this page</p>
        </div>
        @if(isset($previewRoute) && $previewRoute)
        <a href="{{ route($previewRoute) }}" target="_blank"
           class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
            <i class="fas fa-external-link-alt"></i> Preview Page
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl flex items-center gap-3">
        <i class="fas fa-check-circle text-green-600 text-xl"></i>
        <span class="text-green-800 font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
        <div class="flex items-center gap-2 mb-2">
            <i class="fas fa-exclamation-circle text-red-500"></i>
            <span class="font-semibold text-red-700">Please fix the following errors:</span>
        </div>
        <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route($updateRoute) }}" method="POST">
        @csrf

        {{-- SEO Meta --}}
        @include('admin.partials.seo-meta-fields', ['meta' => $page])

        {{-- Page Content --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="{{ $pageIcon }} text-teal-600"></i> Page Content
                </h3>
            </div>
            <div class="p-6 space-y-5">

                {{-- Page Title --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">
                        Page Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title"
                           value="{{ old('title', $page->title ?? $defaultTitle) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm @error('title') border-red-400 @enderror">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Content --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">
                        Content <span class="text-red-500">*</span>
                    </label>
                    <textarea name="content" id="{{ $editorId }}" required
                              class="w-full @error('content') border-red-400 @enderror">{{ old('content', $page->content ?? '') }}</textarea>
                    @error('content')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Active toggle --}}
                <div class="flex items-center gap-3 pt-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }}
                           class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">
                        Active — display this page to visitors
                    </label>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 pb-8">
            <button type="submit"
                    class="px-8 py-2.5 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800
                           text-white rounded-lg text-sm font-semibold transition flex items-center gap-2 shadow-sm">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
ClassicEditor.create(document.querySelector('#{{ $editorId }}'), {
    toolbar: { items: ['heading','|','bold','italic','underline','strikethrough','|','link','bulletedList','numberedList','|','alignment','|','indent','outdent','|','blockQuote','insertTable','|','undo','redo'] },
    heading: { options: [
        { model:'paragraph', title:'Paragraph', class:'ck-heading_paragraph' },
        { model:'heading1', view:'h1', title:'Heading 1', class:'ck-heading_heading1' },
        { model:'heading2', view:'h2', title:'Heading 2', class:'ck-heading_heading2' },
        { model:'heading3', view:'h3', title:'Heading 3', class:'ck-heading_heading3' },
    ]},
    table: { contentToolbar: ['tableColumn','tableRow','mergeTableCells'] }
}).catch(console.error);
</script>
@endsection
