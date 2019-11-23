== Plugin Name: MedioPay ==

Contributors: @christophbergmann
Donate link: ---
Tags: paywall, tips, bsv, moneybutton, revenue
Requires at least: 4.6
Tested up to: 5.2.4
Stable tag: 5.2.4
Requires PHP: 6.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html


== Description ==

MedioPay allows PayWalls and Tip Button for Wordpress using the BSV-app MoneyButton. Features include:

- zero fees
- one-click payments
- paywall management with second editor field or shortcode
- payments directly to your wallet
- No third party registration needed
- incentivise your audience - first buyers get a share on later payments, ref links possible
- analytics - payments contain metainformation, allowing to track and analyse the performance of your content
- global analytics of most valuable posts: tiping and paying is sharing
- uses blockchain technology - Bitcoin SV (BSV) and MoneyButton




== Installation == 

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->MedioPay screen to configure the plugin 


== Frequently Asked Questions ==

= What are the requirements to use MedioPay? =

MedioPay allows you to monetize your content. It requires you to not create any account. 

The onliest requirement to use MedioPay for publishers is to have a cryptocurrency wallet compatible with Bitcoin SV (BSV). Here is a list of wallets available: https://bitcoinsv.io/services/wallets-and-exchanges/. However, we recommend MoneyButton, as it allows you to test your own paywalls. https://www.moneybutton.com/

The money you earn will go directly to your wallet. No middle men involved.

= How do I configure the settings of MedioPay? =

The setting page (see mp_screenshot_1.png) contains two required and eight advanced options. The requirerd options are a currency to denominate the amounts for paywalls and tips, as well as an address for Bitcoin SV. This address can be in the classical format (1XYv...), a paymail address or a moneybutton id. 

In the advanced options you can deactivate sending post metadata with a transaction (for privacy reasons). You can also deactivate the second editor field in case it causes inconveniences. 

You can set a sharing quote and a reflink share, which both determine how much of your income your share with readers which either buy your posts early or share it.

Setting a defaults amount for payments and tips as well as a fixed thank you message will make MedioPay more convenient for you.



= How can I add a paywall to my post? =

Once you activated our plugin, the field “Content behind a paywall” will appear in your editor. Simply put the content you wish to have behind a paywall into this field. You may insert for text, pictures, audio or video. Then put the amount you wish to charge into the field “PayWall Cost” and you are ready to go. (mp_screenshot_2.png)

Alternatively, you can insert the shortcode ```[paywall amount=”xy”]``` , write the hidden text behind it and close it with ```[/paywall]```. If you have set a default amount in the options, you can spare the amount flag. (mp_screenshot_3.png)

Pro Tip: If you use both methods, you can add two paywalls to your post. Note: The shortcode should only used in the main editor. It will always appear as the first paywall. 

= How can I add a tip button to my post? =

In your post’s Screen Options, activate the field MedioPay Tips. The MedioPay Tips box will appear on the right-hand side of your post and lets you set an amount. A button for tips will show up on the bottom of the post. If you set a default amount for tips in the MedioPay plugin options, you may leave the amount form empty. Don't forget to add a thank you message which will be shown when a reader send you a tip. (mp_screenshot_4.png, mp_screenshot_5.png, mp_screenshot_6.png).

= What do my readers need to pay for my posts? =

Your readers need to have a funded account at MoneyButton.com. With MoneyButton, payments are facilitated by just the click of a button. You should inform your readers that MoneyButton is the gateway to a growing ecosystem of BSV apps that rely on using MoneyButton. (mp_screenshot_7.png).

To make it easier for you to onboard your readers, we made the site 
https://www.mediopay.com/bsv-how where your readers can read about Bitcoin SV, can receive a small initial funding and can buy limited amounts of BSV (from a third party provider).

= Will MedioPay work well with other plugins? =

We tested the plugin and made sure that it is compatible with many other popular plugins. Though it is impossible to test everything in all contexts. 

MedioPay works best with the classic WordPress editor. If you have trouble using it with the Gutenberg editor, you can either downgrade to the classic editor or deactivate the paywall editor in the options and instead use the [paywall] shortcode to hide the content of your choice.

If you still happen to have an issue, please contact us and let us know: support@mediopay.com

= How does MedioPay share my income? = 

A core feature of MedioPay is that it allows to let your readers participate in your income streams. This is meant to incentivize them to pay for your articles and share them.

MedioPay uses an own mechanism to split and share the generated income. In the default settings, 10 percent of the income you generate flows to the first reader who pays you. In the options you can disable income sharing or increase the rate to up to 40 percent if you wish. In this case, 40 percent of the income is shared with the first four readers who send you a payment.

A second method to split and share the generated income is through affiliate links: After a payment is made, the buyer receives a referral link connected to the article. If anyone else the link and makes a payment, the referrer gets a share. The default is 10 percent as well, but you may disable it or increase it to up to 40 percent.

= Why does MedioPay add metadata to the payments? =

Transactions with Bitcoin SV allow to add data to transactions. MedioPay adds blogpost metadata such as title, link, and a teaser. This data is permanently stored on the blockchain. 

Why is this done? Simply because it helps the visibility of your posts. Similar to likes on Facebook or Tweets, the payment or tip button serves as a mechanism to share your post. The metadata allows to globally track the success of blog posts. You can see this value list on the mediopay website: https://www.mediopay.com/value-list/ (mp_screenshot_8.png).

If you are not comfortable with the collection of metadata, it can be disabled. In the current version of MedioPay though, this will also disable income sharing. 

= What is Bitcoin SV? = 

Bitcoin SV (BSV) is a cryptocurrency. It is not Bitcoin, which is traded as BTC on exchanges, but a fork of Bitcoin with superior properties. It is traded as BSV.

= What is MoneyButton? =

MoneyButton is a non-custodial web wallet, which allows users to pay BSV by the swipe of a button. To get an account, a simple registration process needs to be completed.

= Where can I find more information on MedioPay? = 

Please visit our website for more information: http://mediopay.com/

== Screenshots ==

Screenshots mp_screenshot_1.png to mp_screenshot_8.png are in the /assets directory of the plugin.

== Changelog ==

= 1.5 =

Introducing cookies to show paid posts automatically. Allowing bloggers to set individual texts on paywalls.

== Development ==

We manage a development version of MedioPay on https://github.com/theBergmann/MedioPay







