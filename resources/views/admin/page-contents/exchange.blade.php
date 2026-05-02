@include('admin.partials.page-content-form', [
    'page'         => $page,
    'pageTitle'    => 'Exchange Policy',
    'pageIcon'     => 'fas fa-exchange-alt',
    'updateRoute'  => 'admin.page-contents.exchange.update',
    'previewRoute' => null,
    'defaultTitle' => 'Exchange Policy',
    'editorId'     => 'exchange-editor',
])
