<?php
class JsonStorage {
    private $storagePath;
    private $cacheTime;
    
    public function __construct($storagePath = 'storage/', $cacheTime = 3600) {
        $this->storagePath = rtrim($storagePath, '/') . '/';
        $this->cacheTime = $cacheTime;
        
        // ایجاد دایرکتوری اگر وجود نداشته باشد
        if (!file_exists($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }
    }
    
    public function save($filename, $data) {
        // اعتبارسنجی نام فایل
        if (!preg_match('/^[a-zA-Z0-9_-]+\.json$/', $filename)) {
            throw new InvalidArgumentException('نام فایل نامعتبر است');
        }
        
        $fullPath = $this->storagePath . $filename;
        
        // اعتبارسنجی داده‌ها
        $this->validateData($data);
        
        // تبدیل به JSON
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('خطا در تبدیل JSON: ' . json_last_error_msg());
        }
        
        // ذخیره موقت در فایل موقت قبل از جایگزینی
        $tempFile = tempnam($this->storagePath, 'tmp');
        if (file_put_contents($tempFile, $jsonData, LOCK_EX) === false) {
            unlink($tempFile);
            throw new RuntimeException('خطا در نوشتن فایل موقت');
        }
        
        // جایگزینی اتمیک فایل
        if (!rename($tempFile, $fullPath)) {
            unlink($tempFile);
            throw new RuntimeException('خطا در جایگزینی فایل');
        }
        
        return true;
    }
    
    public function get($filename, $useCache = true) {
        $fullPath = $this->storagePath . $filename;
        
        if (!file_exists($fullPath)) {
            return null;
        }
        
        // بررسی کش
        if ($useCache && (time() - filemtime($fullPath)) < $this->cacheTime) {
            return json_decode(file_get_contents($fullPath), true);
        }
        
        // خواندن و اعتبارسنجی فایل
        $content = file_get_contents($fullPath);
        $data = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('فایل JSON خراب است');
        }
        
        return $data;
    }
    
    private function validateData($data) {
        // اعتبارسنجی عمیق داده‌ها
        if (is_array($data)) {
            array_walk_recursive($data, function($value, $key) {
                if (is_string($value)) {
                    // بررسی تزریق کد
                    if (preg_match('/<script|<\/script>/i', $value)) {
                        throw new InvalidArgumentException('داده شامل کد مخرب است');
                    }
                }
            });
        }
    }
}

// مثال استفاده:
// try {
//     $storage = new JsonStorage('secure_storage/', 1800); // کش برای 30 دقیقه
    
//     $data = [
//         'user' => 'محمد',
//         'info' => ['age' => 30, 'city' => 'تهران']
//     ];
    
//     $storage->save('user_data.json', $data);
    
//     // بازیابی داده‌ها (با کش)
//     $cachedData = $storage->get('user_data.json');
//     print_r($cachedData);
    
// } catch (Exception $e) {
//     echo 'خطا: ' . $e->getMessage();
// }
?>