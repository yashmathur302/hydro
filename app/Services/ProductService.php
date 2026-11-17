<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Images\StoredImage;
use App\Services\Images\WebpImageProcessor;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Creates, updates and deletes products together with their gallery images,
 * specification rows and brochure. Database work runs in a transaction;
 * files written during a failed save are removed again.
 */
final class ProductService
{
    private const IMAGE_DIRECTORY = 'products';

    private const BROCHURE_DIRECTORY = 'brochures';

    /** @var list<StoredImage> */
    private array $newImages = [];

    /** @var list<string> */
    private array $newFiles = [];

    public function __construct(
        private readonly WebpImageProcessor $images,
        private readonly Filesystem $publicDisk,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     * @param  list<array{label: string, value: string}>  $specifications
     * @param  list<UploadedFile>  $uploads
     */
    public function create(array $attributes, array $specifications, array $uploads, ?UploadedFile $brochure): Product
    {
        return $this->guardFiles(function () use ($attributes, $specifications, $uploads, $brochure): Product {
            $product = new Product($attributes);
            $product->brochure_path = $brochure ? $this->storeBrochure($brochure) : null;
            $product->save();

            $this->syncSpecifications($product, $specifications);
            $this->addImages($product, $uploads);

            return $product;
        });
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @param  list<array{label: string, value: string}>  $specifications
     * @param  array<int, array{alt_text?: string|null, sort_order?: int|null, remove?: bool}>  $existingImages  keyed by image id
     * @param  list<UploadedFile>  $uploads
     */
    public function update(
        Product $product,
        array $attributes,
        array $specifications,
        array $existingImages,
        array $uploads,
        ?UploadedFile $brochure,
        bool $removeBrochure,
    ): Product {
        $filesToDelete = [];

        $this->guardFiles(function () use ($product, $attributes, $specifications, $existingImages, $uploads, $brochure, $removeBrochure, &$filesToDelete): void {
            $product->fill($attributes);

            if ($brochure !== null || $removeBrochure) {
                $filesToDelete[] = $product->brochure_path;
                $product->brochure_path = $brochure ? $this->storeBrochure($brochure) : null;
            }

            $product->save();

            $this->syncSpecifications($product, $specifications);
            $filesToDelete = [...$filesToDelete, ...$this->updateExistingImages($product, $existingImages)];
            $this->addImages($product, $uploads);
        });

        // Old files are removed only once the new state is safely committed.
        $this->publicDisk->delete(array_values(array_filter($filesToDelete)));

        return $product;
    }

    public function delete(Product $product): void
    {
        $files = $product->images()->get()
            ->flatMap(static fn (ProductImage $image): array => [$image->path, $image->thumbnail_path])
            ->push($product->brochure_path)
            ->filter()
            ->values()
            ->all();

        $product->delete();

        $this->publicDisk->delete($files);
    }

    /**
     * @template TResult
     *
     * @param  callable(): TResult  $operation
     * @return TResult
     */
    private function guardFiles(callable $operation): mixed
    {
        $this->newImages = [];
        $this->newFiles = [];

        try {
            return DB::transaction($operation);
        } catch (Throwable $exception) {
            foreach ($this->newImages as $image) {
                $this->images->delete($image);
            }

            $this->publicDisk->delete($this->newFiles);

            throw $exception;
        }
    }

    /**
     * Replaces all specification rows, keeping the submitted order.
     *
     * @param  list<array{label: string, value: string}>  $specifications
     */
    private function syncSpecifications(Product $product, array $specifications): void
    {
        $product->specifications()->delete();

        foreach (array_values($specifications) as $position => $row) {
            $product->specifications()->create([
                'label' => $row['label'],
                'value' => $row['value'],
                'sort_order' => $position,
            ]);
        }
    }

    /**
     * @param  array<int, array{alt_text?: string|null, sort_order?: int|null, remove?: bool}>  $changes
     * @return list<string> files to delete after commit
     */
    private function updateExistingImages(Product $product, array $changes): array
    {
        $filesToDelete = [];

        // Only images that belong to this product can be touched (no IDOR via submitted ids).
        foreach ($product->images()->get() as $image) {
            $change = $changes[$image->id] ?? null;

            if ($change === null) {
                continue;
            }

            if (! empty($change['remove'])) {
                $filesToDelete[] = $image->path;
                $filesToDelete[] = $image->thumbnail_path;
                $image->delete();

                continue;
            }

            $image->fill([
                'alt_text' => $change['alt_text'] ?? null,
                'sort_order' => (int) ($change['sort_order'] ?? $image->sort_order),
            ])->save();
        }

        return $filesToDelete;
    }

    /**
     * @param  list<UploadedFile>  $uploads
     */
    private function addImages(Product $product, array $uploads): void
    {
        $nextPosition = (int) $product->images()->max('sort_order') + 1;

        foreach ($uploads as $upload) {
            $stored = $this->images->store($upload, self::IMAGE_DIRECTORY);
            $this->newImages[] = $stored;

            $product->images()->create([
                'path' => $stored->path,
                'thumbnail_path' => $stored->thumbnailPath,
                'alt_text' => $product->name,
                'sort_order' => $nextPosition++,
            ]);
        }
    }

    private function storeBrochure(UploadedFile $brochure): string
    {
        $path = (string) $this->publicDisk->putFileAs(
            self::BROCHURE_DIRECTORY,
            $brochure,
            $brochure->hashName(),
        );

        $this->newFiles[] = $path;

        return $path;
    }
}
