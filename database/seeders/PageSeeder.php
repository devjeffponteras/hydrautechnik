<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $homeHTML = '
            <div class="container topmargin-lg bottommargin-lg">
                <div class="row">
                    <div class="col-md-5">
                        <div class="heading-block border-bottom-0">
                            <h1>Welcome to our website!</h1>
                        </div>
                        <p class="lead">Integer luctus, odio sit amet ultricies feugiat, urna massa suscipit lectus, vel eleifend justo libero et ex.</p>

                        <blockquote>
                            Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nunc semper tortor in nulla fermentum imperdiet.
                        </blockquote>
                        
                        <a href="about.htm" class="btn bg-color text-white">Read more about us</a>
                        <a href="books.htm" class="btn bg-color text-white">See all our books</a>
                    </div>

                    <div class="col-md-7 align-self-end">
                        <div class="position-relative overflow-hidden">
                            <img src="'.\URL::to('/').'/theme/images/misc/devices.png" data-animate="fadeInUp" data-delay="100" alt="Chrome">
                        </div>
                    </div>
                </div>
            </div>

            <div class="section dark my-0" style="background-color:#21395f;">
                <div class="container">
                    <div class="heading-block center border-bottom-0">
                        <h3>Featured Books</h3>
                    </div>
                    
                    <div id="oc-portfolio" class="owl-carousel portfolio-carousel carousel-widget" data-pagi="false" data-items-xs="1" data-items-sm="2" data-items-md="3" data-items-xl="4">
                        {Featured Products}
                    </div>
                    
                    <div class="text-center m-auto w-75">                   
                        <a href="news.htm" class="button button-border button-rounded ms-0 topmargin-sm button-small">View All</a>
                    </div>
                </div>
            </div>

            <div class="section my-0">
                <div class="container">
                    <div class="heading-block center border-bottom-0">
                        <h3>Best Sellers</h3>
                    </div>
                    
                    <div id="oc-portfolio" class="owl-carousel portfolio-carousel carousel-widget" data-pagi="false" data-items-xs="1" data-items-sm="2" data-items-md="3" data-items-xl="5">
                        {Best Sellers}
                    </div>
                    
                    <div class="text-center m-auto w-75">                   
                        <a href="books.htm" class="button button-border button-rounded ms-0 topmargin-sm button-small">View All</a>
                    </div>
                </div>
            </div>


            <div class="section my-0" style="background-color:white;">
                <div class="container">
                    <div class="heading-block center border-bottom-0">
                        <h3>New Releases</h3>
                    </div>
                    
                    <div id="oc-portfolio" class="owl-carousel portfolio-carousel carousel-widget" data-pagi="false" data-items-xs="1" data-items-sm="2" data-items-md="3" data-items-xl="5">
                        {New Releases}
                    </div>
                    
                    <div class="text-center m-auto w-75">                   
                        <a href="books.htm" class="button button-border button-rounded ms-0 topmargin-sm button-small">View All</a>
                    </div>
                </div>
            </div>


            <div class="section my-0">
                <div class="container">
                    <div class="heading-block center">
                        <h3>Latest News</h3>
                    </div>

                    <div id="oc-posts" class="owl-carousel posts-carousel carousel-widget posts-md" data-pagi="false" data-items-xs="1" data-items-sm="2" data-items-md="3" data-items-lg="4">
                        {Featured Articles}
                    </div>

                    <div class="text-center m-auto w-75">                   
                        <a href="news.htm" class="button button-border button-rounded ms-0 topmargin-sm button-small">Read More</a>
                    </div>
                </div>
            </div>';


        $aboutHTML = '
            <h2>Who We Are</h2>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>

            <p class="nobottommargin">"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>';


        $contactUsHTML = '
            <h3>Contact Details</h3>
                        
            <div class="row">
                <div class="col-md-6">
                    <fieldset>
                        <strong>Mailing Address:</strong><br>
                        Unit 907-909 Antel Global Corporate Center, Julia Vargas<br>
                        Avenue, Ortigas Center, Pasig City, Philippines<br>
                    </fieldset>
                    
                    <fieldset>
                        <strong>E-mail:</strong><br>
                        Sales: sales@webfocus.ph<br>
                        Marketing: marketing@webfocus.ph<br>
                        Billing: billing@webfocus.ph<br>
                        Customer Care: customercare@webfocus.ph<br>
                        Tech Support: support@webfocus.ph<br>
                    </fieldset>
                </div>
                <div class="col-md-3">
                    <fieldset>
                        <strong>Telephone:</strong><br>
                        +63 (2) 8706-6144<br>
                        +63 (2) 8706-5796<br>
                        +63 (2) 8511-0528<br>
                        +63 (2) 8709-8061<br>
                        +63 (2) 8806-5201<br>
                    </fieldset>
                </div>
                
                <div class="col-md-3">
                    <fieldset>
                        <strong>Mobile:</strong><br>
                        +63 908 869 4069 (Smart)<br>
                        +63 917 569 7380 (Globe)<br>
                        +63 922 330 8373 (Sun)<br>
                    </fieldset>
                </div>
            </div>

            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.2644008336374!2d121.06034437481797!3d14.5840041774833!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c869d9acf3bd%3A0x3d08a34bc750b469!2sWebFocus%20Solutions%2C%20Inc.!5e0!3m2!1sen!2sph!4v1683084531924!5m2!1sen!2sph" width="100%" height="55" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

            <div class="row topmargin d-none">
                <div class="col-lg-6">
                    <address>
                        <abbr title="Address">Address:</abbr><br>
                        444a EDSA, Guadalupe Viejo, Makati City, Philippines 1211
                    </address>
                </div>
                <div class="col-lg-6">
                    <p><abbr title="Email Address">Email:</abbr><br>info@vanguard.edu.ph</p>
                </div>
                <div class="col-lg-6">
                    <p class="nomargin"><abbr title="Phone Number">Phone:</abbr><br>(632) 8-1234-4567</p>
                </div>
                <div class="col-lg-6">
                    <p class="nomargin"><abbr title="Phone Number">Fax:</abbr><br>(632) 8-1234-4567</p>
                </div>
            </div>';

        $footerHTML = '
            <div class="container clearfix">
                <div class="footer-widgets-wrap pb-3 border-bottom clearfix">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="d-flex clearfix">
                                <div class="pe-4 ps-1">
                                    <i class="h3 icon-clock1"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <address>
                                        <abbr title="address"><strong>Our Office is open:</strong><br></abbr>
                                        From Mondays to Fridays (Except Holidays)<br>
                                        08:00AM to 05:00PM<br>
                                    </address>
                                </div>
                            </div>
                            <div class="d-flex clearfix">
                                <div class="pe-4 ps-1">
                                    <i class="icon-call h3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="bottommargin-sm">
                                        <abbr title="Phone Number"><strong>Phone:</strong></abbr> +63 (02) 518-7610<br>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center clearfix">
                                <div class="pe-4 ps-1">
                                    <i class="icon-envelope21 h3 mb-0"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <abbr title="Email Address"><strong>Email:</strong></abbr> ebookstore@phr.com.ph
                                </div>
                            </div>

                            {Social Media Icons}
                        </div>
                        
                        <div class="col-lg-2 col-md-6 col-12">
                            <div class="widget clearfix">

                                <h4 class="ls0 mb-3 nott">Customer Care</h4>

                                <ul class="list-unstyled iconlist ms-0">
                                    <li><a href="#">Terms of Use</a></li>
                                    <li><a href="#">FAQs</a></li>
                                    <li><a href="#">Privacy Policy</a></li>
                                </ul>

                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="widget clearfix">

                                <h4 class="ls0 mb-3 nott">Subscribe Now</h4>
                                <div class="widget subscribe-widget mt-2 clearfix">
                                    <p class="mb-4"><strong>Subscribe</strong> to Our Newsletter to get Important News, Amazing Offers &amp; Inside Scoops:</p>
                                    <div class="widget-subscribe-form-result"></div>
                                    <form id="widget-subscribe-form" action="include/subscribe.php" method="post" class="mt-1 mb-0 d-flex">
                                        <input type="email" id="widget-subscribe-form-email" name="widget-subscribe-form-email" class="form-control sm-form-control required email" placeholder="Enter your Email Address">

                                        <a href="#" class="button nott fw-normal ms-1 my-0" data-bs-toggle="modal" data-bs-target=".bs-example-modal-centered">Subscribe Now</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal fade bs-example-modal-centered" tabindex="-1" role="dialog" aria-labelledby="centerModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="card rounded-1 dark" style="background: linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.3)), url('.\URL::to('/').'/theme/images/misc/subscribe.jpeg) no-repeat center center / cover; padding: 60px 50px; border: 12px solid #FFF">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h2 class="card-title text-white font-body">Subscribe to our Newsletter!</h2>
                                        </div>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum nisi beatae temporibus nobis optio eos?</p>

                                        <div class="subscribe-widget" data-loader="button">

                                            <div class="widget-subscribe-form-result"></div>

                                            <form action="include/subscribe.php" role="form" method="post" class="mb-0">
                                                <label for="widget-subscribe-form-email">Name <span>*</span></label>
                                                <input type="email" name="widget-subscribe-form-email" id="widget-subscribe-form-email" class="form-control required not-dark" placeholder="your name">
                                                <label for="widget-subscribe-form-email">Email Address <span>*</span></label>
                                                <input type="email" name="widget-subscribe-form-email" id="widget-subscribe-form-email" class="form-control required not-dark" placeholder="name@email.com">
                                                <button class="btn rounded btn-danger py-2 mt-3 w-100 text-uppercase ls1 fw-semibold" type="submit">Subscribe</button>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="copyrights" class="bg-transparent">
                <div class="container clearfix">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-6">
                            Copyright &copy; 2023 All Rights Reserved, Precious Pages Corp.
                        </div>

                        <div class="col-md-6 d-md-flex flex-md-column align-items-md-end mt-4 mt-md-0">
                            <div class="copyrights-menu copyright-links clearfix">
                                <a href="#">Home</a>/
                                <a href="#">About</a>/
                                <a href="#">Features</a>/
                                <a href="#">Portfolio</a>/
                                <a href="#">FAQs</a>/
                                <a href="#">Contact</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

      
        $pages = [
            [
                'parent_page_id' => 0,
                'album_id' => 1,
                'slug' => 'home',
                'name' => 'Home',
                'label' => 'Home',
                'contents' => $homeHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => 'Home',
                'meta_keyword' => 'home',
                'meta_description' => 'Home page',
                'user_id' => 1,
                'template' => 'home',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'about-us',
                'name' => 'About Us',
                'label' => 'About Us',
                'contents' => $aboutHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => '',
                'meta_title' => 'About Us',
                'meta_keyword' => 'About Us',
                'meta_description' => 'About Us page',
                'user_id' => 1,
                'template' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],

            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'contact-us',
                'name' => 'Contact Us',
                'label' => 'Contact Us',
                'contents' => $contactUsHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => '',
                'meta_title' => 'Contact Us',
                'meta_keyword' => 'Contact Us',
                'meta_description' => 'Contact Us page',
                'user_id' => 1,
                'template' => 'contact-us',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'news',
                'name' => 'News and Updates',
                'label' => 'News and Updates',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'customize',
                'image_url' => '',
                'meta_title' => 'News',
                'meta_keyword' => 'news',
                'meta_description' => 'News page',
                'user_id' => 1,
                'template' => 'news',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'footer',
                'name' => 'Footer',
                'label' => 'footer',
                'contents' => $footerHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'user_id' => 1,
                'template' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ];

        DB::table('pages')->insert($pages);
    }
}
