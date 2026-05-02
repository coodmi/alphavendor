@include('admin.partials.page-content-form', [
    'page'         => $page,
    'pageTitle'    => 'Terms & Conditions',
    'pageIcon'     => 'fas fa-file-contract',
    'updateRoute'  => 'admin.page-contents.terms.update',
    'previewRoute' => 'terms',
    'defaultTitle' => 'Terms & Conditions',
    'editorId'     => 'terms-editor',
])
