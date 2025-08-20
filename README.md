# 🚗 Showroom Car Management System

## 📋 Mô tả dự án

Hệ thống quản lý showroom xe hơi toàn diện được xây dựng trên Laravel 10, cung cấp đầy đủ chức năng cho việc quản lý bán hàng, quản lý khách hàng, và vận hành showroom xe hơi.

## ✨ Tính năng chính

### 🏢 **Quản lý Showroom**
- Quản lý đại lý và showroom
- Quản lý nhân viên bán hàng
- Hệ thống phân quyền chi tiết

### 🚙 **Quản lý Xe hơi**
- Quản lý thương hiệu, dòng xe, biến thể
- Hình ảnh và màu sắc xe
- Tính năng và tùy chọn xe
- Thông số kỹ thuật chi tiết

### 🛒 **E-commerce**
- Giỏ hàng và đặt hàng
- Quản lý đơn hàng
- Danh sách yêu thích
- Hệ thống thanh toán

### 👥 **Quản lý Khách hàng**
- Hồ sơ khách hàng
- Đặt lịch lái thử
- Hệ thống đánh giá
- Tin nhắn liên hệ

### 💰 **Dịch vụ Tài chính**
- Tùy chọn trả góp
- Giao dịch thanh toán
- Hoàn tiền và quản lý nợ
- Lịch trình trả góp

### 📱 **Marketing & Communication**
- Khuyến mãi và ưu đãi
- Blog và tin tức
- Hệ thống thông báo
- SEO tối ưu

## 🛠️ Công nghệ sử dụng

### **Backend**
- **Laravel 10** - PHP Framework
- **MySQL/SQLite** - Database
- **Redis** - Cache & Sessions
- **Queue Jobs** - Xử lý bất đồng bộ

### **Frontend**
- **Blade Templates** - View Engine
- **Tailwind CSS** - Styling
- **Alpine.js** - Interactive Components
- **Chart.js** - Data Visualization

### **Libraries & Tools**
- **Laravel Sanctum** - API Authentication
- **Laravel Mail** - Email System
- **Laravel Notifications** - Notification System
- **Laravel Events** - Event Handling

## 📁 Cấu trúc dự án

```
showroom-car-1/
├── app/
│   ├── Application/          # Use Cases & Business Logic
│   ├── Console/              # Artisan Commands
│   ├── Enums/                # Enumerations
│   ├── Events/               # Event Classes
│   ├── Helpers/              # Helper Functions
│   ├── Http/                 # Controllers & Middleware
│   ├── Listeners/            # Event Listeners
│   ├── Mail/                 # Mail Classes
│   ├── Models/               # Eloquent Models
│   ├── Notifications/        # Notification Classes
│   ├── Observers/            # Model Observers
│   ├── Providers/            # Service Providers
│   └── Services/             # Business Services
├── database/
│   ├── migrations/           # Database Migrations
│   ├── seeders/              # Database Seeders
│   └── factories/            # Model Factories
├── resources/
│   ├── views/                # Blade Templates
│   ├── css/                  # Stylesheets
│   └── js/                   # JavaScript
├── routes/                   # Route Definitions
├── storage/                  # File Storage
└── tests/                    # Test Files
```

## 🚀 Cài đặt và chạy

### **Yêu cầu hệ thống**
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/SQLite

### **Bước 1: Clone dự án**
```bash
git clone <repository-url>
cd showroom-car-1
```

### **Bước 2: Cài đặt dependencies**
```bash
composer install
npm install
```

### **Bước 3: Cấu hình môi trường**
```bash
cp .env.example .env
php artisan key:generate
```

### **Bước 4: Cấu hình database**
```bash
# Trong file .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=showroom_car
DB_USERNAME=root
DB_PASSWORD=
```

### **Bước 5: Chạy migrations và seeders**
```bash
php artisan migrate
php artisan db:seed
```

### **Bước 6: Tạo storage link**
```bash
php artisan storage:link
```

### **Bước 7: Chạy dự án**
```bash
# Terminal 1: Laravel
php artisan serve

# Terminal 2: Frontend assets (nếu cần)
npm run dev
```

## 🔐 Tài khoản mặc định

### **Admin**
- Email: `admin@showroom.com`
- Password: `password`

### **Sales Person**
- Email: `sales@showroom.com`
- Password: `password`

### **Customer**
- Email: `customer@showroom.com`
- Password: `password`

## 📊 Database Schema

Dự án sử dụng 30+ bảng được thiết kế theo kiến trúc chuẩn:

- **Core System**: Users, Addresses, Payment Methods
- **Car Management**: Brands, Models, Variants, Images, Colors, Features
- **Business Operations**: Dealerships, Showrooms, Accessories
- **E-commerce**: Cart, Orders, Wishlist
- **Customer Management**: Profiles, Test Drives, Reviews
- **Financial Services**: Finance Options, Transactions, Installments
- **Marketing**: Promotions, Notifications, Blogs

Xem chi tiết tại: [Database Migrations README](database/migrations/README.md)

## 🧪 Testing

### **Chạy tests**
```bash
# Tất cả tests
php artisan test

# Tests cụ thể
php artisan test --filter=AuthTest
php artisan test --filter=OrderTest
```

### **Test Coverage**
```bash
# Với Xdebug
php artisan test --coverage
```

## 📈 Performance & Optimization

### **Caching**
- Database query caching
- View caching
- Route caching
- Config caching

### **Database Optimization**
- Indexes tối ưu
- Query optimization
- Connection pooling

### **Frontend Optimization**
- Asset minification
- Image optimization
- Lazy loading

## 🔒 Security Features

- **Authentication**: Laravel Sanctum
- **Authorization**: Role-based access control
- **CSRF Protection**: Built-in CSRF tokens
- **SQL Injection**: Eloquent ORM protection
- **XSS Protection**: Blade template escaping
- **File Upload Security**: Validation & scanning

## 📱 API Endpoints

### **Authentication**
```
POST /api/auth/login
POST /api/auth/register
POST /api/auth/logout
```

### **Cars**
```
GET /api/cars
GET /api/cars/{id}
GET /api/cars/{id}/variants
```

### **Orders**
```
GET /api/orders
POST /api/orders
GET /api/orders/{id}
PUT /api/orders/{id}
```

## 🚀 Deployment

### **Production Checklist**
- [ ] Environment variables configured
- [ ] Database migrations run
- [ ] Storage link created
- [ ] Cache cleared
- [ ] Queue workers started
- [ ] SSL certificate installed
- [ ] Backup system configured

### **Deployment Commands**
```bash
# Production deployment
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

## 📚 Documentation

- [Database Schema](database/migrations/README.md)
- [API Documentation](docs/api.md)
- [User Manual](docs/user-manual.md)
- [Admin Guide](docs/admin-guide.md)

## 🤝 Contributing

1. Fork dự án
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Mở Pull Request

## 📄 License

Dự án này được phân phối dưới giấy phép MIT. Xem file `LICENSE` để biết thêm chi tiết.

## 📞 Support

- **Email**: support@showroom.com
- **Documentation**: [docs.showroom.com](https://docs.showroom.com)
- **Issues**: [GitHub Issues](https://github.com/username/showroom-car-1/issues)

## 🎯 Roadmap

### **Version 2.0**
- [ ] Mobile App (React Native)
- [ ] AI-powered recommendations
- [ ] Advanced analytics dashboard
- [ ] Multi-language support
- [ ] Advanced reporting system

### **Version 3.0**
- [ ] IoT integration
- [ ] Blockchain for vehicle history
- [ ] AR/VR showroom experience
- [ ] Predictive maintenance
- [ ] Advanced CRM integration

---

**Made with ❤️ by Showroom Team**
