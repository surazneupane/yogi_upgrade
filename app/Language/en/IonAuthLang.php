<?php

return [
    'account_creation_successful' => 'Account Successfully Created',
    'account_creation_unsuccessful' => 'Unable to Create Account',
    'account_creation_duplicate_email' => 'Email Already Used or Invalid',
    'account_creation_duplicate_identity' => 'Identity Already Used or Invalid',
    'account_creation_missing_default_group' => 'Default group is not set',
    'account_creation_invalid_default_group' => 'Invalid default group name set',

    'password_change_successful' => 'Password Successfully Changed',
    'password_change_unsuccessful' => 'Unable to Change Password',
    'forgot_password_successful' => 'Password Reset Email Sent',
    'forgot_password_unsuccessful' => 'Unable to email the Reset Password link',

    'activate_successful' => 'Account Activated',
    'activate_unsuccessful' => 'Unable to Activate Account',
    'deactivate_successful' => 'Account De-Activated',
    'deactivate_unsuccessful' => 'Unable to De-Activate Account',
    'activation_email_successful' => 'Activation Email Sent. Please check your inbox or spam',
    'activation_email_unsuccessful' => 'Unable to Send Activation Email',
    'deactivate_current_user_unsuccessful' => 'You cannot De-Activate yourself.',

    'login_successful' => 'Logged In Successfully',
    'login_unsuccessful' => 'Incorrect Login',
    'login_unsuccessful_not_active' => 'Account is inactive',
    'login_timeout' => 'Temporarily Locked Out. Try again later.',
    'logout_successful' => 'Logged Out Successfully',

    'update_successful' => 'Account Information Successfully Updated',
    'update_unsuccessful' => 'Unable to Update Account Information',
    'delete_successful' => 'User Deleted',
    'delete_unsuccessful' => 'Unable to Delete User',

    'group_creation_successful' => 'Group created successfully.',
    'group_already_exists' => 'Group name already taken.',
    'group_update_successful' => 'Group details updated.',
    'group_delete_successful' => 'Group deleted.',
    'group_delete_unsuccessful' => 'Unable to delete group.',
    'group_delete_notallowed' => 'Can\'t delete the administrators\' group.',
    'group_name_required' => 'Group name is a required field.',
    'group_name_admin_not_alter' => 'Admin group name can not be changed.',

    'email_activation_subject' => 'Account Activation',
    'email_activate_heading' => 'Activate account for %s',
    'email_activate_subheading' => 'Please click this link to %s.',
    'email_activate_link' => 'Activate Your Account',

    'email_forgotten_password_subject' => 'Forgotten Password Verification',
    'email_forgot_password_heading' => 'Reset Password for %s',
    'email_forgot_password_subheading' => 'Please click this link to %s.',
    'email_forgot_password_link' => 'Reset Your Password',

    'email_new_password_subject' => 'New Password',
    'email_new_password_heading' => 'New Password for %s',
    'email_new_password_subheading' => 'Your password has been reset to: %s',
];
