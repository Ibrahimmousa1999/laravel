@echo off
echo Creating Laravel storage symbolic link...
echo.

php artisan storage:link

echo.
echo Done! Storage link created.
echo Images will now be accessible at: http://localhost:8000/storage/products/
echo.
pause
