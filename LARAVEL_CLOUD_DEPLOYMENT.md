# Laravel Cloud Deployment Guide

## Issue: 404 Errors on Product Images

### Root Cause
Product images stored in `storage/app/public/products/` are not accessible because:
1. The symbolic link from `public/storage` → `storage/app/public` doesn't exist on Laravel Cloud
2. Some product images may reference files that don't exist in storage

### Solution Implemented

#### 1. Automatic Storage Link Creation
Added `storage:link` command to `composer.json` post-autoload-dump script. This ensures the symbolic link is created automatically on every deployment.

```json
"post-autoload-dump": [
    "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
    "@php artisan package:discover --ansi",
    "@php artisan storage:link --ansi"
]
```

#### 2. Updated Image URL Generation
Modified `ImageUploadController` to use `Storage::disk('public')->url()` instead of `url()` helper for proper URL generation based on the configured disk.

#### 3. Image Validation Command
Created `CheckProductImages` command to identify and fix products with missing images.

### Deployment Steps

1. **Push Changes to Repository**
   ```bash
   git add .
   git commit -m "Fix storage configuration for Laravel Cloud"
   git push
   ```

2. **Deploy to Laravel Cloud**
   - Laravel Cloud should automatically deploy when you push to the main branch
   - If not, trigger a manual deployment from the Laravel Cloud dashboard

3. **Verify Storage Link**
   After deployment, SSH into Laravel Cloud and verify:
   ```bash
   ls -la public/storage
   ```
   Should show: `public/storage -> ../storage/app/public`

4. **Check for Missing Images**
   Run the diagnostic command:
   ```bash
   php artisan products:check-images
   ```

5. **Fix Missing Images (if needed)**
   ```bash
   php artisan products:check-images --fix
   ```
   This replaces missing images with placeholder URLs.

### Environment Configuration

Ensure your Laravel Cloud environment has:
```env
APP_URL=https://laravel-main-321gvx.laravel.cloud
FILESYSTEM_DISK=public
```

### Testing

After deployment, test image access:
```
https://laravel-main-321gvx.laravel.cloud/storage/products/[filename].jpg
```

### Troubleshooting

**If images still show 404:**

1. Check if the file exists in storage:
   ```bash
   ls -la storage/app/public/products/
   ```

2. Verify the symbolic link:
   ```bash
   ls -la public/storage
   ```

3. Manually create the link if needed:
   ```bash
   php artisan storage:link
   ```

4. Check file permissions:
   ```bash
   chmod -R 755 storage/app/public
   ```

**If specific images are missing:**
- The file may not have been uploaded to Laravel Cloud
- Use `php artisan products:check-images --fix` to replace with placeholders
- Re-upload the images through the admin panel

### Notes

- New image uploads will work correctly after deployment
- Existing products may have references to missing files
- The `CheckProductImages` command helps identify and fix these issues
- Consider implementing a file sync strategy if you need to migrate existing images from local to cloud
