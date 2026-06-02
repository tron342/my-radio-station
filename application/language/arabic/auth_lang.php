<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Auth Lang - English
*
* Author: Ben Edmunds
* 		  ben.edmunds@gmail.com
*         @benedmunds
*
* Author: Daniel Davis
*         @ourmaninjapan
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.09.2013
*
* Description:  English language file for Ion Auth example views
*
*/

// Errors
$lang['error_csrf'] = 'This form post did not pass our security checks.';

// Login
$lang['login_heading']         = 'تسجيل الدخول';
$lang['login_subheading']      = 'الرجاء الدخول بإستخدام بريدك الإلكتروني / اسم المستخدم وكلمة المرور أدناه.';
$lang['login_identity_label']  = 'البريد الالكتروني / اسم المستخدم:';
$lang['login_password_label']  = 'كلمه المرور:';
$lang['login_remember_label']  = 'تذكرنى';
$lang['login_submit_btn']      = 'تسجيل الدخول';
$lang['login_forgot_password'] = 'نسيت كلمة المرور؟';

// Index
$lang['index_heading']           = 'الحسابات';
$lang['index_subheading']        = 'وفيما يلي قائمة من المستخدمين.';
$lang['index_username_th']       = 'إسم العضوية';
$lang['index_name_th']           = 'الإسم';
$lang['index_fname_th']          = 'الإسم الأول';
$lang['index_lname_th']          = 'اللقب';
$lang['index_email_th']          = 'العنوان الإلكتروني';
$lang['index_groups_th']         = 'المجموعة';
$lang['index_status_th']         = 'الحالة';
$lang['index_action_th']         = 'الأوامر';
$lang['index_active_link']       = 'تفعيل';
$lang['index_inactive_link']     = 'تعطيل';
$lang['index_create_user_link']  = 'إنشاء حساب';
$lang['index_create_group_link'] = 'إنشاء مجموعة';
$lang['index_active_status']     = 'مفعلة';
$lang['index_inactive_status']   = 'غير مفعلة';

// Deactivate User
$lang['deactivate_heading']                  = 'تعطيل العضوية';
$lang['deactivate_subheading']               = 'هل أنت متأكد أنك تريد تعطيل المستخدم \'%s\'';
$lang['deactivate_confirm_y_label']          = 'نعم';
$lang['deactivate_confirm_n_label']          = 'لا';
$lang['deactivate_submit_btn']               = 'إرسال';
$lang['deactivate_validation_confirm_label'] = 'تأكيد';
$lang['deactivate_validation_user_id_label'] = 'إيدي العضو';

// Create User
$lang['create_user_heading']                           = 'إنشاء مستخدم ';
$lang['create_user_subheading']                        = 'الرجاء إدخال معلومات المستخدمين أدناه. ';
$lang['create_user_fname_label']                       = 'الاسم الاول';
$lang['create_user_lname_label']                       = 'الكنية';
$lang['create_user_identity_label']                    = 'هوية';
$lang['create_user_company_label']                     = 'اسم الشركة';
$lang['create_user_email_label']                       = 'البريد الإلكتروني';
$lang['create_user_phone_label']                       = 'هاتف';
$lang['create_user_password_label']                    = 'كلمه السر';
$lang['create_user_password_confirm_label']            = 'تأكيد كلمة المرور';
$lang['create_user_submit_btn']                        = 'إنشاء مستخدم ';
$lang['create_user_validation_fname_label']            = 'الاسم الاول';
$lang['create_user_validation_lname_label']            = 'الكنية';
$lang['create_user_validation_identity_label']         = 'هوية';
$lang['create_user_validation_email_label']            = 'عنوان البريد الإلكتروني';
$lang['create_user_validation_phone_label']            = 'هاتف';
$lang['create_user_validation_company_label']          = 'اسم الشركة';
$lang['create_user_validation_password_label']         = 'كلمه السر';
$lang['create_user_validation_password_confirm_label'] = 'تأكيد كلمة المرور';

// Edit User
$lang['edit_user_heading']                           = 'تحرير العضو';
$lang['edit_user_subheading']                        = 'الرجاء إدخال معلومات المستخدمين أدناه. ';
$lang['edit_user_fname_label']                       = 'الاسم الاول';
$lang['edit_user_lname_label']                       = 'الكنية';
$lang['edit_user_company_label']                     = 'اسم الشركة';
$lang['edit_user_email_label']                       = 'البريد الإلكتروني';
$lang['edit_user_phone_label']                       = 'هاتف';
$lang['edit_user_password_label']                    = 'كلمة المرور';
$lang['edit_user_password_confirm_label']            = 'تأكيد كلمة المرور';
$lang['edit_user_password_help']                     = 'إن تغيير كلمة المرور';
$lang['edit_user_groups_heading']                    = 'عضو في مجموعة ';
$lang['edit_user_submit_btn']                        = 'حفظ العضو ';
$lang['edit_user_validation_fname_label']            = 'الاسم الاول';
$lang['edit_user_validation_lname_label']            = 'الكنية';
$lang['edit_user_validation_email_label']            = 'عنوان البريد الإلكتروني';
$lang['edit_user_validation_phone_label']            = 'هاتف';
$lang['edit_user_validation_company_label']          = 'اسم الشركة';
$lang['edit_user_validation_groups_label']           = 'مجموعة ';
$lang['edit_user_validation_password_label']         = 'كلمه السر';
$lang['edit_user_validation_password_confirm_label'] = 'تأكيد كلمة المرور';

// Create Group
$lang['create_group_title']                  = 'إنشاء المجموعة ';
$lang['create_group_heading']                = 'إنشاء المجموعة ';
$lang['create_group_subheading']             = 'الرجاء إدخال المعلومات مجموعة أدناه. ';
$lang['create_group_name_label']             = 'أسم المجموعة';
$lang['create_group_desc_label']             = 'وصف';
$lang['create_group_submit_btn']             = 'إنشاء المجموعة ';
$lang['create_group_validation_name_label']  = 'أسم المجموعة';
$lang['create_group_validation_desc_label']  = 'وصف';

// Edit Group
$lang['edit_group_title']                  = 'تحرير المجموعة';
$lang['edit_group_saved']                  = 'المجموعة المحفوظة';
$lang['edit_group_heading']                = 'تحرير المجموعة';
$lang['edit_group_subheading']             = 'الرجاء إدخال المعلومات مجموعة أدناه.';
$lang['edit_group_name_label']             = 'أسم المجموعة';
$lang['edit_group_desc_label']             = 'وصف';
$lang['edit_group_submit_btn']             = 'حفظ المجموعة';
$lang['edit_group_validation_name_label']  = 'أسم المجموعة';
$lang['edit_group_validation_desc_label']  = 'وصف';

// Change Password
$lang['change_password_heading']                               = 'تغيير كلمة المرور';
$lang['change_password_old_password_label']                    = 'كلمة المرور القديمة:';
$lang['change_password_new_password_label']                    = 'كلمة المرور الجديدة (%s حروف على الأقل):';
$lang['change_password_new_password_confirm_label']            = 'تأكيد كلمة المرور الجديدة:';
$lang['change_password_submit_btn']                            = 'تغيير';
$lang['change_password_validation_old_password_label']         = 'كلمة المرور القديمة';
$lang['change_password_validation_new_password_label']         = 'كلمة المرور الجديدة';
$lang['change_password_validation_new_password_confirm_label'] = 'تأكيد كلمة المرور الجديدة';

// Forgot Password
$lang['forgot_password_heading']                 = 'نسيت كلمة المرور';
$lang['forgot_password_subheading']              = 'من فضلك أدخل %s حتى يمكننا أن نرسل لك رسالة بالبريد الالكتروني لإعادة تعيين كلمة المرور الخاصة بك.';
$lang['forgot_password_email_label']             = '%s:';
$lang['forgot_password_submit_btn']              = 'إرسال';
$lang['forgot_password_validation_email_label']  = 'عنوان البريد الإلكتروني';
$lang['forgot_password_username_identity_label'] = 'اسم المستخدم';
$lang['forgot_password_email_identity_label']    = 'البريد الإلكتروني';
$lang['forgot_password_email_not_found']         = 'لا يوجد سجل من عنوان البريد الإلكتروني.';

// Reset Password
$lang['reset_password_heading']                               = 'تغيير كلمة المرور';
$lang['reset_password_new_password_label']                    = 'كلمة المرور الجديدة (%s حروف على الأقل):';
$lang['reset_password_new_password_confirm_label']            = 'تأكيد كلمة المرور الجديدة:';
$lang['reset_password_submit_btn']                            = 'تغيير';
$lang['reset_password_validation_new_password_label']         = 'كلمة المرور القديمة';
$lang['reset_password_validation_new_password_confirm_label'] = 'تأكيد كلمة المرور الجديدة';

// Activation Email
$lang['email_activate_heading']    = 'تفعيل حساب ل %s';
$lang['email_activate_subheading'] = 'الرجاء الضغط على هذا الرابط إلى %s.';
$lang['email_activate_link']       = 'فعل حسابك';

// Forgot Password Email
$lang['email_forgot_password_heading']    = 'إعادة تعيين كلمة المرور ل %s';
$lang['email_forgot_password_subheading'] = 'الرجاء الضغط على هذا الرابط إلى %s.';
$lang['email_forgot_password_link']       = 'اعد ضبط كلمه السر';

// New Password Email
$lang['email_new_password_heading']    = 'كلمة السر الجديدة ل %s';
$lang['email_new_password_subheading'] = 'تم إعادة تعيين كلمة المرور الخاصة بك إلى: %s';

