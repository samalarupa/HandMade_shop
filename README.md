# 🎨 Artisana - Handmade Goods Marketplace

A complete e-commerce platform designed for artisans to showcase and sell their handmade products.

---

## 🔑 Key Features

### 👥 User Roles
- **👨‍💼 Admin Panel** – Full control over users and products  
  *Files:* `admindashboard.php`, `adminlogin.php`

- **✨ Artisan Portal** – Manage artisan profile and their products  
  *Files:* `artisandashboard.php`, `addproduct.php`, `artisan_details.php`

- **🛍️ Customer Experience** – Seamless browsing, cart, and checkout  
  *Files:* `home.html`, `category.php`, `cart.php`

---

## ⚙️ Core Functionality

### 📦 Product Management
- Add, edit, delete products  
  *Files:* `addproduct.php`, `edit_product.php`, `delete_product.php`

- Browse by categories  
  *Files:* `category.php`, `productlisting.php`

- View detailed product pages  
  *File:* `product_details.php`

---

### 🛒 Shopping System
- Cart management  
  *Files:* `add_to_cart.php`, `remove_from_cart.php`, `view_cart.php`

- Select quantities  
  *File:* `select_quantity.php`

- Full checkout flow  
  *Files:* `checkout.php`, `place_order.php`, `confirm_order.php`

---

### 🔐 User Authentication
- Role-based redirection  
  *Files:* `select_role.php`, `redirect_role.php`

- Secure login/logout  
  *Files:* `login.php`, `logout.php`

- User registration  
  *File:* `signup.php`

---

## 🧱 Technical Stack

- 🖥️ **Frontend:** HTML5, CSS3, JavaScript  
- ⚙️ **Backend:** PHP  
- 🗃️ **Database:** MySQL (`config.php`)

---
```
artisan-marketplace/
├── admin/               # Admin control panel
├── artisans/            # Artisan portal  
├── cart/                # Shopping cart system
├── config/              # DB connection settings
├── images/              # All media assets
│   ├── artisans/        # Artisan photos
│   └── products/        # Product images
├── sql/                 # handmade_shop.sql schema
└── views/               # Public-facing pages
```
