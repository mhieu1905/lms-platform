<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
    <title>Learning Reminder</title>
    <style type="text/css">
        /* Outlook-specific CSS */
        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }
        /* Force Outlook to provide a "view in browser" message */
        #outlook a {
            padding: 0;
        }
        /* Force Hotmail to display emails at full width */
        .ReadMsgBody {
            width: 100%;
        }
        .ExternalClass {
            width: 100%;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f5f7fa; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
    <!-- Outlook Background Color Fix -->
    <!--[if gte mso 9]>
    <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
        <v:fill type="tile" color="#f5f7fa"/>
    </v:background>
    <![endif]-->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f5f7fa">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 8px;">
                    <!--[if mso]>
                    <tr>
                        <td style="padding: 40px 0; background-color: #6da933;">
                    <![endif]-->
                    <!--[if !mso]><!-->
                    <tr>
                        <td align="center" bgcolor="#6da933" style="padding: 40px 0; border-radius: 8px 8px 0 0;">
                    <!--<![endif]-->
                            <h1 style="margin: 0; color: #ffffff; font-family: Arial, sans-serif; font-size: 28px; font-weight: bold; mso-line-height-rule: exactly;">
                                @if($type === 'inactive')
                                    We Miss You!
                                @else
                                    Stay On Track!
                                @endif
                            </h1>
                        </td>
                    </tr>

                    <!-- Content section with improved compatibility -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="padding-bottom: 20px; font-family: Arial, sans-serif; font-size: 24px; color: #2d3748;">
                                        Hello {{ $user->name }},
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-family: Arial, sans-serif; font-size: 16px; line-height: 1.6; color: #4a5568; padding-bottom: 20px;">
                                        @if($type === 'inactive')
                                            We noticed that you haven't been active recently. Your learning progress is waiting for you!
                                        @else
                                            It seems your recent learning activity shows some risk of falling behind. Let's get you back on track!
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom: 20px;">
                                        <p style="font-family: Arial, sans-serif; font-size: 16px;">
                                            Your current average progress: <strong>{{ $avgProgress }}%</strong><br>
                                            Days since last activity: <strong>{{ $inactiveDays }} days</strong>
                                        </p>
                                    </td>
                                </tr>

                                <!-- CTA Button with VML fallback for Outlook -->
                                <tr>
                                    <td align="center" style="padding: 30px 0;">
                                        <!--[if mso]>
                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://eduma.oncloudtop.com{{ route('home.index', [], false) }}" style="height:50px;v-text-anchor:middle;width:200px;" arcsize="50%" strokecolor="#6da933" fillcolor="#6da933">
                                            <w:anchorlock/>
                                            <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:16px;font-weight:bold;">
                                                {{ $type === 'inactive' ? 'Continue Learning →' : 'Get Back on Track →' }}
                                            </center>
                                        </v:roundrect>
                                        <![endif]-->
                                        <!--[if !mso]><!-->
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="center" bgcolor="#6da933" style="border-radius: 25px;">
                                                    <a href="https://eduma.oncloudtop.com{{ route('home.index', [], false) }}" target="_blank" style="display: inline-block; padding: 16px 36px; color: #ffffff; font-family: Arial, sans-serif; font-size: 16px; font-weight: bold; text-decoration: none;">
                                                        {{ $type === 'inactive' ? 'Continue Learning →' : 'Get Back on Track →' }}
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                        <!--<![endif]-->
                                    </td>
                                </tr>

                                <!-- Stats -->
                                <tr>
                                    <td>
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f7fafc; border-radius: 8px;">
                                            <tr>
                                                <td width="33.33%" align="center" style="padding: 20px;">
                                                    <div style="font-family: Arial, sans-serif; font-size: 28px; font-weight: bold; color: #6da933;">24/7</div>
                                                    <div style="font-family: Arial, sans-serif; font-size: 12px; color: #718096; text-transform: uppercase;">Access</div>
                                                </td>
                                                <td width="33.33%" align="center" style="padding: 20px;">
                                                    <div style="font-family: Arial, sans-serif; font-size: 28px; font-weight: bold; color: #6da933;">∞</div>
                                                    <div style="font-family: Arial, sans-serif; font-size: 12px; color: #718096; text-transform: uppercase;">Resources</div>
                                                </td>
                                                <td width="33.33%" align="center" style="padding: 20px;">
                                                    <div style="font-family: Arial, sans-serif; font-size: 28px; font-weight: bold; color: #6da933;">100%</div>
                                                    <div style="font-family: Arial, sans-serif; font-size: 12px; color: #718096; text-transform: uppercase;">Support</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td bgcolor="#f7fafc" style="padding: 30px; border-top: 1px solid #e2e8f0; border-radius: 0 0 8px 8px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="font-family: Arial, sans-serif; font-size: 14px; color: #718096;">
                                        Keep learning, keep growing!
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 10px; font-family: Arial, sans-serif; font-size: 12px; color: #a0aec0;">
                                        You're receiving this because you're a valued member of our learning community.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
