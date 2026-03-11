<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckProductImages extends Command
{
    protected $signature = 'products:check-images {--fix : Automatically fix missing images}';
    protected $description = 'Check for products with missing image files and optionally fix them';

    public function handle()
    {
        $this->info('Checking product images...');
        
        $products = Product::all();
        $missingImages = [];
        $fixedCount = 0;
        
        foreach ($products as $product) {
            $imagePath = $this->extractPathFromUrl($product->image);
            
            if ($imagePath && !Storage::disk('public')->exists($imagePath)) {
                $missingImages[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->image,
                    'path' => $imagePath
                ];
                
                if ($this->option('fix')) {
                    // Set to a default placeholder image
                    $product->update([
                        'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800'
                    ]);
                    $fixedCount++;
                    $this->warn("Fixed product #{$product->id}: {$product->name}");
                }
            }
        }
        
        if (empty($missingImages)) {
            $this->info('✓ All product images exist!');
            return 0;
        }
        
        $this->newLine();
        $this->error('Found ' . count($missingImages) . ' products with missing images:');
        $this->table(
            ['ID', 'Product Name', 'Missing Image URL'],
            array_map(fn($item) => [$item['id'], $item['name'], $item['image']], $missingImages)
        );
        
        if ($this->option('fix')) {
            $this->newLine();
            $this->info("Fixed {$fixedCount} products with placeholder images.");
        } else {
            $this->newLine();
            $this->comment('Run with --fix option to replace missing images with placeholders.');
        }
        
        return 0;
    }
    
    private function extractPathFromUrl($url)
    {
        // Extract path from URLs like: http://localhost/storage/products/filename.jpg
        if (preg_match('#/storage/(.+)$#', $url, $matches)) {
            return $matches[1];
        }
        
        // If it's already a path like: products/filename.jpg
        if (strpos($url, 'products/') === 0) {
            return $url;
        }
        
        // If it's an external URL (unsplash, etc), return null
        if (filter_var($url, FILTER_VALIDATE_URL) && !str_contains($url, '/storage/')) {
            return null;
        }
        
        return null;
    }
}
