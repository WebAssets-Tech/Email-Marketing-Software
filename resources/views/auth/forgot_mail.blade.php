<div style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2 style="color: #007bff; text-align: center;">Welcome to {{ org('company_name') }}</h2>
    <p>Dear User,</p>
    <p>We have generated a new password for you:</p>
    <div
        style="background-color: #f9f9f9; padding: 15px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 20px; font-family: 'Courier New', monospace; text-align: center; color: #333;">
        <strong>{{ $code }}</strong>
    </div>
    <p style="margin-top: 15px;">For your security, please do not share this password with anyone. If you didn't request
        a password reset, please contact our support team immediately.</p>
    <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
    <p style="text-align: center; color: #555;">Thank you for choosing <strong>{{ org('company_name') }}</strong>. If you
        need assistance, we’re here to help!</p>
</div>
