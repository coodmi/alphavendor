@extends('layouts.dashboard')

@section('title', 'Terms & Conditions Management')
@section('page-title', 'Terms & Conditions')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fas fa-file-contract text-orange-600 mr-3"></i>Terms & Conditions Management
            </h1>
            <p class="text-gray-600">Manage the content displayed on your Terms & Conditions page</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                    <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('admin.page-contents.terms.update') }}" method="POST" class="bg-white rounded-2xl shadow-xl p-8">
            @csrf

            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-heading text-orange-600 mr-2"></i>Page Title
                    </label>
                    <input type="text" name="title" value="{{ old('title', $page->title ?? 'Terms & Conditions') }}" required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-orange-500 transition-all @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-align-left text-orange-600 mr-2"></i>Content
                    </label>
                    <textarea name="content" id="terms-editor" required
                        class="w-full @error('content') border-red-500 @enderror">{{ old('content', $page->content ?? '') }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                        {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }}
                        class="w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                    <label for="is_active" class="ml-3 text-sm font-bold text-gray-700">
                        Active (Display this page to users)
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                    <a href="{{ route('terms') }}" target="_blank" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-bold transition-all">
                        <i class="fas fa-external-link-alt mr-2"></i>Preview Page
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- CKEditor -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#terms-editor'), {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'link', 'bulletedList', 'numberedList', '|',
                    'alignment', '|',
                    'indent', 'outdent', '|',
                    'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ]
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
                ]
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            }
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endsection
