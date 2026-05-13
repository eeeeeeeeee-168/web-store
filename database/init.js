// MongoDB Init Script
db = db.getSiblingDB('ecommerce_khmer');

// Create Collections
db.createCollection('users');
db.createCollection('products');
db.createCollection('categories');
db.createCollection('orders');

// Indexes
db.users.createIndex({ email: 1 }, { unique: true });
db.products.createIndex({ name: 'text', name_km: 'text' });
db.products.createIndex({ category_id: 1 });
db.products.createIndex({ is_active: 1, is_featured: 1 });
db.orders.createIndex({ user_id: 1 });
db.orders.createIndex({ order_number: 1 }, { unique: true });

// Seed Categories
db.categories.insertMany([
  {
    name: 'Electronics',
    name_km: 'អេឡិចត្រូនិច',
    slug: 'electronics',
    is_active: true,
    sort_order: 1,
    created_at: new Date(),
    updated_at: new Date()
  },
  {
    name: 'Fashion',
    name_km: 'សម្លៀកបំពាក់',
    slug: 'fashion',
    is_active: true,
    sort_order: 2,
    created_at: new Date(),
    updated_at: new Date()
  },
  {
    name: 'Food & Drink',
    name_km: 'អាហារ និងភេសជ្ជៈ',
    slug: 'food-drink',
    is_active: true,
    sort_order: 3,
    created_at: new Date(),
    updated_at: new Date()
  },
]);

// Seed Admin User (password: admin123)
db.users.insertOne({
  name: 'Admin',
  email: 'admin@ecommerce.kh',
  password: '$2y$12$YourHashedPasswordHere',
  role: 'admin',
  is_active: true,
  created_at: new Date(),
  updated_at: new Date()
});

print('✅ Database initialized successfully!');
