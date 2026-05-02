@include('admin.partials.page-content-form', [
    'page'         => $page,
    'pageTitle'    => 'Privacy Policy',
    'pageIcon'     => 'fas fa-shield-alt',
    'updateRoute'  => 'admin.page-contents.privacy.update',
    'previewRoute' => 'privacy',
    'defaultTitle' => 'Privacy Policy',
    'editorId'     => 'privacy-editor',
])
