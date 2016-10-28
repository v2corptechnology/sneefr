<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>@yield('title')</title>

<style type="text/css">
    img {max-width: 100%;}
    .footer p, .footer a, .footer a:visited, .footer a:active, .footer td {
        color: #999;
        font-size: 12px;
    }

    @media only screen and (max-width: 640px) {
      body {padding: 0 !important;}
      h1 {font-weight: 800 !important; margin: 20px 0 5px !important;}
      h2 {font-weight: 800 !important; margin: 20px 0 5px !important;}
      h3 {font-weight: 800 !important; margin: 20px 0 5px !important;}
      h4 {font-weight: 800 !important; margin: 20px 0 5px !important;}
      h1 {font-size: 22px !important;}
      h2 {font-size: 18px !important;}
      h3 {font-size: 16px !important;}
      .content {padding: 0 !important;}
      .content-wrap {padding: 10px !important;}
    }
</style>
</head>

<?php
    $sm_font = "font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top;";
    $md_font = "font-family: Helvetica, Arial, sans-serif; font-size: 15px; font-weight: normal; vertical-align: top;";
    $lg_font = "font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top;";
?>

<body itemscope itemtype="http://schema.org/EmailMessage" style="{{ $sm_font }} -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">

<table style="{{ $sm_font }} width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
    <tr style="{{ $sm_font }} margin: 0;">
        <td style="{{ $sm_font }} margin: 0;" valign="top">

        </td>
		<td width="600" style="{{ $sm_font }} display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0 !important; width: 100% !important;" valign="top">
			<div style="{{ $sm_font }} max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
				<table width="100%" cellpadding="0" cellspacing="0" style="{{ $sm_font }} border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">

                    <tr style="{{ $sm_font }} margin: 0;">
						<td style="{{ $lg_font }} color: #000000; font-weight: 500; border-radius: 3px 3px 0 0; background-color: #ffffff; margin: 0; padding: 20px;" bgcolor="#FF9F00" valign="top">
                            <a href="{{ route('home') }}" title="Visit Sidewalks.city">
                                <img src="{{ asset('img/logo-sidewalks.png') }}" alt="Sidewalks" height="40">
                            </a>
						</td>
					</tr>

                    @hasSection('pre-title')
                        <tr style="{{ $sm_font }} margin: 0;">
                            <td style="{{ $sm_font }} margin: 0; padding: 20px 20px 0 20px; text-align: center" valign="top">

                                @yield('pre-title')

                            </td>
                        </tr>
                    @endif

					<tr style="{{ $sm_font }} margin: 0;">
						<td style="{{ $lg_font }} color: #fff; font-weight: 500; text-align: center; background-color: #ff316e; margin: 0; padding: 20px;" align="center" bgcolor="#FF9F00" valign="top">

                            @yield('title')

                        </td>
                    </tr>

                    @hasSection('image')
                        <tr style="{{ $sm_font }} margin: 0;">
                            <td style="{{ $lg_font }} color: #fff; font-weight: 500; text-align: center; background-color: #ff316e; margin: 0; padding: 0 20px 20px 20px;" align="center" bgcolor="#FF9F00" valign="top">

                                @yield('image')

                            </td>
                        </tr>
                    @endif

					<tr style="{{ $md_font }} margin: 0;">
                        <td style="{{ $md_font }} margin: 0; padding: 20px 20px 0 20px;" valign="top">

                            @yield('content')

                        </td>
                    </tr>

                    @hasSection('content.button')
                        <tr style="{{ $sm_font }} margin: 0;">
                            <td style="{{ $sm_font }} margin: 0; padding: 20px 20px 0 20px; text-align: center" valign="top">

                                @yield('content.button')

                            </td>
                        </tr>
                    @endif

                    @hasSection('content.button')
                        <tr style="{{ $sm_font }} margin: 0;">
                            <td style="{{ $sm_font }} margin: 0; padding: 20px 20px 0 20px;" valign="top">

                                <hr style="margin: 20px auto; border-bottom: 1px solid #cacaca; border-left: 0; border-right: 0; border-top: 0; clear: both; height: 0; max-width: 580px;">&nbsp;

                            </td>
                        </tr>
                    @endif

                    @hasSection('secondary.1')
                        <tr style="{{ $sm_font }} margin: 0;">
                            <td style="{{ $sm_font }} margin: 0; padding: 0 60px;" valign="top">
                                <table style="border-collapse: collapse; border-spacing: 0; display: table; padding: 0; position: relative; text-align: left; vertical-align: top; width: 100%;">
                                    <tr style="padding: 0; text-align: left; vertical-align: top;">
                                        <td width="50%" style="color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-size: 15px; font-weight: normal; line-height: 19px; margin: 0; padding: 0 10px 0 0; text-align: left;">

                                            @yield('secondary.1')

                                        </td>
                                        <td width="50%" style="color: #0a0a0a; font-family: Helvetica, Arial, sans-serif; font-size: 15px; font-weight: normal; line-height: 19px; margin: 0; padding: 0 0 0 10px; text-align: left;">

                                            @yield('secondary.2')

                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endif

                    @hasSection('content.button')
                        <tr style="{{ $sm_font }} margin: 0;">
                            <td style="{{ $sm_font }} margin: 0; padding: 20px 20px 0 20px;" valign="top">

                                <hr style="margin: 20px auto; border-bottom: 1px solid #cacaca; border-left: 0; border-right: 0; border-top: 0; clear: both; height: 0; max-width: 580px;">&nbsp;

                            </td>
                        </tr>
                    @endif

                    @hasSection('footer')
                        <tr style="{{ $sm_font }} margin: 0;">
                            <td style="{{ $sm_font }} font-size: 12px; color: #777777 !important; margin: 0; padding: 0px 20px 20px;" valign="top">

                                @yield('footer')

                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </td>
    </tr>
    <tr>
		<td style="{{ $sm_font }} margin: 0;" valign="top">

        </td>
	</tr>
</table>
<table class="footer" width="100%" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
    <tr style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
            @lang('mail.footer', ['url' => route('home')])
            @lang('mail.subscriptions', ['url' => route('me.show')])
        </td>
    </tr>
</table>
</body>
</html>
