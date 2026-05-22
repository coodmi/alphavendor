{{-- Product SEO fields — use in admin product add/edit modals --}}
<div style="margin-top: 20px; padding: 18px; border: 1px solid #e5e7eb; border-radius: 10px; background: #f8fafc;">
    <h4 style="margin: 0 0 16px 0; color: #2c3e50; font-size: 15px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-search" style="color: #667eea;"></i> SEO Settings
        <span style="font-size: 11px; font-weight: normal; color: #7f8c8d;">(optional — improves search visibility)</span>
    </h4>

    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Meta Title</label>
        <input type="text" name="meta_title" id="productMetaTitle" maxlength="255"
               placeholder="SEO title for this product (50–60 characters recommended)"
               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
        <p style="margin: 6px 0 0 0; font-size: 12px; color: #7f8c8d;">Leave blank to use the product name</p>
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Meta Keywords</label>
        <input type="text" name="meta_keywords" id="productMetaKeywords" maxlength="500"
               placeholder="keyword1, keyword2, keyword3, keyword4, keyword5"
               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
        <p style="margin: 6px 0 0 0; font-size: 12px; color: #7f8c8d;">Comma-separated keywords (optional)</p>
    </div>

    <div>
        <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Meta Description</label>
        <textarea name="meta_description" id="productMetaDescription" rows="3" maxlength="500"
                  placeholder="Short description for Google search results (150–160 characters recommended)"
                  style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical;"></textarea>
    </div>
</div>
