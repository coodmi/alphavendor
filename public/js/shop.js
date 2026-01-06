// Shop Filter Functionality
document.addEventListener("DOMContentLoaded", function () {
    const filterForm = document.getElementById("filterForm");
    const clearFiltersBtn = document.querySelector(".btn-clear-filters");
    const applyPriceBtn = document.querySelector(".btn-apply-filter");
    const categoryCheckboxes = document.querySelectorAll(".category-checkbox");
    const brandCheckboxes = document.querySelectorAll(".brand-checkbox");
    const ratingCheckboxes = document.querySelectorAll(".rating-checkbox");
    const vendorTypeCheckboxes = document.querySelectorAll(
        ".vendor-type-checkbox"
    );
    const minPriceInput = document.querySelector(".min-price-input");
    const maxPriceInput = document.querySelector(".max-price-input");
    const sortDropdown = document.querySelector(".sort-dropdown select");
    const perPageDropdown = document.querySelector(".per-page-dropdown select");
    const viewButtons = document.querySelectorAll(".view-btn");
    const productsGridView = document.querySelector(".products-grid-view");

    // Initialize from URL parameters
    initializeFiltersFromURL();

    // Category filter change
    if (categoryCheckboxes) {
        categoryCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", applyFilters);
        });
    }

    // Brand filter change
    if (brandCheckboxes) {
        brandCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", applyFilters);
        });
    }

    // Rating filter change
    if (ratingCheckboxes) {
        ratingCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", applyFilters);
        });
    }

    // Vendor type filter change
    if (vendorTypeCheckboxes) {
        vendorTypeCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", applyFilters);
        });
    }

    // Price filter apply
    if (applyPriceBtn) {
        applyPriceBtn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("Apply Filter button clicked");
            console.log(
                "Min:",
                minPriceInput?.value,
                "Max:",
                maxPriceInput?.value
            );
            applyFilters();
        });
    }

    // Price input enter key
    [minPriceInput, maxPriceInput].forEach((input) => {
        if (input) {
            input.addEventListener("keypress", function (e) {
                if (e.key === "Enter") {
                    applyFilters();
                }
            });
        }
    });

    // Sort dropdown change
    if (sortDropdown) {
        sortDropdown.addEventListener("change", applyFilters);
    }

    // Per page dropdown change
    if (perPageDropdown) {
        perPageDropdown.addEventListener("change", applyFilters);
    }

    // Clear all filters
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener("click", function () {
            // Uncheck all checkboxes
            document
                .querySelectorAll('input[type="checkbox"]')
                .forEach((cb) => (cb.checked = false));

            // Reset price inputs
            if (minPriceInput) minPriceInput.value = 0;
            if (maxPriceInput) maxPriceInput.value = 10000;

            // Reset sliders
            const rangeMin = document.querySelector(".range-min");
            const rangeMax = document.querySelector(".range-max");
            if (rangeMin) rangeMin.value = 0;
            if (rangeMax) rangeMax.value = 10000;

            // Reset sort and per page
            if (sortDropdown) sortDropdown.value = "default";
            if (perPageDropdown) perPageDropdown.value = "24";

            // Apply filters (empty)
            applyFilters();
        });
    }

    // View mode toggle
    viewButtons.forEach((btn) => {
        btn.addEventListener("click", function () {
            const view = this.dataset.view;

            viewButtons.forEach((b) => b.classList.remove("active"));
            this.classList.add("active");

            if (view === "list") {
                productsGridView.classList.add("list-view");
                productsGridView.classList.remove("grid-view");
            } else {
                productsGridView.classList.add("grid-view");
                productsGridView.classList.remove("list-view");
            }
        });
    });

    // Remove filter tag
    document.querySelectorAll(".remove-filter").forEach((btn) => {
        btn.addEventListener("click", function () {
            const tag = this.closest(".filter-tag");
            const filterType = tag.dataset.filterType;
            const filterValue = tag.dataset.filterValue;

            // Uncheck corresponding checkbox
            if (filterType === "category") {
                const checkbox = document.querySelector(
                    `input[value="${filterValue}"]`
                );
                if (checkbox) checkbox.checked = false;
            }

            tag.remove();
            applyFilters();
        });
    });

    // Price range sliders - Handled in inline script for immediate execution
    // Code moved to blade template for better reliability

    // Brand search functionality
    const brandSearch = document.querySelector(".search-filter input");
    if (brandSearch) {
        brandSearch.addEventListener("input", function () {
            const searchTerm = this.value.toLowerCase();
            const brandItems = document.querySelectorAll(
                ".filter-box:has(.search-filter) .filter-list li"
            );

            brandItems.forEach((item) => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? "" : "none";
            });
        });
    }

    // Show more brands button
    const showMoreBtn = document.querySelector(".show-more-btn");
    if (showMoreBtn) {
        showMoreBtn.addEventListener("click", function () {
            const brandList = this.previousElementSibling;
            const hiddenItems = brandList.querySelectorAll("li:nth-child(n+6)");

            if (this.textContent.includes("Show More")) {
                hiddenItems.forEach((item) => (item.style.display = ""));
                this.textContent = "Show Less -";
            } else {
                hiddenItems.forEach((item) => (item.style.display = "none"));
                this.textContent = "Show More +";
            }
        });
    }

    // Apply filters function
    // Apply filters function
    function applyFilters() {
        console.log("applyFilters called");
        const params = new URLSearchParams();

        // Get selected categories
        const selectedCategories = Array.from(
            document.querySelectorAll(".category-checkbox:checked")
        ).map((cb) => cb.value);
        selectedCategories.forEach((cat) => params.append("categories[]", cat));

        // Get selected brands
        const selectedBrands = Array.from(
            document.querySelectorAll(".brand-checkbox:checked")
        ).map((cb) => cb.value);
        selectedBrands.forEach((brand) => params.append("brands[]", brand));

        // Get selected vendor types
        const selectedVendorTypes = Array.from(
            document.querySelectorAll(".vendor-type-checkbox:checked")
        ).map((cb) => cb.value);
        selectedVendorTypes.forEach((type) =>
            params.append("vendor_types[]", type)
        );

        // Get rating filter
        const selectedRating = document.querySelector(
            ".rating-checkbox:checked"
        );
        if (selectedRating) {
            params.append("min_rating", selectedRating.value);
        }

        // Get price range
        const minPrice = minPriceInput ? parseInt(minPriceInput.value) : 0;
        const maxPrice = maxPriceInput ? parseInt(maxPriceInput.value) : 10000;

        if (minPrice > 0) {
            params.append("min_price", minPrice);
        }
        if (maxPrice < 10000) {
            params.append("max_price", maxPrice);
        }

        // Get sort option
        if (sortDropdown && sortDropdown.value !== "default") {
            params.append("sort", sortDropdown.value);
        }

        // Get per page
        if (perPageDropdown && perPageDropdown.value !== "24") {
            params.append("per_page", perPageDropdown.value);
        }

        // Redirect with filters
        const url =
            window.location.pathname +
            (params.toString() ? "?" + params.toString() : "");
        window.location.href = url;
    }

    // Initialize filters from URL
    function initializeFiltersFromURL() {
        const params = new URLSearchParams(window.location.search);

        // Set categories
        const categories = params.getAll("categories[]");
        categories.forEach((cat) => {
            const checkbox = document.querySelector(
                `.category-checkbox[value="${cat}"]`
            );
            if (checkbox) checkbox.checked = true;
        });

        // Set brands
        const brands = params.getAll("brands[]");
        brands.forEach((brand) => {
            const checkbox = document.querySelector(
                `.brand-checkbox[value="${brand}"]`
            );
            if (checkbox) checkbox.checked = true;
        });

        // Set vendor types
        const vendorTypes = params.getAll("vendor_types[]");
        vendorTypes.forEach((type) => {
            const checkbox = document.querySelector(
                `.vendor-type-checkbox[value="${type}"]`
            );
            if (checkbox) checkbox.checked = true;
        });

        // Set rating
        const rating = params.get("min_rating");
        if (rating) {
            const checkbox = document.querySelector(
                `.rating-checkbox[value="${rating}"]`
            );
            if (checkbox) checkbox.checked = true;
        }

        // Set price range
        const minPrice = params.get("min_price");
        const maxPrice = params.get("max_price");
        if (minPrice && minPriceInput) {
            minPriceInput.value = minPrice;
            if (rangeMin) rangeMin.value = minPrice;
        }
        if (maxPrice && maxPriceInput) {
            maxPriceInput.value = maxPrice;
            if (rangeMax) rangeMax.value = maxPrice;
        }

        // Set sort
        const sort = params.get("sort");
        if (sort && sortDropdown) {
            sortDropdown.value = sort;
        }

        // Set per page
        const perPage = params.get("per_page");
        if (perPage && perPageDropdown) {
            perPageDropdown.value = perPage;
        }
    }

    // Quick add to cart functionality
    document.querySelectorAll(".quick-add-btn").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            // Add your cart logic here
            this.innerHTML = '<i class="fas fa-check"></i> Added!';
            setTimeout(() => {
                this.innerHTML =
                    '<i class="fas fa-shopping-cart"></i> Quick Add';
            }, 2000);
        });
    });

    // Wishlist functionality
    document
        .querySelectorAll('.action-btn[title="Add to Wishlist"]')
        .forEach((btn) => {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                const icon = this.querySelector("i");
                icon.classList.toggle("far");
                icon.classList.toggle("fas");
            });
        });
});
