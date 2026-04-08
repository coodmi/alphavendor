<?php

namespace App\Services;

use App\DTOs\ProductData;
use App\Exceptions\ProductException;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Product Service.
 *
 * Handles all business logic related to products including creation,
 * updates, deletion, and queries. Uses the repository pattern for
 * data access and implements proper transaction management.
 *
 * @see ProductRepositoryInterface
 * @see ProductException
 */
class ProductService
{
    /**
     * Create a new ProductService instance.
     *
     * @param  ProductRepositoryInterface  $repository  Product repository
     */
    public function __construct(
        private readonly ProductRepositoryInterface $repository
    ) {}

    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $data  Product data
     * @param  UploadedFile|null  $image  Optional product image
     * @return Product The created product
     *
     * @throws ProductException When product creation fails
     */
    public function create(array $data, ?UploadedFile $image = null): Product
    {
        return DB::transaction(function () use ($data, $image) {
            try {
                // Handle image upload
                if ($image) {
                    $data['image'] = $this->uploadImage($image);
                }

                // Set defaults
                $data['stock'] = $data['stock'] ?? true;
                $data['is_active'] = $data['is_active'] ?? true;
                $data['rating'] = $data['rating'] ?? 0;
                $data['popularity'] = $data['popularity'] ?? 0;

                /** @var Product $product */
                $product = $this->repository->create($data);

                // Dispatch event for product creation
                event('product.created', $product);

                Log::info('Product created', [
                    'product_id' => $product->id,
                    'title' => $product->title ?? $product->name ?? 'Unknown',
                    'user_id' => auth()->id(),
                ]);

                // Invalidate related caches
                $this->invalidateProductCaches();

                return $product;
            } catch (\Exception $e) {
                // Clean up uploaded image if creation failed
                if (isset($data['image'])) {
                    $this->deleteImage($data['image']);
                }

                Log::error('Product creation failed', [
                    'error' => $e->getMessage(),
                    'data' => array_diff_key($data, ['image' => '']),
                ]);

                throw ProductException::creationFailed($e->getMessage());
            }
        });
    }

    /**
     * Create a product from a DTO.
     *
     * @param  ProductData  $data  Product data transfer object
     * @param  UploadedFile|null  $image  Optional product image
     * @return Product The created product
     *
     * @throws ProductException When product creation fails
     */
    public function createFromDto(ProductData $data, ?UploadedFile $image = null): Product
    {
        return $this->create($data->toArray(), $image);
    }

    /**
     * Update an existing product.
     *
     * @param  Product  $product  The product to update
     * @param  array<string, mixed>  $data  Updated data
     * @param  UploadedFile|null  $image  Optional new image
     * @return Product The updated product
     *
     * @throws ProductException When product update fails
     */
    public function update(Product $product, array $data, ?UploadedFile $image = null): Product
    {
        return DB::transaction(function () use ($product, $data, $image) {
            try {
                $oldImage = $product->image;

                // Handle image upload
                if ($image) {
                    $data['image'] = $this->uploadImage($image);
                }

                // Preserve existing values if not provided
                $data['stock'] = $data['stock'] ?? $product->stock;
                $data['is_active'] = $data['is_active'] ?? $product->is_active;
                $data['rating'] = $data['rating'] ?? $product->rating;
                $data['popularity'] = $data['popularity'] ?? $product->popularity;

                /** @var Product $updatedProduct */
                $updatedProduct = $this->repository->update($product, $data);

                // Delete old image after successful update
                if ($image && $oldImage) {
                    $this->deleteImage($oldImage);
                }

                // Dispatch event for product update
                event('product.updated', $updatedProduct);

                Log::info('Product updated', [
                    'product_id' => $updatedProduct->id,
                    'title' => $updatedProduct->title ?? $updatedProduct->name ?? 'Unknown',
                    'user_id' => auth()->id(),
                ]);

                // Invalidate related caches
                $this->invalidateProductCaches();

                return $updatedProduct;
            } catch (\Exception $e) {
                // Clean up newly uploaded image if update failed
                if (isset($data['image']) && $data['image'] !== $product->image) {
                    $this->deleteImage($data['image']);
                }

                Log::error('Product update failed', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage(),
                ]);

                throw ProductException::updateFailed($product->id, $e->getMessage());
            }
        });
    }

    /**
     * Delete a product.
     *
     * @param  Product  $product  The product to delete
     * @return bool True if deletion was successful
     *
     * @throws ProductException When product deletion fails
     */
    public function delete(Product $product): bool
    {
        return DB::transaction(function () use ($product) {
            try {
                $productId = $product->id;
                $productTitle = $product->title ?? $product->name ?? 'Unknown';
                $imagePath = $product->image;

                $deleted = $this->repository->delete($product);

                if ($deleted) {
                    // Delete associated image
                    $this->deleteImage($imagePath);

                    // Dispatch event for product deletion
                    event('product.deleted', ['id' => $productId, 'title' => $productTitle]);

                    Log::info('Product deleted', [
                        'product_id' => $productId,
                        'title' => $productTitle,
                        'user_id' => auth()->id(),
                    ]);

                    // Invalidate related caches
                    $this->invalidateProductCaches();
                }

                return $deleted;
            } catch (\Exception $e) {
                Log::error('Product deletion failed', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage(),
                ]);

                throw ProductException::deletionFailed($product->id, $e->getMessage());
            }
        });
    }

    /**
     * Find a product by ID.
     *
     * @param  int  $id  Product ID
     * @param  array<string>  $relations  Relations to eager load
     */
    public function find(int $id, array $relations = []): ?Product
    {
        /** @var Product|null */
        return $this->repository->find($id, $relations);
    }

    /**
     * Find a product by ID or throw exception.
     *
     * @param  int  $id  Product ID
     * @param  array<string>  $relations  Relations to eager load
     *
     * @throws ProductException When product is not found
     */
    public function findOrFail(int $id, array $relations = []): Product
    {
        $product = $this->find($id, $relations);

        if (! $product) {
            throw ProductException::notFound($id);
        }

        return $product;
    }

    /**
     * Find a product by slug.
     *
     * @param  string  $slug  Product slug
     * @param  array<string>  $relations  Relations to eager load
     */
    public function findBySlug(string $slug, array $relations = []): ?Product
    {
        return $this->repository->findBySlug($slug, $relations);
    }

    /**
     * Get paginated products with filters.
     *
     * @param  array<string, mixed>  $filters  Filter criteria
     * @param  int  $perPage  Items per page
     * @param  array<string>  $relations  Relations to eager load
     */
    public function paginate(array $filters = [], int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage, $relations);
    }

    /**
     * Get featured products.
     *
     * @param  int  $limit  Maximum number of products
     * @return Collection<int, Product>
     */
    public function getFeatured(int $limit = 8): Collection
    {
        return Product::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->limit($limit)
            ->get();
    }

    /**
     * Get popular products.
     *
     * @param  int  $limit  Maximum number of products
     * @param  array<string>  $relations  Relations to eager load
     * @return Collection<int, Product>
     */
    public function getPopular(int $limit = 10, array $relations = []): Collection
    {
        return $this->repository->getPopular($limit, $relations);
    }

    /**
     * Get active products.
     *
     * @param  array<string>  $relations  Relations to eager load
     * @return Collection<int, Product>
     */
    public function getActive(array $relations = []): Collection
    {
        return $this->repository->getActive($relations);
    }

    /**
     * Get products by category.
     *
     * @param  int  $categoryId  Category ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @param  int  $perPage  Items per page
     */
    public function getByCategory(int $categoryId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getByCategory($categoryId, $filters, $perPage);
    }

    /**
     * Search products.
     *
     * @param  string  $query  Search query
     * @param  array<string, mixed>  $filters  Additional filters
     * @param  int  $perPage  Items per page
     */
    public function search(string $query, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->search($query, $filters, $perPage);
    }

    /**
     * Update product stock status.
     *
     * @param  Product  $product  The product
     * @param  bool  $inStock  Stock status
     */
    public function updateStock(Product $product, bool $inStock): Product
    {
        $updatedProduct = $this->repository->updateStock($product, $inStock);

        event('product.stock_updated', [
            'product' => $updatedProduct,
            'in_stock' => $inStock,
        ]);

        Log::info('Product stock updated', [
            'product_id' => $product->id,
            'in_stock' => $inStock,
        ]);

        return $updatedProduct;
    }

    /**
     * Increment product popularity.
     *
     * @param  Product  $product  The product
     * @param  int  $amount  Amount to increment
     */
    public function incrementPopularity(Product $product, int $amount = 1): Product
    {
        return $this->repository->incrementPopularity($product, $amount);
    }

    /**
     * Get products with low stock.
     *
     * @return Collection<int, Product>
     */
    public function getLowStock(): Collection
    {
        return $this->repository->getLowStock();
    }

    /**
     * Upload a product image.
     *
     * @param  UploadedFile  $image  The image file
     * @return string The storage path
     *
     * @throws ProductException When image upload fails
     */
    protected function uploadImage(UploadedFile $image): string
    {
        try {
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(40).'.'.$extension;
            $path = $image->storeAs('products', $imageName, 'public');

            if (! $path) {
                throw new \RuntimeException('Failed to store image');
            }

            return '/storage/'.$path;
        } catch (\Exception $e) {
            Log::error('Product image upload failed', [
                'error' => $e->getMessage(),
            ]);

            throw ProductException::invalidImage($e->getMessage());
        }
    }

    /**
     * Delete a product image.
     *
     * @param  string|null  $imagePath  The image path
     */
    protected function deleteImage(?string $imagePath): void
    {
        if ($imagePath) {
            try {
                $path = str_replace('/storage/', '', $imagePath);
                Storage::disk('public')->delete($path);
            } catch (\Exception $e) {
                Log::warning('Failed to delete product image', [
                    'path' => $imagePath,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Invalidate product-related caches.
     */
    protected function invalidateProductCaches(): void
    {
        $cacheKeys = [
            'products.*',
            'repo:products:*',
        ];

        foreach ($cacheKeys as $pattern) {
            Cache::forget($pattern);
        }
    }
}
