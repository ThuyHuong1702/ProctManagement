<?php
namespace Modules\Product\Services;

class ProductService
{
    public static function formatProductVariants(array $data)
    {
        $product = [
            'name_product' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'brand_id' => $data['brand_id'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'new_from' => $data['new_from'] ?? null,
            'new_to' => $data['new_to'] ?? null,
            'variations' => [],
            'variants' => [],
        ];

        // Xử lý variations theo thứ tự xuất hiện
        $orderedVariations = [];
        foreach ($data as $key => $value) {
            if (preg_match('/^variations_([\w]+)_name$/', $key, $matches)) {
                $variationId = $matches[1];
                $orderedVariations[$variationId] = [
                    'id' => $variationId,
                    'name' => $value,
                    'type' => $data["variations_{$variationId}_type"] ?? null,
                    'values' => [],
                ];
            }
        }

        foreach ($data as $key => $value) {
            if (preg_match('/^variations_([\w]+)_values_([\w]+)_label$/', $key, $matches)) {
                $variationId = $matches[1];
                $valueId = $matches[2];
                $orderedVariations[$variationId]['values'][$valueId] = [
                    'label' => $value,
                    'value' => $valueId,
                ];
            }
        }
        // Gán variations vào product
        $product['variations'] = $orderedVariations;

        // Tạo tất cả các tổ hợp biến thể
        $variationValues = array_map(fn($var) => array_values($var['values']), $orderedVariations);
        $combinations = self::generateCombinations($variationValues);

        // Gán biến thể vào variants[]
        $variantKeys = array_filter(array_keys($data), fn($key) => preg_match('/^variants_([\w]+)_sku$/', $key));
        $variantKeys = array_values($variantKeys);

        foreach ($combinations as $index => $combo) {
            if (!isset($variantKeys[$index])) {
                break;
            }
            preg_match('/^variants_([\w]+)_sku$/', $variantKeys[$index], $matches);
            $variantId = $matches[1];

            $product['variants'][$variantId] = [
                'name' => implode(' / ', $combo),
                'sku' => $data["variants_{$variantId}_sku"] ?? null,
                'price' => $data["variants_{$variantId}_price"] ?? null,
                'special_price' => $data["variants_{$variantId}_special_price"] ?? null,
                'special_price_type' => $data["variants_{$variantId}_special_price_type"] ?? null,
                'special_price_start' => $data["variants_{$variantId}_special_price_start"] ?? null,
                'special_price_end' => $data["variants_{$variantId}_special_price_end"] ?? null,
                'manage_stock' => $data["variants_{$variantId}_manage_stock"] ?? 0,
                'in_stock' => $data["variants_{$variantId}_in_stock"] ?? 0,
                'is_active' => isset($data["variants_{$variantId}_is_active"]) ? ($data["variants_{$variantId}_is_active"] === 'on' ? 1 : 0) : 0,
                'is_default' => isset($data["default_variant"]) && $data["default_variant"] === $variantId ? 1 : 0,
            ];
        }

        return $product;
    }

    private static function generateCombinations($arrays, $prefix = [])
    {
        if (!$arrays) return [$prefix];

        $result = [];
        $firstArray = array_shift($arrays);

        foreach ($firstArray as $value) {
            $result = array_merge($result, self::generateCombinations($arrays, array_merge($prefix, [$value['label']])));
        }

        return $result;
    }


    public static function formatProductVariantsForUpdate(array $data)
{
    $isActive = isset($data['is_active']) && $data['is_active'] === 'on' ? 1 : 0;
    $product = [
        'name' => $data['name'] ?? null,
        'description' => $data['description'] ?? null,
        'brand_id' => $data['brand_id'] ?? null,
        'category_id' => $data['category_id'] ?? null,
        'short_description' => $data['short_description'] ?? null,
        'new_from' => $data['new_from'] ?? null,
        'new_to' => $data['new_to'] ?? null,
        'price' => $data['price'] ?? 0,
        'special_price' => $data['special_price'] ?? null,
        'special_price_type' => $data['special_price_type'] ?? 1,
        'special_price_start' => $data['special_price_start'] ?? null,
        'special_price_end' => $data['special_price_end'] ?? null,
        'selling_price' => $data['selling_price'] ?? null,
        'sku' => $data['sku'] ?? null,
        'manage_stock' => $data['manage_stock'] ?? 0,
        'qty' => $data['qty'] ?? null,
        'in_stock' => $data['in_stock'] ?? 1,
        'is_active' => $isActive,
        'variations' => [],
        'variants' => [],
    ];

    // Xử lý variations
    $orderedVariations = [];
    foreach ($data as $key => $value) {
        if (preg_match('/^variations_([\w]+)_name$/', $key, $matches)) {
            $variationId = $matches[1];
            $orderedVariations[$variationId] = [
                'id' => $variationId,
                'name' => $value,
                'type' => $data["variations_{$variationId}_type"] ?? null,
                'values' => [],
            ];
        }
    }

    foreach ($data as $key => $value) {
        if (preg_match('/^variations_([\w]+)_values_([\w]+)_label$/', $key, $matches)) {
            $variationId = $matches[1];
            $valueId = $matches[2];
            $orderedVariations[$variationId]['values'][$valueId] = [
                'label' => $value,
                'value' => $valueId,
            ];
        }
    }

    $product['variations'] = $orderedVariations;

    // Tạo tổ hợp biến thể
    $variationValues = array_map(fn($var) => array_values($var['values']), $orderedVariations);
    $combinations = self::generateCombinations($variationValues);

    // Gán biến thể vào variants[]
    $variantKeys = array_filter(array_keys($data), fn($key) => preg_match('/^variants_([\w]+)_sku$/', $key));
    $variantKeys = array_values($variantKeys);

    foreach ($combinations as $index => $combo) {
        if (!isset($variantKeys[$index])) {
            break;
        }
        preg_match('/^variants_([\w]+)_sku$/', $variantKeys[$index], $matches);
        $variantId = $matches[1];

        $product['variants'][$variantId] = [
            'name' => implode(' / ', $combo),
            'sku' => $data["variants_{$variantId}_sku"] ?? null,
            'price' => $data["variants_{$variantId}_price"] ?? null,
            'special_price' => $data["variants_{$variantId}_special_price"] ?? null,
            'special_price_type' => $data["variants_{$variantId}_special_price_type"] ?? null,
            'special_price_start' => $data["variants_{$variantId}_special_price_start"] ?? null,
            'special_price_end' => $data["variants_{$variantId}_special_price_end"] ?? null,
            'manage_stock' => $data["variants_{$variantId}_manage_stock"] ?? 0,
            'qty' => $data["variants_{$variantId}_qty"] ?? null,
            'in_stock' => $data["variants_{$variantId}_in_stock"] ?? 0,
            'is_active' => isset($data["variants_{$variantId}_is_active"]) ? 1 : 0,
            'is_default' => isset($data["default_variant"]) && $data["default_variant"] === $variantId ? 1 : 0,
        ];
    }

    return $product;
}

}
