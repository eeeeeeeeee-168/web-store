# 🛒 E-Commerce Khmer

ប្រព័ន្ធទិញ-លក់អនឡាញភាសាខ្មែរ built with **Laravel + React.js + MongoDB**

## 🏗️ Stack Technology

| Layer     | Technology         |
|-----------|--------------------|
| Backend   | PHP 8.2 + Laravel 11 |
| Frontend  | React.js 18        |
| Database  | MongoDB 6.0        |
| Auth      | JWT (tymon/jwt-auth) |
| Container | Docker + Docker Compose |
| Proxy     | Nginx              |

## 📁 Structure

```
ecommerce-khmer/
├── backend/          # Laravel API
│   ├── app/
│   │   ├── Http/Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── ProductController.php
│   │   │   ├── OrderController.php
│   │   │   └── CategoryController.php
│   │   ├── Models/
│   │   │   ├── User.php
│   │   │   ├── Product.php
│   │   │   ├── Order.php
│   │   │   └── Category.php
│   │   └── Http/Middleware/AdminMiddleware.php
│   └── routes/api.php
├── frontend/         # React.js App
│   └── src/
│       ├── pages/
│       ├── components/
│       ├── context/  (Auth + Cart)
│       └── services/ (API)
├── database/
│   └── init.js       # MongoDB seed
└── docker/
    ├── docker-compose.yml
    ├── nginx.conf
    └── .env.example
```

## 🚀 ចាប់ផ្ដើម

### 1. Clone & Setup
```bash
git clone <repo>
cd ecommerce-khmer/docker
cp .env.example .env
```

### 2. Start with Docker
```bash
docker-compose up -d
```

### 3. Setup Backend
```bash
docker exec ecommerce_backend php artisan key:generate
docker exec ecommerce_backend php artisan jwt:secret
```

### 4. Access
| Service  | URL                    |
|----------|------------------------|
| Frontend | http://localhost:3000  |
| Backend  | http://localhost:8000  |
| MongoDB  | localhost:27017        |

## 🔐 API Routes

### Public
```
POST /api/auth/register
POST /api/auth/login
GET  /api/products
GET  /api/products/:id
GET  /api/categories
```

### Protected (JWT)
```
POST /api/auth/logout
GET  /api/auth/me
GET  /api/orders
POST /api/orders
POST /api/orders/:id/cancel
```

### Admin Only
```
POST   /api/admin/products
PUT    /api/admin/products/:id
DELETE /api/admin/products/:id
GET    /api/admin/orders
PUT    /api/admin/orders/:id/status
```

## 💳 Payment Methods
- 💵 Cash on Delivery
- 🏦 ABA Pay
- 🦅 Wing
- 🇰🇭 Bakong

## 📦 Features
- ✅ JWT Authentication
- ✅ Product listing + search + filter
- ✅ Shopping cart (persisted)
- ✅ Order management
- ✅ Admin panel
- ✅ Khmer language support
- ✅ Responsive design
- ✅ Docker ready
