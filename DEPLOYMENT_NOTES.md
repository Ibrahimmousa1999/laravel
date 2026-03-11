# Deployment Notes for LuxStore

## Important Environment Variables

When deploying to production server, ensure these environment variables are set correctly:

### APP_URL
```env
APP_URL=https://your-domain.com
```
This is **critical** for image uploads to work correctly. The system uses `APP_URL` to generate absolute URLs for uploaded images.

### File Storage
```env
FILESYSTEM_DISK=public
```

### After Deployment

1. Run storage link command:
```bash
php artisan storage:link
```

2. Ensure the `storage/app/public` directory has write permissions:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

3. Clear config cache:
```bash
php artisan config:clear
php artisan cache:clear
```

## Image Upload Fix

The `ImageUploadController` now uses `config('app.url')` to generate absolute URLs:
```php
$url = config('app.url') . '/storage/' . $path;
```

This ensures images work correctly when:
- Uploaded from local development
- Pushed to production server
- Accessed from frontend on different domain

## Testing Image Uploads

1. Create a new product with an image
2. Verify the image URL in the response contains your domain
3. Check that the image displays correctly in the frontend
4. Update a product and verify images persist correctly
