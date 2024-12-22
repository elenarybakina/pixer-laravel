<?php

namespace Marvel\Console;

use Illuminate\Console\Command;
use Marvel\Database\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class CopyProductsToLocale extends Command
{
    protected $signature = 'products:copy-to-locale {from_lang} {to_lang}';
    protected $description = 'Copy all products from one locale to another';

    public function handle()
    {
        $fromLang = $this->argument('from_lang');
        $toLang = $this->argument('to_lang');

        // 设置更长的数据库超时时间
        Config::set('database.connections.mysql.options', [
            \PDO::ATTR_EMULATE_PREPARES => true,
            \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false
        ]);
        DB::reconnect();

        $this->info("Copying products from {$fromLang} to {$toLang}...");

        try {
            // Get total count of products
            $total = Product::where('language', $fromLang)->count();
            $this->info("Found {$total} products to copy.");

            // Process in chunks of 10
            $count = 0;
            Product::where('language', $fromLang)
                ->chunk(10, function($products) use ($toLang, &$count) {
                    foreach ($products as $product) {
                        DB::beginTransaction();
                        try {
                            // Check if product already exists in target language
                            $existingProduct = Product::where('language', $toLang)
                                ->where('slug', $product->slug)
                                ->first();

                            if (!$existingProduct) {
                                // Create new product with target language
                                $newProduct = $product->replicate();
                                $newProduct->language = $toLang;
                                $newProduct->save();

                                // Copy all relationships
                                $this->copyRelationships($product, $newProduct);
                                
                                $count++;
                                $this->info("Copied product {$count}: {$product->name}");
                            } else {
                                $this->warn("Product already exists: {$product->name}");
                            }
                            
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            $this->error("Error copying product {$product->name}: " . $e->getMessage());
                        }
                    }
                });

            $this->info("Successfully copied {$count} products to {$toLang}!");

        } catch (\Exception $e) {
            $this->error("Error occurred: " . $e->getMessage());
        }
    }

    protected function copyRelationships($sourceProduct, $targetProduct)
    {
        // Copy categories
        $categories = $sourceProduct->categories()->pluck('categories.id')->toArray();
        if (!empty($categories)) {
            $targetProduct->categories()->sync($categories);
        }

        // Copy tags
        $tags = $sourceProduct->tags()->pluck('tags.id')->toArray();
        if (!empty($tags)) {
            $targetProduct->tags()->sync($tags);
        }

        // Copy variations if any
        foreach ($sourceProduct->variations as $variation) {
            $newVariation = $variation->replicate();
            $newVariation->product_id = $targetProduct->id;
            $newVariation->language = $targetProduct->language;
            $newVariation->save();
        }

        // Copy variation options
        foreach ($sourceProduct->variation_options as $option) {
            $newOption = $option->replicate();
            $newOption->product_id = $targetProduct->id;
            $newOption->save();
        }

        // Copy product type
        if ($sourceProduct->type) {
            $targetProduct->type()->associate($sourceProduct->type);
            $targetProduct->save();
        }

        // Copy shop
        if ($sourceProduct->shop) {
            $targetProduct->shop()->associate($sourceProduct->shop);
            $targetProduct->save();
        }
    }
}
