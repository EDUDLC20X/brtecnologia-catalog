# Testing API Endpoints - Quick Guide

## Setup

### 1. Start the Development Server
```bash
php artisan serve
# Server running at: http://localhost:8000
```

### 2. Get Authentication Token (If Needed)
For protected endpoints (Reviews), you'll need a Bearer token. Use Laravel Sanctum:

```bash
# Create a token for testing
php artisan tinker
>>> $user = User::first();
>>> $token = $user->createToken('test')->plainTextToken;
>>> echo $token;
```

Copy the token and use it in the `Authorization: Bearer YOUR_TOKEN` header.

## Testing Endpoints

### Products - Public

#### 1. Get All Products (with pagination)
```bash
curl http://localhost:8000/api/v1/products
```

**With filters:**
```bash
# Filter by price range
curl "http://localhost:8000/api/v1/products?price_min=100&price_max=500"

# Search by name
curl "http://localhost:8000/api/v1/products?search=laptop"

# Sort by price ascending
curl "http://localhost:8000/api/v1/products?sort=price_asc"

# Multiple parameters
curl "http://localhost:8000/api/v1/products?search=phone&category_id=2&sort=best_rated&per_page=20"
```

**Query Parameters:**
- `search` - Search in name, SKU, description
- `category_id` - Filter by category (int)
- `price_min` - Minimum price (int)
- `price_max` - Maximum price (int)
- `sort` - newest, price_asc, price_desc, name_asc, name_desc, best_rated
- `per_page` - Items per page (default: 12)

#### 2. Get Product Details
```bash
curl http://localhost:8000/api/v1/products/1
```

Response includes:
- Basic product info
- Category details
- All product images
- Average rating
- All reviews with user info

#### 3. Quick Product Search
```bash
curl "http://localhost:8000/api/v1/products/search?q=laptop"
```

Note: Minimum 2 characters required

---

### Categories - Public

#### 1. Get All Categories
```bash
curl http://localhost:8000/api/v1/categories
```

Includes product count per category.

#### 2. Get Products in Category
```bash
curl http://localhost:8000/api/v1/categories/1/products
```

**With pagination:**
```bash
curl "http://localhost:8000/api/v1/categories/1/products?per_page=20"
```

---

### Reviews - Authentication Required

#### 1. Get Product Reviews
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/v1/products/1/reviews
```

**With pagination:**
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  "http://localhost:8000/api/v1/products/1/reviews?per_page=20"
```

#### 2. Create New Review
```bash
curl -X POST http://localhost:8000/api/v1/reviews \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "product_id": 1,
    "rating": 5,
    "title": "Excelente producto",
    "comment": "Muy satisfecho con la compra, recomiendo ampliamente este producto"
  }'
```

**Required Fields:**
- `product_id` - (int) Valid product ID
- `rating` - (int) 1-5 stars
- `title` - (string) Max 255 characters
- `comment` - (string) Min 10, Max 1000 characters

**Note:** If you already reviewed this product, it will update your existing review.

#### 3. Delete Your Review
```bash
curl -X DELETE http://localhost:8000/api/v1/reviews/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Using Postman

1. Import the collection: `Postman_API_Collection.json`
2. Replace `YOUR_TOKEN_HERE` with your actual Bearer token
3. Click Send on any request

### To get a token in Postman:
1. Create a new POST request to: `http://localhost:8000/api/login`
2. Body (JSON):
   ```json
   {
     "email": "user@example.com",
     "password": "password"
   }
   ```
3. Copy the `token` from response
4. In Collection → Authorization → Type: Bearer Token → Paste token

---

## Response Examples

### Success - Get Products
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "Laptop Dell XPS 13",
        "description": "High-performance laptop",
        "price": 999.99,
        "stock": 15,
        "category": {
          "id": 2,
          "name": "Electronics"
        },
        "image": "http://localhost:8000/storage/products/laptop.jpg"
      }
    ],
    "total": 50,
    "last_page": 5,
    "per_page": 12
  }
}
```

### Success - Create Review
```json
{
  "success": true,
  "message": "Review creado exitosamente",
  "data": {
    "id": 5,
    "user_id": 1,
    "product_id": 1,
    "rating": 5,
    "title": "Excelente producto",
    "comment": "Muy satisfecho con la compra, recomiendo ampliamente este producto",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z"
  }
}
```

### Error - Validation Failed
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "rating": ["The rating field must be between 1 and 5."],
    "comment": ["The comment must be at least 10 characters."]
  }
}
```

### Error - Unauthorized
```json
{
  "success": false,
  "message": "No autorizado"
}
```

---

## Common Issues & Solutions

### "Unauthenticated" Error
- Make sure you're including the Authorization header
- Check that your token is valid
- Generate a new token if expired

### "Product not found"
- Check the product ID is valid
- Use GET /api/v1/products first to see available IDs

### Validation Errors
- Review the error message details
- Check field lengths (title max 255, comment max 1000)
- Ensure rating is between 1-5
- Comment must be at least 10 characters

### CORS Issues (Frontend Development)
- API is available locally at http://localhost:8000
- For cross-origin requests, ensure proper CORS headers are set
- Check `config/cors.php` for allowed origins

---

## API Version

**Current Version:** v1  
**Base URL:** `/api/v1/`  
**Authentication:** Sanctum Bearer Tokens  
**Response Format:** JSON  
**Pagination:** Laravel default (cursor-based with per_page parameter)  

---

## Next Steps

1. Start the server: `php artisan serve`
2. Try simple GET requests first (no auth needed)
3. Get a Bearer token for protected endpoints
4. Test POST and DELETE operations
5. Integrate with frontend using fetch/axios

For more details, see `API_DOCUMENTATION.md`
