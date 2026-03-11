<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'privacy-policy',
                'title_en' => 'Privacy Policy',
                'title_ar' => 'سياسة الخصوصية',
                'content_en' => '<h2>Privacy Policy</h2><p>At LuxStore, we are committed to protecting your privacy. This Privacy Policy explains how we collect, use, and safeguard your personal information.</p><h3>Information We Collect</h3><p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support. This may include your name, email address, shipping address, and payment information.</p><h3>How We Use Your Information</h3><p>We use the information we collect to process your orders, communicate with you, improve our services, and personalize your shopping experience.</p><h3>Data Security</h3><p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, or disclosure.</p><h3>Your Rights</h3><p>You have the right to access, update, or delete your personal information at any time. Contact us if you wish to exercise these rights.</p><h3>Contact Us</h3><p>If you have any questions about this Privacy Policy, please contact us at privacy@luxstore.com</p>',
                'content_ar' => '<h2>سياسة الخصوصية</h2><p>في LuxStore، نحن ملتزمون بحماية خصوصيتك. توضح سياسة الخصوصية هذه كيفية جمع معلوماتك الشخصية واستخدامها وحمايتها.</p><h3>المعلومات التي نجمعها</h3><p>نجمع المعلومات التي تقدمها لنا مباشرة، مثل عند إنشاء حساب أو إجراء عملية شراء أو الاتصال بنا للحصول على الدعم. قد يشمل ذلك اسمك وعنوان بريدك الإلكتروني وعنوان الشحن ومعلومات الدفع.</p><h3>كيف نستخدم معلوماتك</h3><p>نستخدم المعلومات التي نجمعها لمعالجة طلباتك والتواصل معك وتحسين خدماتنا وتخصيص تجربة التسوق الخاصة بك.</p><h3>أمان البيانات</h3><p>نطبق تدابير أمنية مناسبة لحماية معلوماتك الشخصية من الوصول غير المصرح به أو التعديل أو الإفصاح.</p><h3>حقوقك</h3><p>لديك الحق في الوصول إلى معلوماتك الشخصية أو تحديثها أو حذفها في أي وقت. اتصل بنا إذا كنت ترغب في ممارسة هذه الحقوق.</p><h3>اتصل بنا</h3><p>إذا كان لديك أي أسئلة حول سياسة الخصوصية هذه، يرجى الاتصال بنا على privacy@luxstore.com</p>',
                'meta_description_en' => 'Learn about how LuxStore protects your privacy and handles your personal information.',
                'meta_description_ar' => 'تعرف على كيفية حماية LuxStore لخصوصيتك والتعامل مع معلوماتك الشخصية.',
                'active' => true,
                'order' => 1,
            ],
            [
                'slug' => 'terms-of-service',
                'title_en' => 'Terms of Service',
                'title_ar' => 'شروط الخدمة',
                'content_en' => '<h2>Terms of Service</h2><p>Welcome to LuxStore. By accessing and using our website, you agree to be bound by these Terms of Service.</p><h3>Use of Our Service</h3><p>You must be at least 18 years old to use our service. You agree to provide accurate information and keep your account secure.</p><h3>Products and Pricing</h3><p>All products are subject to availability. We reserve the right to modify prices at any time. Prices are displayed in Saudi Riyals (SAR).</p><h3>Orders and Payment</h3><p>By placing an order, you agree to pay the total amount including applicable taxes and shipping fees. We accept various payment methods as displayed at checkout.</p><h3>Shipping and Delivery</h3><p>We aim to deliver your orders within 2-3 business days. Delivery times may vary based on your location.</p><h3>Returns and Refunds</h3><p>You may return products within 14 days of delivery for a full refund, provided they are in original condition.</p><h3>Limitation of Liability</h3><p>LuxStore shall not be liable for any indirect, incidental, or consequential damages arising from the use of our service.</p><h3>Changes to Terms</h3><p>We reserve the right to modify these terms at any time. Continued use of our service constitutes acceptance of modified terms.</p>',
                'content_ar' => '<h2>شروط الخدمة</h2><p>مرحبًا بك في LuxStore. من خلال الوصول إلى موقعنا واستخدامه، فإنك توافق على الالتزام بشروط الخدمة هذه.</p><h3>استخدام خدمتنا</h3><p>يجب أن يكون عمرك 18 عامًا على الأقل لاستخدام خدمتنا. أنت توافق على تقديم معلومات دقيقة والحفاظ على أمان حسابك.</p><h3>المنتجات والأسعار</h3><p>جميع المنتجات تخضع للتوفر. نحتفظ بالحق في تعديل الأسعار في أي وقت. يتم عرض الأسعار بالريال السعودي (SAR).</p><h3>الطلبات والدفع</h3><p>من خلال تقديم طلب، فإنك توافق على دفع المبلغ الإجمالي بما في ذلك الضرائب المطبقة ورسوم الشحن. نقبل طرق دفع مختلفة كما هو معروض عند الدفع.</p><h3>الشحن والتسليم</h3><p>نهدف إلى تسليم طلباتك خلال 2-3 أيام عمل. قد تختلف أوقات التسليم بناءً على موقعك.</p><h3>المرتجعات والمبالغ المستردة</h3><p>يمكنك إرجاع المنتجات خلال 14 يومًا من التسليم لاسترداد كامل المبلغ، بشرط أن تكون في حالتها الأصلية.</p><h3>حدود المسؤولية</h3><p>لن تكون LuxStore مسؤولة عن أي أضرار غير مباشرة أو عرضية أو تبعية ناشئة عن استخدام خدمتنا.</p><h3>التغييرات على الشروط</h3><p>نحتفظ بالحق في تعديل هذه الشروط في أي وقت. يشكل الاستمرار في استخدام خدمتنا قبولًا للشروط المعدلة.</p>',
                'meta_description_en' => 'Read the Terms of Service for using LuxStore and making purchases.',
                'meta_description_ar' => 'اقرأ شروط الخدمة لاستخدام LuxStore وإجراء عمليات الشراء.',
                'active' => true,
                'order' => 2,
            ],
            [
                'slug' => 'about-us',
                'title_en' => 'About Us',
                'title_ar' => 'من نحن',
                'content_en' => '<h2>About LuxStore</h2><p>LuxStore is your premier destination for luxury products in Saudi Arabia. Since our founding, we have been committed to bringing you the finest selection of high-end fashion, accessories, and lifestyle products.</p><h3>Our Mission</h3><p>Our mission is to provide an exceptional shopping experience by offering authentic luxury products, outstanding customer service, and a seamless online platform.</p><h3>Quality Guarantee</h3><p>Every product in our store is carefully selected and verified for authenticity. We work directly with authorized distributors and brands to ensure you receive only genuine luxury items.</p><h3>Our Values</h3><ul><li>Authenticity: 100% genuine products</li><li>Excellence: Superior customer service</li><li>Trust: Secure and reliable shopping</li><li>Innovation: Cutting-edge e-commerce experience</li></ul><h3>Why Choose Us</h3><p>With fast delivery, easy returns, and dedicated customer support, LuxStore makes luxury shopping effortless and enjoyable.</p>',
                'content_ar' => '<h2>عن LuxStore</h2><p>LuxStore هي وجهتك الأولى للمنتجات الفاخرة في المملكة العربية السعودية. منذ تأسيسنا، التزمنا بتقديم أفضل مجموعة من منتجات الأزياء والإكسسوارات ونمط الحياة الراقية.</p><h3>مهمتنا</h3><p>مهمتنا هي توفير تجربة تسوق استثنائية من خلال تقديم منتجات فاخرة أصلية وخدمة عملاء متميزة ومنصة إلكترونية سلسة.</p><h3>ضمان الجودة</h3><p>يتم اختيار كل منتج في متجرنا والتحقق من أصالته بعناية. نعمل مباشرة مع الموزعين والعلامات التجارية المعتمدة لضمان حصولك على منتجات فاخرة أصلية فقط.</p><h3>قيمنا</h3><ul><li>الأصالة: منتجات أصلية 100%</li><li>التميز: خدمة عملاء متفوقة</li><li>الثقة: تسوق آمن وموثوق</li><li>الابتكار: تجربة تجارة إلكترونية متطورة</li></ul><h3>لماذا تختارنا</h3><p>مع التوصيل السريع والإرجاع السهل ودعم العملاء المخصص، تجعل LuxStore التسوق الفاخر سهلاً وممتعًا.</p>',
                'meta_description_en' => 'Learn about LuxStore, your premier destination for luxury shopping in Saudi Arabia.',
                'meta_description_ar' => 'تعرف على LuxStore، وجهتك الأولى للتسوق الفاخر في المملكة العربية السعودية.',
                'active' => true,
                'order' => 3,
            ],
            [
                'slug' => 'shipping-delivery',
                'title_en' => 'Shipping & Delivery',
                'title_ar' => 'الشحن والتوصيل',
                'content_en' => '<h2>Shipping & Delivery Information</h2><h3>Delivery Areas</h3><p>We deliver to all cities across Saudi Arabia.</p><h3>Delivery Time</h3><p>Standard delivery takes 2-3 business days. Express delivery is available for select areas with next-day delivery.</p><h3>Shipping Costs</h3><ul><li>Free shipping on orders over 500 SAR</li><li>Standard shipping: 25 SAR</li><li>Express shipping: 50 SAR</li></ul><h3>Order Tracking</h3><p>Once your order is shipped, you will receive a tracking number via email and SMS to monitor your delivery.</p><h3>Delivery Issues</h3><p>If you experience any issues with your delivery, please contact our customer support team immediately.</p>',
                'content_ar' => '<h2>معلومات الشحن والتوصيل</h2><h3>مناطق التوصيل</h3><p>نقوم بالتوصيل إلى جميع المدن في جميع أنحاء المملكة العربية السعودية.</p><h3>وقت التوصيل</h3><p>يستغرق التوصيل القياسي 2-3 أيام عمل. التوصيل السريع متاح لمناطق مختارة مع التوصيل في اليوم التالي.</p><h3>تكاليف الشحن</h3><ul><li>شحن مجاني للطلبات التي تزيد عن 500 ريال سعودي</li><li>الشحن القياسي: 25 ريال سعودي</li><li>الشحن السريع: 50 ريال سعودي</li></ul><h3>تتبع الطلب</h3><p>بمجرد شحن طلبك، ستتلقى رقم تتبع عبر البريد الإلكتروني والرسائل القصيرة لمراقبة التسليم.</p><h3>مشاكل التوصيل</h3><p>إذا واجهت أي مشاكل في التوصيل، يرجى الاتصال بفريق دعم العملاء على الفور.</p>',
                'meta_description_en' => 'Information about shipping, delivery times, and costs at LuxStore.',
                'meta_description_ar' => 'معلومات حول الشحن وأوقات التسليم والتكاليف في LuxStore.',
                'active' => true,
                'order' => 4,
            ],
            [
                'slug' => 'faq',
                'title_en' => 'Frequently Asked Questions',
                'title_ar' => 'الأسئلة الشائعة',
                'content_en' => '<h2>Frequently Asked Questions</h2><h3>How do I create an account?</h3><p>Click on the "Register" button in the top menu and fill in your details. You can also register using your Google account.</p><h3>How can I track my order?</h3><p>After your order is shipped, you will receive a tracking number via email. You can also view your order status in your account dashboard.</p><h3>What payment methods do you accept?</h3><p>We accept credit cards, debit cards, and various digital payment methods including Apple Pay and STC Pay.</p><h3>Can I cancel my order?</h3><p>You can cancel your order within 24 hours of placing it. After that, please contact customer support for assistance.</p><h3>How do I return a product?</h3><p>Products can be returned within 14 days of delivery. Please ensure the item is in its original condition with all tags attached.</p><h3>Are all products authentic?</h3><p>Yes, we guarantee 100% authenticity for all products sold on LuxStore. We work directly with authorized distributors.</p><h3>How can I contact customer support?</h3><p>You can reach us via email at support@luxstore.com or through the contact form on our website.</p>',
                'content_ar' => '<h2>الأسئلة الشائعة</h2><h3>كيف أقوم بإنشاء حساب؟</h3><p>انقر على زر "التسجيل" في القائمة العلوية وأدخل بياناتك. يمكنك أيضًا التسجيل باستخدام حساب Google الخاص بك.</p><h3>كيف يمكنني تتبع طلبي؟</h3><p>بعد شحن طلبك، ستتلقى رقم تتبع عبر البريد الإلكتروني. يمكنك أيضًا عرض حالة طلبك في لوحة تحكم حسابك.</p><h3>ما هي طرق الدفع التي تقبلونها؟</h3><p>نقبل بطاقات الائتمان والخصم وطرق الدفع الرقمية المختلفة بما في ذلك Apple Pay و STC Pay.</p><h3>هل يمكنني إلغاء طلبي؟</h3><p>يمكنك إلغاء طلبك خلال 24 ساعة من تقديمه. بعد ذلك، يرجى الاتصال بدعم العملاء للحصول على المساعدة.</p><h3>كيف أقوم بإرجاع منتج؟</h3><p>يمكن إرجاع المنتجات خلال 14 يومًا من التسليم. يرجى التأكد من أن المنتج في حالته الأصلية مع جميع العلامات المرفقة.</p><h3>هل جميع المنتجات أصلية؟</h3><p>نعم، نضمن أصالة 100% لجميع المنتجات المباعة على LuxStore. نعمل مباشرة مع الموزعين المعتمدين.</p><h3>كيف يمكنني الاتصال بدعم العملاء؟</h3><p>يمكنك الوصول إلينا عبر البريد الإلكتروني على support@luxstore.com أو من خلال نموذج الاتصال على موقعنا.</p>',
                'meta_description_en' => 'Find answers to frequently asked questions about shopping at LuxStore.',
                'meta_description_ar' => 'ابحث عن إجابات للأسئلة الشائعة حول التسوق في LuxStore.',
                'active' => true,
                'order' => 5,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}
