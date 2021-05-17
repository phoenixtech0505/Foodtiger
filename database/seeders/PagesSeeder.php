<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert([
            'title' => 'Terms and conditions',
            'content' => '<p><strong>foodtiger website Terms of Use</strong><br />
                            <br />
                            These Terms of Use govern your use of the foodtiger website and, unless otherwise stated, to your use of any other website or mobile application owned or operated by foodtiger Bulgaria or operated on behalf of foodtiger (collectively the &ldquo;Websites&rdquo;). Please read these Terms of Use carefully before using the Websites.<br />
                            <br />
                            <strong>Agreement to Terms</strong><br />
                            <br />
                            By using the Websites, you agree to these Terms of Use and the foodtiger General Online Privacy Policy (for visitors who are eighteen years of age or over) or the foodtiger Children&rsquo;s Online Privacy Policy (for visitors who are under eighteen (18) years of age) which are incorporated herein by reference. Each time you use the Websites, you reaffirm your acceptance of the then-current Terms of Use. If you do not wish to be bound by these Terms of Use, your only remedy is to discontinue using the Websites.<br />
                            <br />
                            foodtiger may change these Terms of Use at any time and in its sole discretion. The modified Terms of Use will be effective immediately upon posting and you agree to the new posted Terms of Use by continuing your use of the Websites. You are responsible for staying informed of any changes. If you do not agree with the modified Terms of Use, your only remedy is to discontinue using the Websites.<br />
                            <br />
                            <strong>Accounts</strong><br />
                            <br />
                            You may be required to create an account and specify a password to use certain features on the Websites. You agree to provide, maintain and update true, accurate, current and complete information about yourself as prompted by the registration processes. You may not impersonate any person or entity or misrepresent your identity or affiliation with any person or entity, including using another person&rsquo;s username, password, or other account information.<br />
                            <br />
                            You are entirely responsible for maintaining the confidentiality of your password and your account. And you are entirely responsible for all activity made by you or anyone that uses your account. You agree to safeguard your password from access by others. If you believe that your account has been compromised, you must immediately contact us by mail at:&nbsp;support@foodtiger.com or send a message to our live-chat service. You agree to indemnify and hold harmless foodtiger for losses incurred by foodtiger or another party due to someone else using your account as a result of your failure to use reasonable care to safeguard your password.<br />
                            <br />
                            <strong>Cancellation</strong><br />
                            <br />
                            You have the right to cancel your order up to five minutes after your order is placed on the foodtiger platform. After this point, the restaurant would have started to prepare the food and therefore no refunds would be possible. For the avoidance of doubt, timing will be assessed based on the point you place your call with our call-center, or send a message to our live-chat service. In the event of a cash-on-delivery order, your order will be delivered as instructed and cash must be collected by the rider. In the event that a customer refuses to pay the cash to our rider, foodtiger reserves the right to limit his/her future cash payments.<br />
                            <br />
                            <strong>Content Posted by Other Users</strong><br />
                            <br />
                            foodtiger is not responsible for, and does not endorse, Content in any posting made by other users on the Websites. Under no circumstances shall foodtiger be held liable, directly or indirectly, for any loss or damage caused or alleged to have been caused to you in connection with any Content posted by a third party on the Websites. If you become aware of misuse of the Websites by any person, please contact foodtiger by mail at:&nbsp;support@foodtiger.com or send a message to our live-chat service.<br />
                            <br />
                            If you feel threatened or believe someone else is in danger, you should contact the local law enforcement agency immediately.<br />
                            <br />
                            <br />
                            <strong>Activities Prohibited on the Websites</strong><br />
                            <br />
                            The following is a partial list of the kinds of conduct that are illegal or prohibited on the Websites. foodtiger reserves the right to investigate and take appropriate legal action against anyone who, in foodtiger sole discretion, engages in any of the prohibited activities. Prohibited activities include &mdash; but are not limited to &mdash; the following:<br />
                            <br />
                            - Using the Websites for any purpose in violation of local, state, or federal laws or regulations;<br />
                            - Posting Content that infringes the intellectual property rights, privacy rights, publicity rights, trade secret rights, or any other rights of any party;<br />
                            - Posting Content that is unlawful, obscene, defamatory, threatening, harassing, abusive, slanderous, hateful, or embarrassing to any other person or entity as determined by foodtiger in its sole discretion or pursuant to local community standards;<br />
                            - Posting Content that constitutes cyber-bullying, as determined by foodtiger in its sole discretion;<br />
                            - Posting Content that depicts any dangerous, life-threatening, or otherwise risky behavior;<br />
                            - Posting telephone numbers, street addresses, or last names of any person;<br />
                            - Posting URLs to external websites or any form of HTML or programming code;<br />
                            - Posting anything that may be &ldquo;spam,&rdquo; as determined by foodtiger in its sole discretion;<br />
                            - Impersonating another person when posting Content;<br />
                            - Harvesting or otherwise collecting information about others, including e-mail addresses, without their consent;<br />
                            - Allowing any other person or entity to use your identification for posting or viewing comments;<br />
                            - Harassing, threatening, stalking, or abusing any person;<br />
                            - Engaging in any other conduct that restricts or inhibits any other person from using or enjoying the Websites, or which, in the sole discretion of foodtiger, exposes foodtiger or any of its customers, suppliers, or any other parties to any liability or detriment of any type; or<br />
                            - Encouraging other people to engage in any prohibited activities as described herein.<br />
                            foodtiger reserves the right -- but is not obligated -- to do any or all of the following:<br />
                            <br />
                            - Investigate an allegation that any Content posted on the Websites does not conform to these Terms of Use and determine in its sole discretion to remove or request the removal of the Content;<br />
                            - Remove Content which is abusive, illegal, or disruptive, or that otherwise fails to conform with these Terms of Use;<br />
                            - Terminate a user&rsquo;s access to the Websites upon any breach of these Terms of Use;<br />
                            - Monitor, edit, or disclose any Content on the Websites; and<br />
                            - Edit or delete any Content posted on the Websites, regardless of whether such Content violates these standards.<br />
                            - foodtiger Trademarks and Copyrights<br />
                            <br />
                            All trademarks, logos, and service marks displayed on the Website are registered and unregistered Trademarks of foodtiger and/or third parties who have authorized their use (collectively the &ldquo;Trademarks&rdquo;)<br />
                            <br />
                            You may not use, copy, reproduce, republish, upload, post, transmit, distribute, or modify these Trademarks in any way. The use of foodtiger&#39;s trademarks on any other website is strictly prohibited. All of the materials contained on the Websites are copyrighted except where explicitly noted otherwise. foodtiger will aggressively enforce its intellectual property rights to the fullest extent of the law, including the seeking of criminal prosecution. foodtiger neither warrants nor represents that your use of materials displayed on the Websites will not infringe rights of third parties not owned by or affiliated with foodtiger. Use of any materials on the Websites is at your own risk.<br />
                            <br />
                            <strong>Hyperlinks</strong><br />
                            <br />
                            This Websites may contain hyperlinks to third-party websites. foodtiger does not control or endorse these third-party websites or any goods or services sold on those websites. Some of these websites may contain materials that are objectionable, unlawful, or inaccurate. You acknowledge and agree that foodtiger is not responsible or liable for any Content or other materials on these third party websites.<br />
                            <br />
                            <strong>Governing Law and Severability</strong><br />
                            <br />
                            These Terms of Use shall be governed by and construed in accordance with the laws of Bulgaria, without regard to its conflict of laws rules. You expressly agree that the exclusive jurisdiction for any claim or dispute under the Terms of Use and or your use of the Websites resides in the courts of Bulgaria, and you further expressly agree to submit to the personal jurisdiction of such courts for the purpose of litigating any such claim or action.<br />
                            <br />
                            If any provision of these Terms of Use is found to be invalid by any court having competent jurisdiction, the invalidity of such provision shall not affect the validity of the remaining provisions of these Terms of Use, which shall remain in full force and effect. No waiver of any provision in these Terms of Use shall be deemed a further or continuing waiver of such provision or any other provision.<br />
                            <br />
                            <strong>Payment</strong><br />
                            <br />
                            Payments are processed by Emerging Markets Online Food Delivery S.&agrave; r.l., a limited liability company (soci&eacute;t&eacute; &agrave; responsabilit&eacute; limit&eacute;e) incorporated and existing under the laws of the Grand Duchy of Luxembourg, which is the ultimate holding of the local company in Bulgaria. Cross-border subrcharges may be applicable.<br />
                            The end customer can choose between different payment methods provided on the platforms, which are currently the following: [credit card and immediate transfer.] The provider reserves the right to provide other payment methods or to no longer offer certain payment methods. The end customer bindingly chooses the payment method when placing the respective order. Provided that the end customer chooses an online payment method, the payment might be processed by an external payment provider cooperating with the provider. Card data will in this case be stored for future orders by the payment provider, on the condition that the end customer chooses the respective storage of such and hereby gives consent to it. Due to the COVID-19 emergency in the Republic of Bulgaria, all orders paid online will be delivered without physical contact.<br />
                            <br />
                            <strong>Warranties</strong><br />
                            <br />
                            The Websites and the Content are provided on an &ldquo;as is&rdquo; basis. To the fullest extent permitted by law, foodtiger, its parent, subsidiaries, and affiliates (the foodtiger entities), and each of their agents, representatives and service providers, disclaim all warranties, either expressed or implied, statutory or otherwise, including but not limited to the implied warranties of merchantibility, non-infringement of third parties rights, and fitness for particular purpose. Applicable law may not allow the exclusion of implied warranties, so the above exclusion may not apply to you. The foodtiger Entities, their agents, representatives and service providers cannot and do not guarantee or warrant that: (a) the Websites will be reliable, accurate, complete, or updated on a timely basis; (b) the Websites will be free of human and machine errors, omissions, delays, interruptions or losses, including loss of data; (c) any files available for downloading from the Websites will be free of infection by viruses, worms, Trojan horses, or other codes that manifest contaminating or destructive properties; (d) any Content you post on the Websites will remain on the Websites; or (e) the functions or services performed on the Websites will be uninterrupted or error-free or that defects in the Websites will be corrected.<br />
                            <br />
                            <strong>Limitation of Liability</strong><br />
                            <br />
                            The foodtiger entities, their agents, representatives, and service providers, entire liability and your exclusive remedy with respect to your use of the websites is to discontinue your use of the websites. The foodtiger entities, their agents, representatives, and service providers shall not be liable for any indirect, special, incidental, consequential, or exemplary damages arising from your use of the websites or for any other claim related in any way to your use of the websites. These exclusions for indirect, special, consequential, and exemplary damages include, without limitation, damages for lost profits, lost data, loss of goodwill, work stoppage, work stoppage, computer failure, or malfunction, or any other commercial damages or losses, even if the foodtiger entities, their agents, representatives, and service providers have been advised of the possibility thereof and regardless of the legal or equitable theory upon which the claim is based. Because some states or jurisdictions do not allow the exclusion or the limitation of liability for consequential or incidental damages, in such states or jurisdictions, the foodtiger entities, their agents, representatives and service providers&#39; liability shall be limited to the extent permitted by law.<br />
                            <br />
                            <strong>Termination</strong><br />
                            <br />
                            foodtiger has the right to terminate your account and access to the Websites for any reason, including, without limitation, if foodtiger, in its sole discretion, considers your use to be unacceptable, or in the event of any breach by you of the Terms of Use. foodtiger may, but shall be under no obligation to, provide you a warning prior to termination of your use of the Websites.<br />
                            <br />
                            <strong>Vouchers</strong><br />
                            <br />
                            Unless otherwise stated,<br />
                            <br />
                            - Vouchers are only applicable to food orders, excluding delivery fees and containers<br />
                            - Valid only for online payment<br />
                            - foodtiger reserves the right to cancel orders and accounts if fraud activities are detected<br />
                            - foodtiger reserves the right to stop this voucher to be used on certain restaurants without prior notice<br />
                            - Individual restaurants terms &amp; conditions apply<br />
                            <br />
                            <strong>Contact Us</strong><br />
                            <br />
                            Questions? Comments? Please send an email to us at&nbsp;contact@foodtiger.com<br />
                            <br />
                            &copy; 2020 foodtiger. All rights reserved.</p>',
            'showAsLink' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('pages')->insert([
            'title' => 'How it works',
            'content' => '<p>foodtiger is simple and easy way to order food online. Enjoy the variety of choices and cuisines which could be delivered to your home or office.</p>

                        <p>&nbsp;</p>

                        <p><strong>Here is how foodtiger works:</strong><br />
                        &nbsp;</p>

                        <p>&nbsp;</p>

                        <p><strong>Find a restaurant:</strong></p>

                        <p>Enter you address or choose from the map on the front page to set your location. Take a review on the restaurants which deliver to your address. Choose a restaurant and dive in its tasty menu.</p>

                        <p>&nbsp;</p>

                        <p><strong>Choose a dish:</strong></p>

                        <p>Choose from the displayed dishes. If there is an option to add products or sauce, for pizza for example, you will be asked for your choice right after you click on the dish. Your order will be dispayed on the right side of the page.</p>

                        <p>&nbsp;</p>

                        <p><strong>Finish your order and choose type of payment:</strong></p>

                        <p>When you complete the order with delicious food, click &quot;Buy&quot;. Now you only have to write your address and choose type of payment as you follow the instructions on the page.</p>

                        <p>&nbsp;</p>

                        <p><strong>Delivery:</strong></p>

                        <p>You will receive SMS as confirmation for your order and information about the delivery time and.....</p>

                        <p>That&#39;s all!</p>

                        <p>&nbsp;</p>',
            'showAsLink' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*DB::table('pages')->insert([
            'title' => 'Partner with us',
            'content' => '<p>You have a great restaurant and deliver the most delicious food in your city?<br />
                        You want the customers of foodtiger&nbsp;to be satisfied by your offers and discounts?</p>

                        <p><strong>foodtiger always looks for new friends!</strong></p>
                        <p><a class="btn typeform-share button" href="https://danieldimov.typeform.com/to/rmbOMG" data-mode="popup"><strong>REGISTER NOW</strong></a>',
            'showAsLink' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);*/
    }
}
