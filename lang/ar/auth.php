<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'الإيميل أو كلمة السر خطأ',
    'create-success' => 'تم إنشاء مستخدم جديد',
    'create-failed'=>'فشل إنشاء المستخدم',
    'create-failed-found'=>'تم التسجيل من قبل',
    'delete-user' => 'تم حذف العميل بنجاح',
    'delete-user-failed' => 'فشل حذف العميل',
    'edit-user' => 'تم تحديث العميل بنجاح',
    'edit-user-failed' => 'فشل تحديث العميل',
    'update-user-success-permissions' => 'تم تحديث أذونات العميل بنجاح',
    'update-user-failed-permissions' => 'فشل تحديث أذونات العميل',
    'update-user-success-roles' => 'تم تحديث أدوار العميل بنجاح',

    #inputs
    'inputs'=>[
        'email'=>'الإيميل',
        'password'=>'كلمة السر',
        'repassword'=>'إعادة كلمة المرور',
        'name'=>'الإسم',
        'otp_number'=>'رقم التحقق'
    ],
    'web'=>[
        'register'=>[
            'success'=>'تم إنشاء حساب جديد',
            'failed'=>'فشل إنشاء حساب جديد',
            'found'=>'الحساب موجود بالفعل'
        ],
        'login'=>[
            'success'=>'تم تسجيل الدخول',
            'failed'=>'فشل تسجيل الدخول'
        ],
        'logout'=>[
            'success'=>'تم تسجيل الخروج',
            'failed'=>'فشل تسجيل الخروج'
        ]
        ],
    'api'=>[
        'register'=>[
            'success'=>'تم إنشاء حساب جديد',
            'failed'=>'فشل إنشاء حساب جديد',
            'found'=>'الحساب موجود بالفعل'
        ],
        'login'=>[
            'success'=>'تم تسجيل الدخول',
            'failed'=>'فشل تسجيل الدخول'
        ],
        'logout'=>[
            'success'=>'تم تسجيل الخروج',
            'failed'=>'فشل تسجيل الخروج'
        ] 
    ],
    'otp'=>[
        'expiry'=>'رقم تحقق منتهي',
        'wrong'=>'رقم تحقق خاطئ',
        'failed'=>'فشت عملية التحقق'
    ]

];
