# Testing Guide: Regular Products Image Upload with Vue Dropzone

## Overview
This guide explains how to test the new async image upload functionality for regular products using vue2-dropzone.

## Prerequisites
- Application running locally
- Admin user account
- Test images (JPG, PNG, etc.) less than 2MB each

## Test Scenarios

### Scenario 1: Create a New Regular Product with Images

**Steps:**
1. Navigate to `/admin/catalogo/productos/create`
2. Check the "Es producto regular (Sin combinaciones)" checkbox
3. Fill in required product information:
   - Nombre: "Test Product"
   - Categoría: Select any category
   - Marca: Select any brand
   - Precio: Enter a price
4. Click on the "Multimedia" tab
5. Drag and drop 3-5 test images onto the dropzone area

**Expected Results:**
- ✅ Dropzone shows "Arrastra los archivos aquí para subirlos (Max 2MB)" message
- ✅ When images are dropped, they upload immediately (async)
- ✅ Upload progress is shown for each image
- ✅ After upload completes, images appear in a grid below the dropzone
- ✅ Each image shows:
  - Thumbnail preview
  - Remove button (X icon in top right)
  - Primary image button (star icon in top left)
  - Position number badge (bottom right)
- ✅ Image counter shows: "Imágenes cargadas (X)"

**Continue:**
6. Click the star icon on one image to set it as primary
7. Click the "Crear" button to save the product

**Expected Results:**
- ✅ Product is created successfully
- ✅ Redirect to product list
- ✅ Success message appears

### Scenario 2: Edit an Existing Regular Product

**Steps:**
1. Navigate to product list
2. Click "Editar" on a regular product
3. Click on the "Multimedia" tab

**Expected Results:**
- ✅ Existing images are displayed in the grid
- ✅ Primary image has green star badge
- ✅ Each image has its position number

**Continue:**
4. Drag and drop 2 more images onto the dropzone
5. Wait for upload to complete

**Expected Results:**
- ✅ New images upload asynchronously
- ✅ New images appear in the grid
- ✅ Total image counter increases

### Scenario 3: Delete an Image

**Steps:**
1. Edit a regular product with at least 2 images
2. Go to "Multimedia" tab
3. Click the X button on one image
4. Click "Si" in the confirmation dialog

**Expected Results:**
- ✅ Confirmation dialog appears: "¿Seguro desea eliminar esta imagen?"
- ✅ After confirmation, image is removed from display
- ✅ Success message: "Imagen removida con éxito"
- ✅ Image counter decreases

### Scenario 4: Change Primary Image

**Steps:**
1. Edit a regular product with at least 2 images
2. Go to "Multimedia" tab
3. Click the star icon on a non-primary image

**Expected Results:**
- ✅ The clicked image gets a green star (is_primary)
- ✅ The previous primary image loses the green star
- ✅ Success message: "Imagen establecida como principal"

### Scenario 5: Upload Large File (Error Handling)

**Steps:**
1. Edit a regular product
2. Go to "Multimedia" tab
3. Try to upload an image larger than 2MB

**Expected Results:**
- ✅ Error message appears: "El archivo es demasiado grande. Máx: 2MB."
- ✅ File is not uploaded
- ✅ File is removed from dropzone

### Scenario 6: Upload Invalid File Type

**Steps:**
1. Edit a regular product
2. Go to "Multimedia" tab
3. Try to upload a non-image file (PDF, DOC, etc.)

**Expected Results:**
- ✅ Error message appears: "No puede cargar archivos de este tipo."
- ✅ File is not uploaded

### Scenario 7: Verify Non-Regular Products Still Work

**Steps:**
1. Create or edit a NON-regular product
2. Uncheck "Es producto regular"
3. Fill in basic information
4. Click on "Combinaciones y Stocks" tab
5. Add a combination
6. Upload images to the combination dropzone

**Expected Results:**
- ✅ Images upload normally for combinations
- ✅ Images display per combination
- ✅ No regression - functionality unchanged

## Browser Testing

Test in the following browsers:
- [ ] Chrome/Edge (latest)
- [ ] Firefox (latest)
- [ ] Safari (if on Mac)

## Mobile Testing (Optional)

Test on mobile devices:
- [ ] iOS Safari
- [ ] Android Chrome

## Verification Checklist

After testing, verify:
- [ ] Images upload asynchronously without blocking the UI
- [ ] Images display immediately after upload
- [ ] Primary image can be set and changed
- [ ] Images can be deleted
- [ ] Image position numbers are correct
- [ ] Error handling works for large files
- [ ] Error handling works for invalid file types
- [ ] Form submission works correctly
- [ ] Non-regular products are not affected
- [ ] Console shows no JavaScript errors

## Database Verification

After creating/editing products, verify in database:

```sql
-- Check that images were created with temp_code first
SELECT * FROM products_images WHERE temp_code IS NOT NULL ORDER BY created_at DESC LIMIT 5;

-- Check that images are associated with product after save
SELECT * FROM products_images WHERE product_id = [YOUR_PRODUCT_ID];

-- Verify only one image is primary per product
SELECT product_id, COUNT(*) as primary_count 
FROM products_images 
WHERE is_primary = 1 
GROUP BY product_id 
HAVING COUNT(*) > 1;
-- Should return 0 rows

-- Check combination_index is 0 for regular products
SELECT * FROM products_images 
WHERE product_id IN (SELECT id FROM products WHERE is_regular = 1)
AND combination_index != 0;
-- Should return 0 rows
```

## Known Limitations

1. **Image Reordering**: Drag-and-drop reordering is not yet implemented. Position is assigned based on upload order.
2. **Bulk Upload**: Maximum 10 files at once (configurable in dropzone options)
3. **File Size**: Maximum 2MB per file (configurable)

## Future Enhancements

- Add vuedraggable for drag-and-drop reordering
- Add image preview lightbox
- Add basic image editing (crop, rotate)
- Add dimension validation
- Add automatic image optimization

## Troubleshooting

### Images Don't Upload
- Check browser console for errors
- Verify CSRF token is present
- Check file permissions on storage/products
- Verify route 'producto-imagen.store' exists

### Images Don't Display After Upload
- Check response from ProductImageController@store
- Verify url_img is returned in response
- Check Storage::disk('products') is configured correctly

### Primary Image Not Setting
- Verify route 'producto-imagen.set-primary' exists
- Check ProductImage model observer is working
- Verify is_primary field exists in database

## Test Report Template

```
Test Date: ___________
Tester: ___________
Browser: ___________

Scenario 1 (Create New): ☐ Pass ☐ Fail
Scenario 2 (Edit Existing): ☐ Pass ☐ Fail
Scenario 3 (Delete Image): ☐ Pass ☐ Fail
Scenario 4 (Change Primary): ☐ Pass ☐ Fail
Scenario 5 (Large File Error): ☐ Pass ☐ Fail
Scenario 6 (Invalid File Type): ☐ Pass ☐ Fail
Scenario 7 (Non-Regular Products): ☐ Pass ☐ Fail

Issues Found:
_______________________
_______________________

Notes:
_______________________
_______________________
```
