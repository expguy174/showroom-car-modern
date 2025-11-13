<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(config('app.name')); ?></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            margin: 0 0 20px 0;
            color: #1a202c;
            font-size: 20px;
            font-weight: 600;
        }
        .email-body p {
            margin: 0 0 15px 0;
            line-height: 1.6;
            color: #4a5568;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #667eea;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
        }
        .info-box {
            background-color: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 5px 0;
        }
        .footer {
            background-color: #f7fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #718096;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1><?php echo e(config('app.name')); ?></h1>
        </div>
        
        <div class="email-body">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
        
        <div class="footer">
            <p>© <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. All rights reserved.</p>
            <p>
                <a href="<?php echo e(url('/')); ?>">Website</a> |
                <a href="<?php echo e(url('/contact')); ?>">Liên hệ</a> |
                <a href="<?php echo e(url('/help')); ?>">Trợ giúp</a>
            </p>
            <p style="margin-top: 15px; font-size: 11px;">
                Email này được gửi tự động. Vui lòng không trả lời email này.
            </p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/emails/layout.blade.php ENDPATH**/ ?>