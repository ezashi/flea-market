<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>取引完了通知</title>
    <style>
        body {
            font-family: 'Hiragino Sans', 'ヒラギノ角ゴシック', 'Meiryo', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #ff6b6b;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #000000ff;
            -webkit-text-stroke: 2px #fff;
            margin-bottom: 10px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .product-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .product-info h3 {
            margin-top: 0;
            color: #333;
            font-size: 18px;
        }
        .product-details {
            margin: 15px 0;
        }
        .product-details strong {
            color: #333;
        }
        .buyer-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .next-steps {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .next-steps h4 {
            margin-top: 0;
            color: #856404;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #ff6b6b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 14px;
            color: #666;
            text-align: center;
        }
        .contact-info {
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">coachtechフリマ</div>
            <div class="title">取引完了のお知らせ</div>
        </div>

        <div class="content">
            <p>{{ $seller->name }} 様</p>

            <p>いつもcoachtechフリマをご利用いただき、ありがとうございます。</p>

            <p>下記商品の取引が完了いたしましたので、ご連絡いたします。</p>

            <div class="product-info">
                <h3>📦 取引完了商品</h3>
                <div class="product-details">
                    <strong>商品名：</strong>{{ $item->name }}<br>
                    @if($item->brand)
                    <strong>ブランド：</strong>{{ $item->brand }}<br>
                    @endif
                    <strong>販売価格：</strong>¥{{ number_format($item->price) }}<br>
                    <strong>商品の状態：</strong>{{ $item->condition }}
                </div>
            </div>

            <div class="buyer-info">
                <h4>👤 購入者情報</h4>
                <strong>購入者：</strong>{{ $buyer->name }} 様<br>
                <strong>取引完了日時：</strong>{{ now()->format('Y年m月d日 H:i') }}
            </div>

            <div class="next-steps">
                <h4>🔄 今後の流れ</h4>
                <ul>
                    <li>購入者から評価をいただけるよう、お待ちください</li>
                    <li>取引相手への評価も可能です</li>
                    <li>何かご不明な点がございましたら、サポートまでお気軽にお問い合わせください</li>
                </ul>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}/mypage?tab=trade" class="button">
                    取引履歴を確認する
                </a>
            </div>

            <p>この度は、coachtechフリマでの取引をご利用いただき、ありがとうございました。<br>
            今後ともよろしくお願いいたします。</p>
        </div>

        <div class="footer">
            <p>このメールは自動配信されています。<br>
            ご返信いただいても対応できませんので、予めご了承ください。</p>
        </div>
    </div>
</body>
</html>