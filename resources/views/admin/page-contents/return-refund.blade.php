@include('admin.partials.page-content-form', [
    'page'         => $page,
    'pageTitle'    => 'Return & Refund Policy',
    'pageIcon'     => 'fas fa-undo-alt',
    'updateRoute'  => 'admin.page-contents.return-refund.update',
    'previewRoute' => null,
    'defaultTitle' => 'Return & Refund Policy',
    'editorId'     => 'return-refund-editor',
])
