@include('admin.partials.page-content-form', [
    'page'         => $page,
    'pageTitle'    => 'Shipping Info',
    'pageIcon'     => 'fas fa-shipping-fast',
    'updateRoute'  => 'admin.page-contents.shipping.update',
    'previewRoute' => 'shipping-info',
    'defaultTitle' => 'Shipping Information',
    'editorId'     => 'shipping-editor',
])
