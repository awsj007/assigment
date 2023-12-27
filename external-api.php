<?php
/*
Plugin Name: External API
Plugin URI: https://jsonplaceholder.typicode.com/posts
Description: Simple plugin that get data from External api
Version: 1.0
Author URI: #
*/

defined('ABSPATH') || die ('Unauthorized access');

add_shortcode( 'external_data', 'assigment_external_api' );

function custom_js_css_cdn(){
    $plugin_url = plugin_dir_url(__FILE__);

      // JQuery Enqueue
      wp_register_script( 'Jquery', 'https://code.jquery.com/jquery-3.7.1.min.js');
      wp_enqueue_script('Jquery');

      // Tailwind CSS Enqueue
      wp_register_script( 'Tailwindcss', 'https://cdn.tailwindcss.com');
      wp_enqueue_script('Tailwindcss');

      // intlTelInput CSS & JS Enqueue
      wp_register_style( 'intl', 'https://cdn.tutorialjinni.com/intl-tel-input/17.0.19/css/intlTelInput.css' );
      wp_enqueue_style('intl');
      wp_register_script( 'intltelinput', 'https://cdn.tutorialjinni.com/intl-tel-input/17.0.19/js/intlTelInput.min.js');
      wp_enqueue_script('intltelinput');
    }
    add_action( 'wp_enqueue_scripts', "custom_js_css_cdn");


    // Enqueue scripts
    function enqueue_custom_script() {
    wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) . 'js/custom-script.js', array('jquery'), '1.0', true);
    wp_localize_script('custom-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }
    add_action('wp_enqueue_scripts', 'enqueue_custom_script');

    // AJAX handler
    add_action('wp_ajax_handle_form_submission', 'handle_form_submission');
    add_action('wp_ajax_nopriv_handle_form_submission', 'handle_form_submission');

    function handle_form_submission() {
     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['phone']) && isset($_POST['email'])) {
        
        // Retrieve form data
        $firstname = sanitize_text_field($_POST['firstname']);
        $lastname = sanitize_text_field($_POST['lastname']);
        $phone = sanitize_text_field($_POST['phone']);
        $email = sanitize_email($_POST['email']);

        // Send email
        $to = 'awaisjaved55@gmail.com';
        $subject = 'Form Submission';
        $message = "First Name: $firstname\n Last Name: $lastname\nEmail: $email\nphone: $phone";
        $message .= file_get_contents("email-template.php");
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $sent = wp_mail($to, $subject, $message, $headers);

        // Check if email was sent
        if ($sent) {
            echo "Form submitted successfully! Email sent.";
        } else {
            echo "Error: Email not sent.";
        }
    } else {
        echo "Error: Invalid request!";
    }
    wp_die();
}

        function assigment_external_api(){
            $per_page = 5;
            $page = isset($_GET['_start']) ? $_GET['_start'] : 0;
            $api_endpoint = 'https://jsonplaceholder.typicode.com/posts?_start=' . $page . '&_limit=' . $per_page; //
            $response = wp_remote_get($api_endpoint);
                if (!is_wp_error($response) && $response['response']['code'] === 200) {
                    $data = wp_remote_retrieve_body($response);
                    $posts = json_decode($data, true);
                if (!empty($posts)) {
                    foreach ($posts as $post) {?>

<div class="container mx-auto shadow-lg m-4 p-4 w-[1024px]">
    <div class="flex gap-2 justify-start align-middle p-2">
        <span
            class="text-sm font-bold text-white bg-blue-300 rounded w-10 h-10 flex justify-center align-middle p-2"><?php echo esc_html($post['id']) ?></span>
        <h1 class="text-lg font-bold"><?php echo esc_html($post['title']) ?></h1>
    </div>

    <p class="text-sm my-3"><?php echo esc_html($post['body']) ?></p>

    <button class="bg-blue-500 px-5 py-2 text-sm text-center shadow-md rounded-sm text-white font-bold transition"
        onclick="openModal('modelConfirm')">
        Subscribe Now
    </button>
</div>

<!-- Subscribe Now Popup start -->
<div id="modelConfirm" class="fixed hidden z-50 inset-0 bg-white bg-opacity-60  overflow-y-auto w-full h-full px-4 ">
    <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">

        <div class="flex justify-end p-2">
            <button onclick="closeModal('modelConfirm')" type="button"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">

            </button>
        </div>
        <form id="myForm" class="myform" action="" method="post">
            <div class="p-6 pt-0 text-left">
                <div class="flex flex-wrap -m-2">
                    <div class="p-2 w-full">
                        <h1 class="text-md text-center font-bold">Subscribe Now</h1>
                        <div class="relative">

                            <input type="text" id="firstname" name="firstname" placeholder="First Name"
                                class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                    </div>
                    <div class="p-2 w-full">
                        <div class="relative">

                            <input type="text" id="lastname" name="lastname" placeholder="Last Name"
                                class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                    </div>
                    <div class="p-2 w-full">
                        <div class="relative">

                            <input type="email" id="email" name="email" isEmail required placeholder="Email Address"
                                class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                    </div>
                    <div class="p-2 w-full">
                        <div class="relative">

                            <input type="text" id="phone" name="phone" required isPhone placeholder="Phone Number"
                                class="w-full mb-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>

                    </div>


                </div>
                <input id="submitForm" type="submit" name="submit" value="Submit"
                    class="mt-3 text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2"
                    Subscribe Now>
                <a href="#" onclick="closeModal('modelConfirm')"
                    class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center"
                    data-modal-toggle="delete-user-modal">
                    No, cancel
                </a>
            </div>
        </form>
        <div id="result"
            class="text-gray-900  font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center m-3">
        </div>
    </div>
</div>
<!-- Subscribe Now Popup End -->
<?php
            }
            echo '<div id="contentother"></div><button  class="bg-blue-500 px-5 py-2 text-white font-bold container mx-auto" id="loadMore">Load More</button>';
        } else {
            echo 'No posts found.';
        }
    } else {
        echo 'Error fetching data from the API.';
    }
}
?>