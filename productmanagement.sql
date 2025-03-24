-- Create database products_management
DROP DATABASE IF EXISTS `products_management`;

CREATE DATABASE `products_management` CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;
    
USE products_management

SELECT * FROM products

-- Create table brands
DROP TABLE IF EXISTS `brands`;

CREATE TABLE brands (
    `id` INT PRIMARY KEY,
    `slug` VARCHAR(191) NOT NULL,
    `is_active` TINYINT(1),
    `createc_at` TIMESTAMP,
    `updated_at` TIMESTAMP
)AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = UTF8MB4_UNICODE_CI;

-- create table categories

CREATE TABLE categories (
	`id` INT PRIMARY KEY,
	`parent_id` INT NOT NULL,
	`slug` VARCHAR(191) NOT null,
	`position` INT,
	`is_searchable` TINYINT(1),
	`is_active` TINYINT(1),
   `createc_at` TIMESTAMP,
   `updated_at` TIMESTAMP
)AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = UTF8MB4_UNICODE_CI;


-- create table variations

CREATE TABLE `variations` (
	`id` INT PRIMARY KEY,
	`name` VARCHAR(191) NOT NULL,
	`type` VARCHAR(191),
	`is_global` TINYINT(1),
	`position` INT,
	`deleted_at` TIMESTAMP,
	`created_at` TIMESTAMP,
	`updated_at` TIMESTAMP,
)AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = UTF8MB4_UNICODE_CI;

-- create table variation_values

CREATE TABLE `variation_values` (
	`id` INT PRIMARY KEY,
	`variation_id` INT NOT NULL,
	`lable` VARCHAR(191) NOT NULL,
	`value` VARCHAR(191),
	`position` INT,
	`createc_at` TIMESTAMP,
   `updated_at` TIMESTAMP,
   CONSTRAINT FK_variation_id FOREIGN KEY (`variation_id`) REFERENCES variations (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
)AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = UTF8MB4_UNICODE_CI;

-- create table products

CREATE TABLE `products` (
	`id` INT PRIMARY KEY,
	`brand_id` INT NOT NULL,
	`description` text,
	`price` DECIMAL(18,4),
	`special_price` DECIMAL(18,4),
	`special_price_type` VARCHAR(191),
	`special_price_start` DATE,
	`special_price_end` DATE,
	`selling_price` DECIMAL(18,4),
	`sku` VARCHAR(191),
	`manage_stock` TINYINT(1),
	`qty` INT,
	`in_stock` TINYINT(1),
	`viewed` INT,
	`is_active` TINYINT(1),
	`sm_description` text,
	`new_from` DATETIME,
	`new_to` DATETIME,
	`position` INT,
	`deleted_at` TIMESTAMP,
	`createc_at` TIMESTAMP,
   `updated_at` TIMESTAMP,
    CONSTRAINT FK_brand_id FOREIGN KEY (`brand_id`) REFERENCES brands (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
)AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = UTF8MB4_UNICODE_CI;

-- create table product_variants

CREATE TABLE `product_variants` (
	`id` INT PRIMARY KEY,
	`uid` VARCHAR(191),
	`uids` TEXT,
	`product_id` INT NOT NULL,
	`name` VARCHAR(191),
	`price` DECIMAL(18,4),
	`special_price` DECIMAL(18,4),
	`special_price_type` VARCHAR(191),
	`special_price_start` DATE,
	`special_price_end` DATE,
	`selling_price` DECIMAL(18,4),
	`sku` VARCHAR(191),
	`manage_stock` TINYINT(1),
	`qty` INT,
	`in_stock` TINYINT(1),
	`is-default` TINYINT(1),
	`is_active` TINYINT(1),
	`position` INT,
	`deleted_at` TIMESTAMP,
	`createc_at` TIMESTAMP,
   `updated_at` TIMESTAMP,
    CONSTRAINT FK_product_id FOREIGN KEY (`product_id`) REFERENCES products (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
)AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = UTF8MB4_UNICODE_CI;
	`

-- create table `product_variations`

CREATE TABLE `product_variations` (
	`product_id` INT NOT NULL,
	`variation_id` INT NOT NULL,
	CONSTRAINT PK_product_variations_variation_id_product_id PRIMARY KEY (`product_id`, `variation_id`),
	CONSTRAINT FK_variation_id FOREIGN KEY (`variation_id`) REFERENCES options (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
   CONSTRAINT FK_product_id FOREIGN KEY (`product_id`) REFERENCES products (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = UTF8MB4_UNICODE_CI;

	
	
-- create table product_categories

CREATE TABLE product_categories (
	`category_id` INT NOT NULL,
	`product_id` INT NOT NULL,
	CONSTRAINT PK_product_categories_category_id_product_id PRIMARY KEY (`order_id`, `product_id`),
	CONSTRAINT FK_category_id FOREIGN KEY (`category_id`) REFERENCES categories (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
   CONSTRAINT FK_product_id FOREIGN KEY (`product_id`) REFERENCES products (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = UTF8MB4_UNICODE_CI;

-- create table product_options

CREATE TABLE product_options (
	`product_id` INT NOT NULL,
	`option_id` INT NOT NULL,
	CONSTRAINT PK_product_options_option_id_product_id PRIMARY KEY (`product_id`, `option_id`),
	CONSTRAINT FK_option_id FOREIGN KEY (`option_id`) REFERENCES options (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
   CONSTRAINT FK_product_id FOREIGN KEY (`product_id`) REFERENCES products (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = UTF8MB4_UNICODE_CI;

INSERT INTO brands (id, slug, is_active, created_at, updated_at)
VALUES
(1, 'brand-1', 1, NOW(), NOW()),
(2, 'brand-2', 1, NOW(), NOW()),
(3, 'brand-3', 0, NOW(), NOW());

INSERT INTO products (id, brand_id, slug, price, special_price, special_price_type, selling_price, sku, manage_stock, qty, in_stock, is_active, created_at, updated_at)
VALUES
(1, 1, 'product-1', 100.50, 90.00, 'fixed', 90.00, 'P1001', 1, 50, 1, 1, NOW(), NOW()),
(2, 1, 'product-2', 200.00, 180.00, 'fixed', 180.00, 'P1002', 1, 30, 1, 1, NOW(), NOW()),
(3, 2, 'product-3', 150.75, 140.00, 'fixed', 140.00, 'P1003', 1, 20, 0, 1, NOW(), NOW()),
(4, 2, 'product-4', 300.99, 270.99, 'fixed', 270.99, 'P1004', 1, 10, 1, 0, NOW(), NOW()),
(5, 3, 'product-5', 50.00, 45.00, 'fixed', 45.00, 'P1005', 1, 100, 1, 1, NOW(), NOW());

DELETE FROM products WHERE id = 6

INSERT INTO products (id, brand_id, slug, price, special_price, special_price_type, special_price_start, special_price_end, selling_price, sku, manage_stock, qty, in_stock, is_active, created_at, updated_at)
VALUES
(6, 1, 'product-6', 100.50, 90.00, 'fixed', '2025-03-17', '2025-03-20', 90.00, 'P1006', 1, 50, 1, 1, NOW(), NOW());


DELIMITER //

CREATE TRIGGER before_insert_products
BEFORE INSERT ON products
FOR EACH ROW
BEGIN
    IF NEW.special_price IS NOT NULL 
        AND NEW.special_price_start <= CURDATE() 
        AND NEW.special_price_end >= CURDATE() THEN
        IF NEW.special_price_type = 'fixed' THEN
            SET NEW.selling_price = NEW.price - NEW.special_price;
        ELSEIF NEW.special_price_type = 'percent' THEN
            SET NEW.selling_price = NEW.price - (NEW.special_price * NEW.price / 100);
        ELSE
            SET NEW.selling_price = NEW.price;
        END IF;
    ELSE
        SET NEW.selling_price = NEW.price;
    END IF;
END;
//

DELIMITER ;

DELIMITER //

CREATE TRIGGER before_update_products
BEFORE UPDATE ON products
FOR EACH ROW
BEGIN
    IF NEW.special_price IS NOT NULL 
        AND NEW.special_price_start <= CURDATE() 
        AND NEW.special_price_end >= CURDATE() THEN
        IF NEW.special_price_type = 'fixed' THEN
            SET NEW.selling_price = NEW.price - NEW.special_price;
        ELSEIF NEW.special_price_type = 'percent' THEN
            SET NEW.selling_price = NEW.price - (NEW.special_price * NEW.price / 100);
        ELSE
            SET NEW.selling_price = NEW.price;
        END IF;
    ELSE
        SET NEW.selling_price = NEW.price;
    END IF;
END;
//

DELIMITER ;

SELECT * FROM products

INSERT INTO products (id, brand_id, name, tax_class_id, slug, price, special_price, special_price_type, special_price_start, special_price_end, sku, manage_stock, qty, in_stock, viewed, is_active, new_from, new_to, deleted_at, created_at, updated_at)
VALUES
-- Trường hợp 1: Giảm giá cố định (fixed) và đang trong thời gian giảm
(6, 1, 'Product 1', NULL, 'product-1', 200.00, 20.00, 'fixed', '2025-03-17', '2025-03-23', 'P1001', 1, 50, 1, 0, 1, NULL, NULL, NULL, NOW(), NOW()),

-- Trường hợp 2: Giảm giá phần trăm (percent) và đang trong thời gian giảm
(2, 1, 'Product 2', NULL, 'product-2', 300.00, 10, 'percent', '2025-03-15', '2025-03-25', 'P1002', 1, 30, 1, 0, 1, NULL, NULL, NULL, NOW(), NOW()),

-- Trường hợp 3: Hết hạn giảm giá (phải giữ nguyên giá gốc)
(3, 2, 'Product 3', NULL, 'product-3', 150.00, 15.00, 'fixed', '2025-03-10', '2025-03-15', 'P1003', 1, 20, 1, 0, 1, NULL, NULL, NULL, NOW(), NOW()),

-- Trường hợp 4: Không có giảm giá
(4, 3, 'Product 4', NULL, 'product-4', 500.00, NULL, NULL, NULL, NULL, 'P1004', 1, 10, 1, 0, 1, NULL, NULL, NULL, NOW(), NOW()),

-- Trường hợp 5: Giảm giá phần trăm nhưng chưa đến ngày bắt đầu
(5, 2, 'Product 5', NULL, 'product-5', 250.00, 20, 'percent', '2025-03-25', '2025-03-30', 'P1005', 1, 15, 1, 0, 1, NULL, NULL, NULL, NOW(), NOW());


INSERT INTO categories (id, parent_id, slug, position, is_searchable, is_active, created_at, updated_at) 
VALUES
(1, NULL, 'electronics', 1, 1, 1, NOW(), NOW()),
(2, NULL, 'fashion', 2, 1, 1, NOW(), NOW()),
(3, NULL, 'home-appliances', 3, 1, 1, NOW(), NOW()),
(4, 1, 'laptops', 4, 1, 1, NOW(), NOW()),
(5, 1, 'smartphones', 5, 1, 1, NOW(), NOW()),
(6, 2, 'clothing', 6, 1, 1, NOW(), NOW()),
(7, 2, 'shoes', 7, 1, 1, NOW(), NOW());

SELECT 
INSERT INTO product_categories (category_id, product_id) 
VALUES
(5, 2), -- Product 2 thuộc danh mục smartphones
(6, 3), -- Product 3 thuộc danh mục clothing
(7, 4), -- Product 4 thuộc danh mục shoes
(4, 5); -- Product 5 thuộc danh mục laptops


-- Chèn dữ liệu vào bảng variations
INSERT INTO `variations` (`name`, `type`, `is_global`, `position`) VALUES
('Màu sắc', 'Color', 1, 1),
('Kích thước', 'Text', 1, 2),
('Hình ảnh', 'Image', 0, 3);

-- Chèn dữ liệu vào bảng variation_values
INSERT INTO `variation_values` (`variation_id`, `label`, `value`, `position`) VALUES
-- Dữ liệu cho "Màu sắc"
(1, 'Red', '#d01c1f', 1),
(1, 'Green', '#3aa845', 2),
(1, 'Blue', '#0d46a0', 3),
(1, 'Yellow', '#f6d100', 4),

-- Dữ liệu cho "Kích thước"
(2, 'Small', 'S', 1),
(2, 'Medium', 'M', 2),
(2, 'Large', 'L', 3);

-- Dữ liệu cho "Hình ảnh"
(3, 'Front View', 'https://example.com/front.jpg', 1),
(3, 'Back View', 'https://example.com/back.jpg', 2);

SELECT * FROM variations;

SELECT * FROM product_variants

SELECT * FROM variation_values

DELETE FROM products WHERE id=1
SELECT * FROM product_categories

SELECT * FROM products

//////////////////////////////
composer require tightenco/Ziggy
npm install ziggy-js
npm list ziggy-js 
thêm vào app.js
import route from 'ziggy-js';
window.route = route;

thêm vào layout.blade.php trước script
@route

 public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:191',
            'brand_id' => 'required|exists:brands,id',
            'description' => 'nullable|string',
            'sort_description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'special_price' => 'nullable|numeric|min:0',
            'special_price_type' => 'nullable|string|in:fixed,percent',
            'special_price_start' => 'nullable|date',
            'special_price_end' => 'nullable|date',
            'selling_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku|max:191',
            'manage_stock' => 'boolean',
            'qty' => 'integer|min:0',
            'in_stock' => 'boolean',
            'viewed' => 'integer|min:0',
            'is_active' => 'boolean',
            'new_from' => 'nullable|date',
            'new_to' => 'nullable|date',
            'variants' => 'nullable|array',
            'variants.*.name' => 'required_with:variants|string|max:191',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.special_price' => 'nullable|numeric|min:0',
            'variants.*.special_price_type' => 'nullable|string|in:fixed,percent',
            'variants.*.special_price_start' => 'nullable|date',
            'variants.*.special_price_end' => 'nullable|date',
            'variants.*.sku' => 'required_with:variants|string|unique:product_variants,sku|max:191',
            'variants.*.is_default' => 'boolean'
        ]);

        $variants = $request->input('variants', []);
        $defaultVariant = collect($variants)->firstWhere('is_default', true);

        if ($variants && !$defaultVariant) {
            return redirect()->back()->withErrors(['variants' => 'Phải có ít nhất một biến thể mặc định.']);
        }

        $productData = $request->except(['price', 'special_price', 'special_price_type', 'special_price_start', 'special_price_end', 'sku']);

        if ($defaultVariant) {
            $productData['price'] = $defaultVariant['price'];
            $productData['special_price'] = $defaultVariant['special_price'] ?? null;
            $productData['special_price_type'] = $defaultVariant['special_price_type'] ?? null;
            $productData['special_price_start'] = $defaultVariant['special_price_start'] ?? null;
            $productData['special_price_end'] = $defaultVariant['special_price_end'] ?? null;
            $productData['sku'] = $defaultVariant['sku'] ?? $request->sku;
        } else {
            $productData['price'] = $request->price;
            $productData['special_price'] = $request->special_price;
            $productData['special_price_type'] = $request->special_price_type;
            $productData['special_price_start'] = $request->special_price_start;
            $productData['special_price_end'] = $request->special_price_end;
            $productData['sku'] = $request->sku;
        }

        $product = Product::create($productData);

        if (!empty($variants)) {
            foreach ($variants as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $variant['name'],
                    'price' => $variant['price'],
                    'special_price' => $variant['special_price'] ?? null,
                    'special_price_type' => $variant['special_price_type'] ?? null,
                    'special_price_start' => $variant['special_price_start'] ?? null,
                    'special_price_end' => $variant['special_price_end'] ?? null,
                    'sku' => $variant['sku'],
                    'is_default' => $variant['is_default'] ?? false,
                    'manage_stock' => $request->manage_stock ?? 0,
                    'qty' => $request->qty ?? 0,
                    'in_stock' => $request->in_stock ?? 1,
                    'is_active' => $request->is_active ?? 1,
                    'position' => 0
                ]);
            }
        }

        // Gán danh mục nếu có
    if ($request->has('category_id')) {
        $product->categories()->attach($request->category_id);
    }

    // Kiểm tra xem người dùng có muốn quay lại trang danh sách không
    if ($request->input('redirect_after_save') == "1") {
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm thành công!');
    }

    // Nếu không, quay lại trang tạo sản phẩm với thông báo thành công
    return redirect()->back()->with('success', 'Sản phẩm đã được lưu!');
    }

