Plan: Export Dashboard CRUD with Dynamic Frontend
Implement complete CRUD operations for Brands, Categories, and Products in the Exporter Dashboard, create seeders with valid image URLs, and connect the Export frontend page to display dynamic data with working filters.

Steps
Create Exporter CRUD Controllers - Create ExporterBrandController, ExporterCategoryController, and ExporterProductController in Controllers following the existing RetailerBrandController pattern with vendor_id filtering, JSON responses for AJAX, and image URL/upload support.

Add Exporter Routes - Update web.php to add brand, category, and product CRUD routes under the exporter middleware group (lines 118-120), mirroring the retailer routes pattern (lines 94-110).

Create Exporter Dashboard Views - Create three new Blade views (exporter/brands.blade.php, exporter/categories.blade.php, exporter/products.blade.php) in views with:

Image upload/URL toggle with live preview (following retailer pattern)
Toast notification system (showToast() function)
Professional delete confirmation modal with gradient styling
Tables displaying both URL and uploaded images correctly
Update Exporter Dashboard Navigation - Modify resources/views/exporter/dashboard.blade.php to add sidebar navigation links to Brands, Categories, and Products management pages.

Create Exporter Seeders - Create ExporterBrandSeeder, ExporterCategorySeeder, and ExporterProductSeeder in seeders using Unsplash image URLs (following RetailerBrandSeeder pattern), and register them in DatabaseSeeder.php.

Make Export Frontend Dynamic - Update ExportController to pass real data and modify export.blade.php to:

Display products from exporter's database with dynamic images, prices, categories
Populate category filter from database with real counts
Make Minimum Order (MOQ) filter functional based on product data
Make Supplier Location filter dynamic based on vendor locations
Implement AJAX filtering that works with the backend
Further Considerations
Supplier Location Source: Should supplier location come from a new field on User/Vendor model, or a separate locations table? Recommend adding location field to users table.

Minimum Order Field: Products table needs a minimum_order_quantity field - should this be added via new migration? Recommend yes, with default value of 1.

Wholesale Page Updates: Should the same dynamic MOQ and Supplier Location logic be applied to wholesale.blade.php as requested? Recommend yes for consistency.
